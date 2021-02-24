<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

use App\Application;
use Spatie\CalendarLinks\Link;
use Carbon\Carbon;

class ApplicantApplicationComplete extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The application instance.
     *
     * @var Application
     */
    public $application;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.application.complete.applicant');
        $email->subject('Applicant Indemnity Request for PENDING booking');
        if($this->application->job->indemnity_file) {
            $indemnityFiles = json_decode($this->application->job->indemnity_file);
            foreach($indemnityFiles as $file) {
                if(Storage::disk('local')->exists('/indemnity_files/' . $this->application->job->company . '/' . $file)) {
                    $email->attachFromStorage('/indemnity_files/' . $this->application->job->company . '/' . $file);
                }
            }
        }

        return $email;
    }
}
