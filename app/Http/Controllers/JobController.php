<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Job;
use App\Location;
use App\Sector;
use App\Career;
use App\Imports\JobsImport;
use App\Exports\JobsExport;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Log;

class JobController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::select('id', 'career_id', 'company', 'description', 'location', 'location_id', 'availability')->get();

        foreach($jobs as $job) {
            $allPeriodDates = [];
            foreach ($job->availability as $key => $value) {
                if ($value) {
                    $value = explode(',', str_replace(' ', '', $value));
                    foreach($value as $date) {
                        if(\DateTime::createFromFormat('m/d/Y', $date) !== false) {
                            $allPeriodDates[] = $date;
                        }
                    }
                }
            }
            $job->dates = implode(', ', $allPeriodDates);
        }

        return view('order.index',
            [
                'jobs' => $jobs
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $sectors = Job::select('sector')->distinct()->orderBy('sector')->pluck('sector', 'sector')->toArray();
        // $careers = Job::select('career')->distinct()->orderBy('career')->pluck('career', 'career')->toArray();
        $locations = Location::orderBy('city')->pluck('city', 'id')->toArray();

        return view('order.create',
            [
                // 'sectors'   => $sectors,
                // 'careers'   => $careers,
                'locations' => $locations
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if($request->has('jobs')) {

            $validator = Validator::make(
                [
                    'file'      => $request->file('jobs'),
                    'extension' => strtolower($request->file('jobs')->getClientOriginalExtension()),
                ],
                [
                    'file'          => 'required',
                    'extension'      => 'required|in:csv,xlsx,xls',
                ]
            );
            if ($validator->fails()) {
                return back()->withErrors(["jobs" => "The jobs must be a file of type: csv, xlsx, xls."]);
            }
            Excel::import(new JobsImport, request()->file('jobs'));
        } else {
            $request->validate([
                'amount' => 'required|numeric'
            ]);
            // Split into array if there are multiple sectors
            $sectors = explode(',', $request->sector);
            $sector_ids = [];

            foreach ($sectors as $key => $sector) {
                $sector = Sector::firstOrNew(['name' => trim($sector)]);
                $sector->save();

                $sector_ids[] = $sector->id;
            }

            // Careers Table
            $career = Career::firstOrNew(['name' => trim($request->career)]);
            $career->save();

            // Update Pivot Table
            $career->sectors()->sync($sector_ids);

            // Update Locations Table
            $location_import_array = explode(',', $request->location);
            $location = Location::firstOrNew(
                ['city' => trim($request->location)],
                ['province' => end($location_import_array), 'country' => 'South Africa']
            );
            $location->save();

            $data = $request->except(['indemnity_file']);
            $job = Job::create($data);
            // Store the uploaded ID
            if ($request->hasFile('indemnity_file')) {

                $uploadedFiles = $request->file('indemnity_file');
                foreach ($uploadedFiles as $file) {
                    $filename = $file->getClientOriginalName();
                    $data['indemnity_file'][] = $file->getClientOriginalName();
                    Storage::disk('local')->putFileAs('indemnity_files/' . $job->company . '/', $file, $filename);
                }
            }

            if(isset($data['indemnity_file'])) {
                $data['indemnity_file'] = json_encode($data['indemnity_file']);
                Job::find($job->id)->update(['indemnity_file' => $data['indemnity_file']]);
            }


            $job->update(['career_id' => $career->id, 'location_id' => $location->id]);

            // Update Pivot Table
            $job->sectors()->sync($sector_ids);


        }

        return redirect()->action('JobController@index')->with('status', 'Successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->action('JobController@edit', ['id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job = Job::find($id, ['id', 'career_id', 'description', 'location', 'location_id', 'company',
            'company_code', 'address', 'website', 'job_mentor', 'backup_job_mentor', 'hr_contact',
            'availability', 'total_days', 'days_per_job_shadow', 'indemnity_file', 'arrival_time',
            'collection_time', 'max_applicants', 'amount', 'created_at']);

        if(!$job) {
            return abort(404);
        }

        if($job->indemnity_file) {
            $job->indemnity_file = implode(json_decode($job->indemnity_file), ', ');
        }

        // dd($job->toArray());

        // $sectors = Job::select('sector')->distinct()->orderBy('sector')->pluck('sector', 'sector')->toArray();
        $sectors = $job->sectors();
        // $careers = Job::select('career')->distinct()->orderBy('career')->pluck('career', 'career')->toArray();
        $locations = Location::orderBy('city')->pluck('city', 'id')->toArray();

        return view('order.edit',
            [
                'job'       => $job,
                // 'career'    => $job->career->name,
                'sectors'   => $sectors,
                // 'careers'   => $careers,
                'locations' => $locations
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric'
        ]);
        // Split into array if there are multiple sectors
        $sectors = explode(',', $request->sector);
        $sector_ids = [];

        foreach ($sectors as $key => $sector) {
            $sector = Sector::firstOrNew(['name' => trim(strtolower($sector))]);
            $sector->save();

            $sector_ids[] = $sector->id;
        }

         // Careers Table
        $career = Career::firstOrNew(['name' => trim(strtolower($request->career))]);
        $career->save();

        // Update Pivot Table
        $career->sectors()->sync($sector_ids);

        // Update Locations Table
        $location_import_array = explode(',', $request->location);
        $location = Location::firstOrNew(
            ['city' => trim(strtolower($request->location))],
            ['province' => end($location_import_array), 'country' => 'South Africa']
        );
        $location->save();
        $job = Job::find($id);
        $data = $request->except(['indemnity_file']);
        // Store the uploaded ID
        if ($request->hasFile('indemnity_file')) {
            $uploadedFiles = $request->file('indemnity_file');
            foreach ($uploadedFiles as $file) {
                $filename = $file->getClientOriginalName();
                $data['indemnity_file'][] = $file->getClientOriginalName();
                Storage::disk('local')->putFileAs('indemnity_files/' . $job->company . '/', $file, $filename);
            }
        }

        if(isset($data['indemnity_file'])) {
            $data['indemnity_file'] = json_encode($data['indemnity_file']);
        }

        $job->update($data);

        $job->update(['career_id' => $career->id, 'location_id' => $location->id]);

        // Update Pivot Table
        $job->sectors()->sync($sector_ids);



        return redirect()->action('JobController@edit', ['id' => $id])->with('status', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Job::find($id)->delete();

        return redirect()->action('JobController@index')->with('status', 'Successfully deleted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        return Excel::download(new JobsExport($request->all()), 'jobs.xlsx');
    }
}
