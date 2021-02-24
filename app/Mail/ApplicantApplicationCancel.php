<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Application;

class ApplicantApplicationCancel extends Mailable
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
        $email = $this->view('emails.application.cancelled.applicant');
        $email->subject('Job Shadow application cancelled');
        
        return $email;
    }
}
