<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/register');
});

Auth::routes();

Route::get('/book', 'HomeController@index')->name('book');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/jobs/export', 'JobController@export');
Route::resource('jobs', 'JobController');
Route::post('/job/period', 'JobController@period');
Route::resource('sectors', 'SectorController');

Route::any('/application/delete/{id}', 'ApplicationController@destroy');
Route::post('/application/export', 'ApplicationController@export')->name('application-export');
Route::get('/application/step/2/getDescription', 'ApplicationController@getDescription')->name('description');
Route::resource('application', 'ApplicationController');
Route::get('/application/step/1', 'ApplicationController@createStep1')->name('create-step-1');
Route::post('/application/step/1', 'ApplicationController@storeStep1')->name('store-step-1');
Route::get('/application/step/2', 'ApplicationController@createStep2')->name('create-step-2');
Route::post('/application/step/2', 'ApplicationController@storeStep2')->name('store-step-3');
Route::get('/application/step/3', 'ApplicationController@createStep3')->name('create-step-3');
Route::post('/application/step/3', 'ApplicationController@storeStep3')->name('store-step-3');
Route::get('/application/step/4', 'ApplicationController@createStep4')->name('create-step-4');
Route::post('/application/step/4', 'ApplicationController@storeStep4')->name('store-step-4');
Route::get('/application/step/5', 'ApplicationController@createStep5')->name('create-step-5');
Route::post('/application/step/5', 'ApplicationController@storeStep5')->name('store-step-5');
Route::match(['get', 'post'], '/application/documents/indemnity/{id}', 'ApplicationController@applicationIndemnity')->name('indemnity');


Route::post('/application/duplicate-application', 'ApplicationController@isDuplicateApplication');
Route::get('/application/step/5/getAvailableDates', 'ApplicationController@getAvailableDates')->name('dates');
Route::get('/application/step/complete', 'ApplicationController@applicationComplete')->name('complete');
Route::get('/application/step/indemnity/{id}', 'ApplicationController@createIndemnity')->name('create-indemnity');
Route::post('/application/step/indemnity', 'ApplicationController@storeIndemnity')->name('store-indemnity');

Route::get('/application/{id}', 'ApplicationController@show')->name('show-application');

Route::get('/payment/success', 'PaymentController@successPayment')->name('success-payment');
Route::get('/payment/cancel', 'PaymentController@cancelPayment')->name('cancel-payment');
Route::post('/payment/itn', 'PaymentController@itn')->name('itn');

Route::resource('profile', 'ProfileController');
Route::get('/profile/{user}/applications', 'ProfileController@applicationsByUser');
Route::get('/profile/{user}/applications/{id}', 'ProfileController@application');

Route::get('/voucher/search', 'VoucherController@search')->name('voucher.search');
Route::resource('voucher', 'VoucherController');

Route::get('/report', 'ReportController@index');
Route::post('/report/export', 'ReportController@export');

Route::get('/mail/{id}', 'MailController@sendMail')->name('mail.send');


// Remove for production
Route::get('mailable/applicant-thank-you', function () {
    if(Auth::guest())
    {
        return abort(404);
    }
    $application = App\Application::where('user_id', 2)->with(['user.profile', 'job', 'location'])->first();

    return new App\Mail\ApplicantThankYou($application);
});

Route::get('mailable/applicant-application-complete', function () {
    if(Auth::guest())
    {
        return abort(404);
    }
    $application = App\Application::where('user_id', 2)->with(['user.profile', 'job', 'location'])->first();

    return new App\Mail\ApplicantApplicationComplete($application);
});

Route::get('mailable/mentor-application-complete', function () {
    if(Auth::guest())
    {
        return abort(404);
    }
    $application = App\Application::where('user_id', 2)->with(['user.profile', 'job', 'location'])->first();

    return new App\Mail\MentorApplicationComplete($application);
});

Route::get('mailable/hr-application-complete', function () {
    if(Auth::guest())
    {
        return abort(404);
    }
    $application = App\Application::where('user_id', 2)->with(['user.profile', 'job', 'location'])->first();

    return new App\Mail\HrApplicationComplete($application);
});

Route::get('mailable/applicant-application-cancelled', function () {
    if(Auth::guest())
    {
        return abort(404);
    }
    $application = App\Application::where('user_id', 2)->with(['user.profile', 'job', 'location'])->first();

    return new App\Mail\ApplicantApplicationCancel($application);
});

Route::get('mailable/mentor-application-cancelled', function () {
    if(Auth::guest())
    {
        return abort(404);
    }
    $application = App\Application::where('user_id', 2)->with(['user.profile', 'job', 'location'])->first();

    return new App\Mail\MentorApplicationCancel($application);
});
