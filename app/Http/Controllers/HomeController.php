<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Application;
use Auth;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->role_id == 1) {
            return redirect('application');
        }

        $application = Application::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();

        // Redirect to step 1 if no application is found
        if(!$application) {
            return redirect('application/step/1');
        }

        $missing_indemnity_files = Application::where('user_id', Auth::user()->id)
            ->where('paid', 1)
            ->where('indemnity_file', NULL)
            ->first();

        return view('home',
        [
            'application'               => $application,
            'missing_indemnity_files'   => $missing_indemnity_files
        ]);
    }
}
