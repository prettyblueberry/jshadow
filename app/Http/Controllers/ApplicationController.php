<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use App\Job;
use App\Career;
use App\Sector;
use App\Profile;
use App\Location;

use Carbon\Carbon;

use App\Application;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Mail\ApplicantThankYou;

use App\Exports\ApplicationsExport;
use App\Mail\HrApplicationComplete;

use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\MentorApplicationCancel;
use Billow\Contracts\PaymentProcessor;
use App\Mail\MentorApplicationComplete;
use Illuminate\Support\Facades\Storage;

use App\Mail\ApplicantApplicationCancel;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\StoreApplicationRequest;

class ApplicationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin', ['only' => ['index', 'edit']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('application.index', [
            'applications'  => Application::select('id', 'user_id', 'sector_id', 'career', 'company_code', 'job_id', 'location_id',
                'dates', 'payment_id', 'voucher_id', 'amount', 'paid', 'indemnity_file', 'created_at', 'updated_at',
                'deleted_at')->withTrashed()->with(['location', 'job', 'user', 'sector'])->get(),
            'companies'     => Job::select('company')->whereNotNull('company')->distinct()->orderBy('company')->pluck('company', 'company')->toArray()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Allow user to select a sector to work in.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStep1()
    {
        // Delete all applications that are incomplete for this user.
        Application::where('user_id', Auth::user()->id)->where('paid', 0)->forceDelete();

        // Redirect if the user profile has not been completed
        $profile_completed = Profile::where('user_id', Auth::user()->id)->get()->toArray();
        if (!$profile_completed) {
            return redirect()->action('ProfileController@create');
        }

        // $sectors = Job::select('sector')->distinct()->orderBy('sector')->pluck('sector', 'sector')->toArray();
        $sectors = Sector::all()->sortBy('name')->pluck('name', 'id')->toArray();

        return view('application.createStep1', [
            'sectors' => $sectors
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeStep1(Request $request)
    {
        try {
            // If a company code is present, prepopulate the applications table with the relevant data
            if ($request->company_code) {
                $jobs = Job::where('company_code', trim($request->company_code))->get();
                if (!$jobs->isEmpty()) {
                    Application::create(
                        [
                            'user_id' => Auth::user()->id,
                            'company_code' => $request->company_code,
                        ]
                    );

                    return redirect()->action('ApplicationController@createStep2');
                } else {
                    return redirect()->back()->withErrors('Invalid company code.');
                }
            } else {
                Application::create(
                    [
                        'user_id' => Auth::user()->id,
                        'sector' => $request->sector,
                        'sector_id' => $request->sector,
                    ]
                );
            }

            return redirect()->action('ApplicationController@createStep2');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()->back()->withErrors('Unable to submit application. Please try again!');
        }
    }

    /**
     * Allow user to select career.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStep2()
    {
        // Redirect if the user profile has not been completed
        $profile_completed = Profile::where('user_id', Auth::user()->id)->get()->toArray();
        if (!$profile_completed) {
            return redirect()->action('ProfileController@create');
        }

        // Redirect if user has not completed step 1
        $step_1_completed = Application::where('user_id', Auth::user()->id)->whereNotNull('sector_id')->orWhereNotNull('company_code')->where('paid', 0)->get()->toArray();
        if (!$step_1_completed) {
            return redirect()->action('ApplicationController@createStep1');
        }

        $application = Application::where('user_id', Auth::user()->id)->where('paid', 0)->first();

        // Get all the careers based on the selected sector from the user
        // $careers = Job::select('career', 'description');

        // If applicant selected a sector in step 1
        if($application->sector_id) {
            $careerApplications = Application::where("sector_id", $application->sector_id)->whereNotIn('user_id', [Auth::user()->id])->get()->groupBy("career")->toArray();
            // $careers = Job::select('career')->where('sector', $application->sector)->distinct()->orderBy('career')->get();
            // $careers = Sector::where('id', $application->sector_id)->with(['jobs'])->get();
            $careers = Career::whereHas('sectors', function($query) use ($application) {
                $query->where('id', $application->sector_id);
            })->with('jobs')->get();
        }

        // If applicant entered a company code in step 1
        if($application->company_code) {
            $company_code = $application->company_code;
            $careerApplications = Application::where("company_code", $company_code)->whereNotIn('user_id', [Auth::user()->id])->get()->groupBy("career")->toArray();
            // Fetch all the unique careers that have a job with th supplied company code
            $careers = Career::whereHas('jobs', function(Builder $query) use ($company_code) {
                $query->where('company_code', $company_code);
            })->with('jobs')
            ->distinct()
            ->get();
        }

        foreach($careers as $career) {
            $career->availableDates = false;

            if($career->jobs->isEmpty()) {
                continue;
            }

            $careerJobs = $career->jobs->toArray();

            foreach($careerJobs as $job){
                $temp_availability = array();
                foreach($job['availability'] as $availability){
                    if(!$availability) continue;
                    $arrival_time = '00';
                    if($job['arrival_time']) $arrival_time = $job['arrival_time'];
                    $from = Carbon::createFromFormat('m/d/Y H:s:i', $availability.' '.substr($arrival_time, 0, 2).':00:00');
                    $to = Carbon::createFromFormat('Y-m-d H:s:i', now());
                    $diff_hours = $to->diffInHours($from);
                    if($diff_hours > $job['period']) array_push($temp_availability, $availability);
                }
                $job['availability'] = $temp_availability;
            }

            foreach($careerJobs as $jobs) {
                foreach ($jobs['availability'] as $key => $availability) {
                    if(!$availability) {
                        continue;
                    }
                    $datesArray = explode(",", $availability);
                    $filtered = Arr::where($datesArray, function ($value, $key) {
                        return strtotime($value) > time();
                    });
                    if(!empty($filtered)) {
                        $career->availableDates = true;
                    }
                }
            }
        }

        return view('application.createStep2', [
            'careers'       => $careers->sortBy('name'),
            'application'   => $application,
            'careerApplications' => $careerApplications
        ]);
    }

    public function getDescription(Request $request)
    {
        $job_description = Job::select('description')->where('career_id', $request->career)->first();

        if(!$job_description) {
            return response()->json([], 400);
        }

        return response()->json($job_description, 200);
    }

    /**
     * Check duplicate applications.
     *
     * @return \Illuminate\Http\Response
     */
    public function isDuplicateApplication(Request $request)
    {
        $selected_sector = $request->sector;
        $selected_career = $request->career;
        $outdated = false;

        $application = Application::where('user_id', Auth::user()->id)
                        ->where('sector_id', $selected_sector)
                        ->where('career', $selected_career)
                        ->where('paid', 1)
                        ->first();

        if($application && $application->dates) {
            $date = \DateTime::createFromFormat('m/d/Y', $application->dates);
            if(Carbon::parse($date)->format('m-d-Y') <= Carbon::now()->format('m-d-Y')) {
                $outdated = true;
            }
        }

        // No duplicate found
        if(is_null($application) || $outdated) {
            return response()->json(['duplicate' => false]);
        }

        return response()->json(['duplicate' => true]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeStep2(Request $request)
    {
        try {
            Application::where('user_id', Auth::user()->id)
                       ->where('paid', 0)
                       ->update(
                            [
                                'user_id' => Auth::user()->id,
                                'career' => $request->career
                            ]
                        );

            return redirect()->action('ApplicationController@createStep3');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()->back()->withErrors('Unable to submit application. Please try again!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStep3()
    {
        // Redirect if the user profile has not been completed
        $profile_completed = Profile::where('user_id', Auth::user()->id)->get()->toArray();
        if (!$profile_completed) {
            return redirect()->action('ProfileController@create');
        }

        // Redirect if user has not completed step 2
        $step_2_completed = Application::where('user_id', Auth::user()->id)->whereNotNull('career')->where('paid', 0)->get()->toArray();
        if (!$step_2_completed) {
            return redirect()->action('ApplicationController@createStep2');
        }

        $application = Application::where('user_id', Auth::user()->id)->where('paid', 0)->first();

        // Get all the locations based on what the user selected for sector and career
        // $available_locations = Job::whereHas('sectors', function($query) use ($application) {
        //                             $query->where('id', $application->sector_id);
        //                         })
        //                         ->orWhere('company_code', $application->company_code)
        //                         ->where('career_id', $application->career)
        //                         ->distinct()
        //                         ->pluck('location')
        //                         ->toArray();

        $available_locations = Location::whereHas('jobs', function($query) use ($application) {
            $query->where('career_id', $application->career);
        })->pluck('city', 'id')->toArray();

        // dd($available_locations);

        return view('application.createStep3', [
            'available_locations' => array_map('ucwords', $available_locations)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeStep3(Request $request)
    {
        try {
            $application = Application::where('user_id', Auth::user()->id)->where('paid', 0)->first();
            $job = Job::whereHas('sectors', function($query) use ($application) {
                $query->where('id', $application->sector_id);
            })
            ->orWhere('company_code', $application->company_code)
            ->where('career_id', $application->career)
            ->where('location_id', $request->location)
            ->first();

            Application::where('user_id', Auth::user()->id)->where('paid', 0)
            ->update(
                [
                    'user_id'       => Auth::user()->id,
                    'location_id'   => $request->location,
                    'job_id'        => $job->id,
                    'sector_id'     => $job->sectors()->first()->id
                ]
            );

            return redirect()->action('ApplicationController@createStep4');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()->back()->withErrors('Unable to submit application. Please try again!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStep4()
    {
        // Redirect if the user profile has not been completed
        $profile_completed = Profile::where('user_id', Auth::user()->id)->get()->toArray();
        if (!$profile_completed) {
            return redirect()->action('ProfileController@create');
        }

        // Redirect if user has not completed step 3
        $step_3_completed = Application::where('user_id', Auth::user()->id)->whereNotNull('location_id')->where('paid', 0)->get()->toArray();
        if (!$step_3_completed) {
            return redirect()->action('ApplicationController@createStep3');
        }

        $application = Application::where('user_id', Auth::user()->id)->where('paid', 0)->with('job')->first();

        return view('application.createStep5', [
            'application'   => $application
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeStep4(Request $request)
    {
        // Skipped as this was initially storing the selected company
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStep5()
    {
        return redirect()->action('ApplicationController@createStep4');
    }

    /**
     * Return JSON data of available dates.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAvailableDates()
    {
        $application = Application::where('user_id', Auth::user()->id)->where('paid', 0)->with('job')->first();
        $available_company_ids = Job::query()
                                 ->whereHas('sectors', function($query) use ($application) {
                                    $query->where('id', $application->sector_id);
                                 })
                                 ->where('career_id', $application->career)
                                 ->where('location_id', $application->location_id)
                                 ->pluck('id')
                                 ->toArray();

                                 Log::debug($available_company_ids);

        foreach ($available_company_ids as $key => $id) {
            $selected_job = Job::find($id)->toArray();

            if(!$selected_job['availability']) {
                continue;
            }

            $available_dates = explode(',', implode(',', $selected_job['availability']));

            // remove all available dates where the max applicant number has been reached
            $filtered_available_dates[] = array_filter($available_dates, function ($date) use ($selected_job) {
                $this_application_date_count = Application::where('dates', $date)->where('job_id', $selected_job['id'])->whereNotIn('user_id', [Auth::user()->id])->count();
                if ($this_application_date_count >= $selected_job['max_applicants'] || $date == '') {
                    return false;
                }

                return true;
            });
        }

        // If no dates are available
        if(!isset($filtered_available_dates)) {
            return response()->json(['error' => ['message' => 'No available dates for your selected sector and career.']]);
        }

        // Merge all the available for each job into one array
        $filtered_available_dates_merged = array();
        foreach ($filtered_available_dates as $key => $dates) {
            $filtered_available_dates_merged = array_merge($filtered_available_dates_merged, $dates);
        }

        // Remove any dates where the applicant already has another application.
        $existing_applications = Application::where('user_id', Auth::user()->id)->whereNotNull('dates')->with('job')->get();

        $booked_dates_array = [];
        foreach ($existing_applications->toArray() as $key => $existing_appplication) {
            $booked_dates = CarbonPeriod::create(
                Carbon::create($existing_appplication['dates']),
                '1 days',
                Carbon::create($existing_appplication['dates'])->addDays($existing_appplication['job']['days_per_job_shadow'] - 1)->format('m/d/Y')
            );

            foreach ($booked_dates as $key => $date) {
                array_push($booked_dates_array, $date->format('m/d/Y'));
            }
        }

        $student_available_dates = array_diff($filtered_available_dates_merged, $booked_dates_array);

        return response()->json(array_values(array_map('trim', $student_available_dates)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeStep5(Request $request)
    {
        try {
            if (!$request->dates) {
                return redirect()->back()->withErrors('Please select an available date slot.');
            }

            // Assign a company to the applicant
            $application = Application::where('user_id', Auth::user()->id)->where('paid', 0)->with('job')->first();
            $available_company_ids = Job::whereHas('sectors', function($query) use ($application) {
                                        $query->where('id', $application->sector_id);
                                     })
                                     ->where('career_id', $application->career)
                                     ->where('location_id', $application->location_id)
                                     ->pluck('id')
                                     ->toArray();

            foreach ($available_company_ids as $key => $id) {
                $selected_job = Job::find($id)->toArray();

                if(!$selected_job['availability']) {
                    continue;
                }

                $available_dates = explode(',', implode(',', $selected_job['availability']));

                foreach ($available_dates as $key => $date) {
                    $this_application_date_count = Application::where('dates', $date)->where('job_id', $selected_job['id'])->whereNotIn('user_id', [Auth::user()->id])->count();
                    // remove all available dates where the max applicant number has been reached
                    if ($this_application_date_count >= $selected_job['max_applicants'] || $date == '') {
                        continue;
                    }

                    // If the date in the current iteration is the date, a user seleted, assign job ID to this application
                    if($date == $request->dates) {
                        Application::where('user_id', Auth::user()->id)
                                   ->where('paid', 0)
                                   ->update(
                            [
                                'user_id'   => Auth::user()->id,
                                'dates'     => $request->dates,
                                'job_id'    => $selected_job['id']
                            ]
                        );

                        break 2;
                    }
                }
            }

            return redirect()->action('ApplicationController@applicationComplete');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()->back()->withErrors('Unable to submit application. Please try again!');
        }
    }


    /**
     * Application complete.
     *
     * @return \Illuminate\Http\Response
     */
    public function applicationComplete(PaymentProcessor $payfast)
    {
        // Redirect if user has not completed step 5
        $step_5_completed = Application::where('user_id', Auth::user()->id)->whereNotNull('dates')->get()->toArray();
        if (!$step_5_completed) {
            return redirect()->action('ApplicationController@createStep5');
        }

        // Payfast
        $application = Application::where('user_id', Auth::user()->id)->with(['job', 'sector'])->orderBy('id', 'desc')->first();
        $application->update(
            [
                'user_id' => Auth::user()->id,
                'payment_id' => Auth::user()->id . '' . mt_rand(100, 1000)
            ]
        );

        $amount = ($application->amount !== NULL) ? $application->amount : $application->job->amount;
        $application->update(
            [
                'amount' => $amount
            ]
        );
        try {
            if($application->sector_id) {
                $application->sectorName = Sector::where('id', $application->sector_id)->first()->name;
            } else {
                $application->sectorName = $application->sector;
            }
            $career = Career::where('id', $application->career)->first();
            if($career) {
                $application->careerName = $career->name;
            } else {
                $application->careerName = $application->career;
            }
        } catch(\Exception $e) {
            $application->sectorName = '';
            $application->careerName = '';
        }

        // Build up payment Paramaters.
        $name = explode(' ', Auth::user()->name);
        $first_name = $name[0];
        $last_name = (array_key_exists(1, $name)) ? $name[1] : $name[0];
        $payfast->setBuyer($first_name, $last_name, Auth::user()->email);
        $payfast->setAmount($amount);
        $payfast->setItem('Job Shadow', 'description');
        $payfast->setMerchantReference($application->payment_id);

        return view('application.complete', [
            'application' => $application,
            'payfast_form' => ($amount === 0.0) ? '<a href="' . url('/payment/success') . '" class="btn btn-danger">Check out</a>' : $payfast->paymentForm('Check Out')
        ]);
    }

    /**
     * Get Application Indemnity.
     *
     * @return \Illuminate\Http\Response
     */
    public function applicationIndemnity(Request $request, $id)
    {
        if (Auth::guest()) {
            return abort(404);
        }

        if(Auth::check() && Auth::user()->role_id == 1) {
            $id = \Hashids::decode($request->id)[0];
            $application = Application::find($id);
            return Storage::download('/applications/' . $application->user->id . '/' . $application->indemnity_file);
        } else {
            $id = \Hashids::decode($request->id)[0];
            $fileIndex = \Hashids::decode($request->id)[1];
            $application = Application::where('id', $id)->with(['job'])->orderBy('created_at', 'desc')->first();
            if($application) {
                return Storage::download('/indemnity_files/' . $application->job->company . '/' . json_decode($application->job->indemnity_file)[$fileIndex]);
            } else {
                return redirect()->action('HomeController@index');
            }

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createIndemnity($id)
    {
        $application = Application::where('id', \Hashids::decode($id))->first();

        // dd($application->toArray());

        return view('application.indemnity', [
            'id'            => $id,
            'application'   => $application
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeIndemnity(Request $request)
    {
        if (Auth::guest()) {
            return abort(404);
        }

        $request->validate([
            'indemnity_file' => 'required|file|mimes:doc,docx,pdf,jpeg,jpg,zip'
        ]);

        try {
            $application = Application::where('user_id', Auth::user()->id)->where('id', \Hashids::decode($request->id))->with(['user.profile', 'job', 'location'])->orderBy('id', 'desc')->first();

            if(!$application) {
                throw new \Exception('Application not found', 1);
            }

            // Store the uploaded ID
            if ($request->hasFile('indemnity_file')) {
                $uploadedFile = $request->file('indemnity_file');
                $filename = time() . $uploadedFile->getClientOriginalName();
                Storage::disk('local')->putFileAs('applications/' . Auth::user()->id, $uploadedFile, $filename);

                // Update the filename in the profiles table
                $application->update(['indemnity_file' => $filename]);
            }

            Mail::to(Auth::user()->email)->cc('info@jobshadow.co.za')->bcc('mike@obriendesign.co.za')->send(new ApplicantThankYou($application));
            // We do this because mailtrap has a limit of 2 mails per second
            if (trim(config('mail.host')) == 'smtp.mailtrap.io') {
                sleep(3);
            }

            if($application->job->job_mentor['email']) {
                $mail = Mail::to(explode(',', $application->job->job_mentor['email']));
                $emailCC = ['info@jobshadow.co.za'];

                if($application->job->backup_job_mentor['email']) {
                    $emailCC = array_merge($emailCC, explode(',', $application->job->backup_job_mentor['email']));
                }

                $mail->cc($emailCC);
                $mail->bcc('mike@obriendesign.co.za');

                $mail->send(new MentorApplicationComplete($application));

                // We do this because mailtrap has a limit of 2 mails per second
                if (trim(config('mail.host')) == 'smtp.mailtrap.io') {
                    sleep(3);
                }
            }

            if($application->job->hr_contact['email']) {
                Mail::to(explode(',', $application->job->hr_contact['email']))->cc('info@jobshadow.co.za')->bcc('mike@obriendesign.co.za')->send(new HrApplicationComplete($application));

                // We do this because mailtrap has a limit of 2 mails per second
                if (trim(config('mail.host')) == 'smtp.mailtrap.io') {
                    sleep(3);
                }
            }

            // FOR TESTING ONLY
            // Mail::to('christiansen.marcus@gmail.com')->cc('mike@obriendesign.co.za')->send(new MentorApplicationComplete($application));

            return redirect()->action('HomeController@index');
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()->back()->withErrors('Unable to submit application. Please try again!');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $application = Application::find($id);
        if ($application === null) {
            return redirect()->back()->withErrors('Invalid application or application not found!!');
        }

        return view('application.view', [
            'application' => $application
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $application = Application::with('job')->find($id);
        if($application === null) {
            return redirect()->back()->withErrors('Invalid application or application not found!!!');
        }

        return view('application.edit', [
            'application'   => $application
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $application = Application::where('id', $id)->with(['location', 'job', 'user'])->first();

            if($application === null) {
                return redirect('application/')->withErrors('Invalid application or application not found!');
            }

            // Store the uploaded ID
            if ($request->hasFile('indemnity_file')) {
                $uploadedFile = $request->file('indemnity_file');
                $filename = time() . $uploadedFile->getClientOriginalName();
                Storage::disk('local')->putFileAs('applications/' . $application->user->id, $uploadedFile, $filename);

                // Update the filename in the profiles table
                $application->update(['indemnity_file' => $filename]);
            }

            Mail::to($application->user->email)->cc('info@jobshadow.co.za')->bcc('mike@obriendesign.co.za')->send(new ApplicantThankYou($application));
            // We do this because mailtrap has a limit of 2 mails per second
            if (trim(config('mail.host')) == 'smtp.mailtrap.io') {
                sleep(3);
            }

            if($application->job->job_mentor['email']) {
                $mail = Mail::to(explode(',', $application->job->job_mentor['email']));

                $emailCC = ['info@jobshadow.co.za'];

                if($application->job->backup_job_mentor['email']) {
                    $emailCC = array_merge($emailCC, explode(',', $application->job->backup_job_mentor['email']));
                }

                $mail->cc($emailCC);
                $mail->bcc('mike@obriendesign.co.za');
                $mail->send(new MentorApplicationComplete($application));

                // We do this because mailtrap has a limit of 2 mails per second
                if (trim(config('mail.host')) == 'smtp.mailtrap.io') {
                    sleep(3);
                }
            }
            if($application->job->hr_contact['email']) {
                Mail::to(explode(',', $application->job->hr_contact['email']))->cc('info@jobshadow.co.za')->bcc('mike@obriendesign.co.za')->send(new HrApplicationComplete($application));

                // We do this because mailtrap has a limit of 2 mails per second
                if (trim(config('mail.host')) == 'smtp.mailtrap.io') {
                    sleep(3);
                }
            }

            // FOR TESTING ONLY
            // Mail::to('christiansen.marcus@gmail.com')->cc('mike@obriendesign.co.za')->send(new MentorApplicationComplete($application));

            return redirect('application/')->with('success', 'Application successfully updated!');
        } catch(\Exception $e) {
            return redirect('application/')->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            $application = Application::find($id);

            if($application->dates) {
                $date = \DateTime::createFromFormat('m/d/Y', $application->dates);
                if(Carbon::parse($date)->format('m-d-Y') <= Carbon::now()->format('m-d-Y')) {
                    return redirect()->back()->withErrors('Unable to delete application. Applicaion is outdated');
                }
            }

            $application->delete();

            // Delete Application
            $application = Application::withTrashed()->where('id', $id)->first();

            Mail::to(Auth::user()->email)->cc('info@jobshadow.co.za')->bcc('mike@obriendesign.co.za')->send(new ApplicantApplicationCancel($application));

            // We do this because mailtrap has a limit of 2 mails per second
            if (trim(config('mail.host')) == 'smtp.mailtrap.io') {
                sleep(3);
            }

            if($application->job->job_mentor['email']) {
                $mail = Mail::to(explode(',', $application->job->job_mentor['email']));

                $emailCC = ['info@jobshadow.co.za'];

                if($application->job->backup_job_mentor['email']) {
                    $emailCC = array_merge($emailCC, explode(',', $application->job->backup_job_mentor['email']));
                }

                $mail->cc($emailCC);
                $mail->bcc('mike@obriendesign.co.za');
                $mail->send(new MentorApplicationCancel($application));

                // We do this because mailtrap has a limit of 2 mails per second
                if (trim(config('mail.host')) == 'smtp.mailtrap.io') {
                    sleep(3);
                }
            }
            if($application->job->hr_contact['email']) {
                Mail::to(explode(',', $application->job->hr_contact['email']))->cc('info@jobshadow.co.za')->bcc('mike@obriendesign.co.za')->send(new MentorApplicationCancel($application));

                // We do this because mailtrap has a limit of 2 mails per second
                if (trim(config('mail.host')) == 'smtp.mailtrap.io') {
                    sleep(3);
                }
            }

            return redirect()->back()->with('success', 'Application successfully cancelled!');
        } catch(\Exception $e) {
            return redirect()->back()->withErrors('Unable to delete application. Please try again!');
        }
    }

    /**
     * Export all applications.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        try {
            $params = ['from' => $request->from, 'to' => $request->to, 'company' => $request->company, 'search' => $request->search];

            return Excel::download(new ApplicationsExport($params), 'applications.xlsx');
        } catch(\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()->back()->withErrors('Unable to export applications. Please try again!');
        }
    }
}
