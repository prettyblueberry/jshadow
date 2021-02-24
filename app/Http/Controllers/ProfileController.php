<?php

namespace App\Http\Controllers;

use App\Career;
use App\Sector;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Profile;
use App\User;
use App\Application;

use Auth;
use Log;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function applicationsByUser($id)
    {
        $applications = Application::where('user_id', Auth::user()->id)->where('paid', 1)->with(['user.profile', 'job', 'location', 'sector'])->withTrashed()->get();
        foreach($applications as $application) {
            $application->outdated = false;

            if($application->dates) {
                $date = \DateTime::createFromFormat('m/d/Y', $application->dates);
                if(Carbon::parse($date)->format('m-d-Y') <= Carbon::now()->format('m-d-Y')) {
                    $application->outdated = true;
                }
            }

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

        }
        //dd($applications);
        return view('profile.applicationsIndex', [
            'applications' => $applications
        ]);
    }

    /**
     * Return specific users application.
     *
     * @return \Illuminate\Http\Response
     */
    public function application($userId, $applicationId)
    {
        $application = Application::where('user_id', Auth::user()->id)->where('id', $applicationId)->with(['user.profile', 'job', 'location'])->first();
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
        return response()->json($application);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('profile.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProfileRequest $request)
    {
        try {
            // Set the user_id to the currently logged in user & create the profile record for the user
            $request['user_id'] = Auth::user()->id;
            $profile = Profile::updateOrCreate(['user_id' => Auth::user()->id], $request->all());

            // Maybe update the name in the users table & link profile id to user
            User::find(Auth::user()->id)->update(
                [
                    'name'          => $request->name,
                    'profile_id'    => $profile->id
                ]
            );

            // Store the uploaded ID
            $uploadedFile = $request->file('id_file');
            $filename = time() . $uploadedFile->getClientOriginalName();
            Storage::disk('local')->putFileAs('applications/' . Auth::user()->id . '/', $uploadedFile, $filename);

            // Update the filename in the profiles table
            Profile::where('user_id', Auth::user()->id)->update(['id_path' => $filename]);

            // Store the profile photo
            $uploadedFile = $request->file('profile_photo');
            $filename = time() . $uploadedFile->getClientOriginalName();
            Storage::disk('local')->putFileAs('applications/' . Auth::user()->id . '/', $uploadedFile, $filename);

            // Update the filename in the profiles table
            Profile::where('user_id', Auth::user()->id)->update(['profile_photo' => $filename]);

            return redirect()->action('ApplicationController@createStep1')->with('status', 'Profile successfully created');

        } catch(\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()->back()->withErrors('Unable to create profile. Please try again!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::where('id', Auth::user()->id)->with('profile')->first();

        return view('profile.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request, $id)
    {
        try {

            // Set the user_id to the currently logged in user & create the profile record for the user
            $request['user_id'] = Auth::user()->id;
            $profile = Profile::updateOrCreate(['user_id' => Auth::user()->id], $request->all());

            // Maybe update the name in the users table & link profile id to user
            User::find(Auth::user()->id)->update(
                [
                    'name'          => $request->name,
                    'profile_id'    => $profile->id
                ]
            );

            if($request->hasFile('id_file')) {
                // Store the uploaded ID
                $uploadedFile = $request->file('id_file');
                $filename = time() . $uploadedFile->getClientOriginalName();
                Storage::disk('local')->putFileAs('applications/' . Auth::user()->id . '/', $uploadedFile, $filename);

                // Update the filename in the profiles table
                Profile::where('user_id', Auth::user()->id)->update(['id_path' => $filename]);
            }

            if($request->hasFile('profile_photo')) {
                // Store the uploaded ID
                $uploadedFile = $request->file('profile_photo');
                $filename = time() . $uploadedFile->getClientOriginalName();
                Storage::disk('local')->putFileAs('applications/' . Auth::user()->id . '/', $uploadedFile, $filename);

                // Update the filename in the profiles table
                Profile::where('user_id', Auth::user()->id)->update(['profile_photo' => $filename]);
            }

            return redirect()->action('HomeController@index')->with('status', 'Profile successfully updated');

        } catch(\Exception $e) {
            Log::debug($e->getMessage());
            return redirect()->back()->withErrors('Unable to create profile. Please try again!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return abort(404);
    }
}
