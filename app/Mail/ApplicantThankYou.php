<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Application;

class ApplicantThankYou extends Mailable
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
        $email = $this->view('emails.application.complete.thankyou');
        $email->subject('Applicant Confirmed Booking');
        $email->attach(public_path('attachments/JobShadowerExperienceForm.pdf'));

        $locationProperty = $this->application->job->address ? $this->application->job->address : '';
        $titleProperty = $this->application->job->company ? 'Jobshadow at ' . $this->application->job->company : 'Jobshadow';
        $arrivalTimeProperty = $this->application->dates;
        $collectionTimeProperty = $arrivalTimeDescription = $collectionTimeDescription = $jobMentor = null;

        if ($this->application->job->arrival_time && $this->application->job->collection_time) {
            $arrivalTimeDescription = "Arrival time: " . $this->application->job->arrival_time;
            $collectionTimeDescription = "Collection time: " . $this->application->job->collection_time;
            try {
                $arrivalTimeProperty = Carbon::parse($this->application->dates .
                    str_replace('h', ':', $this->application->job->arrival_time));
                $collectionTimeProperty = Carbon::parse($this->application->dates .
                    str_replace('h', ':', $this->application->job->collection_time));
            } catch(\Exception $e) {

            }
        }

        if ($this->application->job->job_mentor["name"]) {
            $jobMentor = "Job mentor: " . $this->application->job->job_mentor["name"];
        }

        $filename = "calendar.ics";
        $vcalendar = [];
        $vcalendar[]  = "BEGIN:VCALENDAR";
        $vcalendar[] = "PRODID:-//JobShadow//Calendar 1.00//EN";
        $vcalendar[] = "VERSION:2.0";
        $vcalendar[] = "BEGIN:VEVENT";
        $vcalendar[] = "SUMMARY:" . $titleProperty;
        $vcalendar[] = "UID:" . date('Ymd').'T'.date('His').'-'.rand();
        $vcalendar[] = "DTSTART:".date('Ymd' . ($collectionTimeProperty ? '\THis' : ''), strtotime($arrivalTimeProperty));
        if ($collectionTimeProperty) {
            $vcalendar[] = "DTEND:".date('Ymd' . ($collectionTimeProperty ? '\THis' : ''), strtotime($collectionTimeProperty));
        }
        $vcalendar[] = "BEGIN:VALARM";
        $vcalendar[] = "ACTION:DISPLAY";
        $vcalendar[] = "DESCRIPTION:REMINDER";
        $vcalendar[] = "TRIGGER:-PT24H";
        $vcalendar[] = "END:VALARM";
        $vcalendar[] = "DESCRIPTION:" .
            ($arrivalTimeDescription ? $arrivalTimeDescription . "\\n" : "") .
            ($collectionTimeDescription ? $collectionTimeDescription . "\\n" : "") .
            ($jobMentor ? $jobMentor . "\\n" : "") .
            "Career: " . $this->application->job->career->name;
        $vcalendar[] = "LOCATION:" . $locationProperty;
        $vcalendar[] = "END:VEVENT";
        $vcalendar[] = "END:VCALENDAR";
        $vcalendar = implode("\r\n", $vcalendar);
        file_put_contents($filename, $vcalendar);
        $email->attach($filename, array('mime' => "text/calendar"));

        return $email;
    }
}
