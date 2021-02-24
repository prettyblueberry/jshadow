<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

use App\Application;

class HrApplicationComplete extends Mailable
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
        $email = $this->view('emails.application.complete.hr');

        if(Storage::disk('local')->exists('/applications/' . $this->application->user_id . '/' . $this->application->indemnity_file)) {
            $email->attachFromStorage('/applications/' . $this->application->user_id . '/' . $this->application->indemnity_file);
        }
        
        if(Storage::disk('local')->exists('/applications/' . $this->application->user_id . '/' . $this->application->user->profile->id_path)) {
            $email->attachFromStorage('/applications/' . $this->application->user_id . '/' . $this->application->user->profile->id_path);
        }
        
        if(Storage::disk('local')->exists('/applications/' . $this->application->user_id . '/' . $this->application->user->profile->profile_photo)) {
            $email->attachFromStorage('/applications/' . $this->application->user_id . '/' . $this->application->user->profile->profile_photo);
        }
        
        $email->attach(public_path('attachments/JobShadowExperienceForm_HR.pdf'));

        return $email;
    }
}
