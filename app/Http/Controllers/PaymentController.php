<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Billow\Contracts\PaymentProcessor;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicantApplicationComplete;
use App\Application;
use Auth;
use Log;

class PaymentController extends Controller
{
    public function itn(Request $request, PaymentProcessor $payfast)
    {
        try {
            // Retrieve the Order from persistance. Eloquent Example.
            $application = Application::where('payment_id', $request->get('m_payment_id'))->firstOrFail(); // Eloquent Example

            // Verify the payment status.
            $status = $payfast->verify($request, $application->amount, $application->m_payment_id)->status();

            // Handle the result of the transaction.
            switch( $status )
            {
                case 'COMPLETE': // Things went as planned, update your order status and notify the customer/admins.
                    $application->paid = 1;
                    $application->save();

                    break;
                case 'FAILED': // We've got problems, notify admin and contact Payfast Support.
                    Log::debug('FAILED');
                    $application->paid = 0;
                    break;
                case 'PENDING': // We've got problems, notify admin and contact Payfast Support.
                    Log::debug('PENDING');
                    $application->paid = 0;
                    break;
                default: // We've got problems, notify admin to check logs.
                    Log::debug('DEFAULT');
                    $application->paid = 0;
                    break;
            }
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
        }


    }

    public function successPayment()
    {
        if(Auth::guest())
        {
           return abort(404);
        }

        $application = Application::where('user_id', Auth::user()->id)->with(['user', 'user.profile', 'location'])
            ->with(array('job'=>function($query){
                $query->select('id', 'career_id', 'description', 'days_per_job_shadow', 'arrival_time',
                    'collection_time', 'company', 'address', 'job_mentor', 'indemnity_file');
            }))->orderBy('created_at', 'desc')->first();

        if(config('payfast.testing')) {
            $application->update(['paid' => 1]);
        }

        if(!$application->paid) {
           return abort(404);
        }

        // Send email to applicant
        Mail::to($application->user->email)->cc('info@jobshadow.co.za')->bcc('mike@obriendesign.co.za')->send(new ApplicantApplicationComplete($application));

        return view('application.payment', [
            'application'   => $application,
        ]);
    }

    public function cancelPayment()
    {
        return redirect()->action('ApplicationController@applicationComplete');
    }
}
