@extends('layouts.app')

@section('content')

<div class="application">
    <div class="card-header" style="color:white;"><div class="container form-container "><h1>Your Booking</h1></div></div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="background-color:#6fc7c0;color:white;">
                    <div class="container form-container ">

                        <div class="row">

                            <div class="col-md-6">
                                <div class="row my-3">
                                    <div class="col-md-12">
                                        <h3><strong>Job Shadow Description</strong></h3>
                                        <p><strong>{{ $application->job->sector }}: {{ $application->job->career }}</strong></p>
                                    </div>
                                </div>

                                <div class="row my-3">
                                    <div class="col-md-12">
                                        <h3><strong>Date &amp; Time of Job Shadow</strong></h3>
                                        <p>
                                            @if($application->job->days_per_job_shadow > 1)
                                                {{ \Carbon\Carbon::createFromFormat('m/d/Y', $application->dates)->format('l jS \\of F Y') }} - {{ \Carbon\Carbon::createFromFormat('m/d/Y', $application->dates)->addDays($application->job->days_per_job_shadow - 1)->format('l jS \\of F Y') }}
                                            @else
                                                {{ \Carbon\Carbon::createFromFormat('m/d/Y', $application->dates)->format('l jS \\of F Y') }}
                                            @endif
                                        </p>
                                        <p>{{ $application->job->arrival_time }} - {{ $application->job->collection_time }}</p>
                                        <p>The duration of this job shadow is {{ $application->job->days_per_job_shadow }} day/s.</p>

                                    </div>
                                </div>

                                <div class="row my-3">
                                    <div class="col-md-12">
                                        <h3><strong>Company</strong></h3>
                                        <p>{{ $application->job->company }}</p>
                                    </div>
                                </div>

                                <div class="row my-3">
                                    <div class="col-md-12">
                                        <h3><strong>Physical Address</strong></h3>
                                        <p>{{ $application->job->address }}</p>
                                    </div>
                                </div>

                                <div class="row my-3">
                                    <div class="col-md-12">
                                        <h3><strong>Host</strong></h3>
                                        <ul>
                                            <li>Name: {{ $application->job->job_mentor['name'] }}</li>
                                            <li>Email: <a href="mailto:{{ $application->job->job_mentor['email'] }}">{{ $application->job->job_mentor['email'] }}</a></li>
                                            <li>Telephone: {{ $application->job->job_mentor['telephone'] }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5" style="margin-left: 20px">
                                <div class="row">
                                    <hr>
                                    <div class="col-md-12">
                                        <br />
                                        <div class="form-group">
                                            <a class="btn btn-primary download" href="{{ action('ApplicationController@applicationIndemnity') }}">Your Indemnity</a>
                                            <p style="margin-top: 7px"><small><i>*pdf of indemnity form from company and any other legal requirements</i></small></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <p><a class="btn btn-primary download" href="{{ asset('attachments/JobShadowerExperienceForm.pdf') }}" target="_blank">Questionnaire</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="row justify-content-center pre-footer">
		<div class="card-header ">
			<div class="col-md-12 ">
				<h1>DON'T FORGET TO TAKE YOUR ID, SIGNED INDEMNITY FORM AND QUESTIONAIRE</h1>
			</div>
		</div>
	</div>
</div>

@endsection