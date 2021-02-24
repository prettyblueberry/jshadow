<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Application;

use App\Mail\MentorApplicationComplete;
use App\Mail\ApplicantThankYou;
use App\Mail\HrApplicationComplete;
use App\Mail\ApplicantApplicationComplete;

class MailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function sendMail(Request $request, $id)
    {
        $application = Application::where('id', $id)->with(['user', 'user.profile', 'job', 'location'])->first();

        switch ($request->mail) {

            case 'applicant_application_complete':
                Mail::to($application->user->email)->cc('info@jobshadow.co.za')->bcc('mike@obriendesign.co.za')->send(new ApplicantApplicationComplete($application));
                break;

            case 'hr_application_complete':
                if($application->job->hr_contact['email']) {
                    Mail::to(explode(',', $application->job->hr_contact['email']))->cc('info@jobshadow.co.za')->bcc('mike@obriendesign.co.za')->send(new HrApplicationComplete($application));
                }
                break;

            case 'applicant_thank_you':
                Mail::to($application->user->email)->cc('info@jobshadow.co.za')->bcc('mike@obriendesign.co.za')->send(new ApplicantThankYou($application));
                break;

            case 'mentor_application_complete':
                if($application->job->job_mentor['email']) {
                    $mail = Mail::to(explode(',', $application->job->job_mentor['email']));
                    $emailCC = ['info@jobshadow.co.za'];

                    if($application->job->backup_job_mentor['email']) {
                        $emailCC = array_merge($emailCC, explode(',', $application->job->backup_job_mentor['email']));
                    }

                    $mail->cc($emailCC);
                    $mail->bcc('mike@obriendesign.co.za');
                    $mail->send(new MentorApplicationComplete($application));
                }
                break;

            default:
                # code...
                break;
        }

        return back()->with('success', 'Mail successfully sent!');
    }
}
