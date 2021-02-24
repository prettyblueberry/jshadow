<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

use App\Application;
use Carbon\Carbon;
use App\Mail\ApplicantEvaluation;
use App\Mail\MentorEvaluation;
use Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function() {
            // Get all applications that happened yesterday
            $today = Carbon::now();
            $two_days_ago = Carbon::now()->subdays(2)->format('m/d/Y');

            $applications = Application::where('paid', 1)
                            // ->where('dates', '<=', $two_days_ago)
                            ->where('dates', $two_days_ago)
                            // ->where('created_at', '>', '2020-08-01 00:00:01')
                            ->with(['job', 'user'])
                            ->get();

            foreach ($applications as $key => $application) {
                echo $application->dates . "\n";
                echo $application->created_at . "\n";
                if($application->user->email) {
                    $recipients = explode(',', $application->user->email);
                    foreach($recipients as $recipient) {
                        if(!empty(trim($recipient))) {
                            echo $recipient . "\n";
                            $applicant_mail = Mail::to($recipient);
                            $applicant_mail->cc('info@jobshadow.co.za');
                            try {
                                $applicant_mail->send(new ApplicantEvaluation($application));
                            } catch(\Exception $e) {
                                \Log::info($e);
                            }
                        }
                    }
                }

                if($application->job->job_mentor['email']) {
                    $recipients = explode(',', $application->job->job_mentor['email']);
                    foreach($recipients as $recipient) {
                        if(!empty(trim($recipient))) {
                            echo $recipient . "\n";
                            $mail = Mail::to($recipient);
                            $mail->cc('info@jobshadow.co.za');

                            if($application->job->job_mentor['email']) {
                                $mentors = explode(',', $application->job->backup_job_mentor['email']);
                                foreach($mentors as $mentor) {
                                    if(!empty(trim($mentor))) {
                                        echo $mentor . "\n";
                                        $mail->cc($mentor);
                                    }
                                }
                            }

                            try {
                                $mail->send(new MentorEvaluation($application));
                            } catch(\Exception $e) {
                                \Log::info($e);
                            }
                        }
                    }
                }

                // We do this because mailtrap has a limit of 2 mails per second
                if (trim(config('mail.host')) == 'smtp.mailtrap.io') {
                    sleep(3);
                }
            }
        })->dailyAt('09:00');

        $schedule->call(function() {
            // Get all applications that happened yesterday
            $today = Carbon::now();
            $seven_days_ago = Carbon::now()->subdays(7)->format('m/d/Y');

            $applications = Application::where('paid', 1)
                            // ->where('dates', '<=', $seven_days_ago)
                            ->where('dates', $seven_days_ago)
                            // ->where('created_at', '>', '2020-08-01 00:00:01')
                            ->with(['job', 'user'])
                            ->get();

            foreach ($applications as $key => $application) {
                echo $application->dates . "\n";
                echo $application->created_at . "\n";
                if($application->user->email) {
                    $recipients = explode(',', $application->user->email);
                    foreach($recipients as $recipient) {
                        if(!empty(trim($recipient))) {
                            echo $recipient . "\n";
                            $applicant_mail = Mail::to($recipient);
                            $applicant_mail->cc('info@jobshadow.co.za');
                            try {
                                $applicant_mail->send(new ApplicantEvaluation($application));
                            } catch(\Exception $e) {
                                \Log::info($e);
                            }
                        }
                    }
                }

                if($application->job->job_mentor['email']) {
                    $recipients = explode(',', $application->job->job_mentor['email']);
                    foreach($recipients as $recipient) {
                        if(!empty(trim($recipient))) {
                            echo $recipient . "\n";
                            $mail = Mail::to($recipient);
                            $mail->cc('info@jobshadow.co.za');

                            if($application->job->job_mentor['email']) {
                                $mentors = explode(',', $application->job->backup_job_mentor['email']);
                                foreach($mentors as $mentor) {
                                    if(!empty(trim($mentor))) {
                                        echo $mentor . "\n";
                                        $mail->cc($mentor);
                                    }
                                }
                            }

                            try {
                                $mail->send(new MentorEvaluation($application));
                            } catch(\Exception $e) {
                                \Log::info($e);
                            }
                        }
                    }
                }

                // We do this because mailtrap has a limit of 2 mails per second
                if (trim(config('mail.host')) == 'smtp.mailtrap.io') {
                    sleep(3);
                }
            }
        })->dailyAt('09:00');

        $schedule->command('telescope:prune')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

