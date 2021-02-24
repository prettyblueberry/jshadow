@extends('layouts.app')

@section('content')

<div class="create-step-5">
    {{-- {{ dd($application->toArray()) }} --}}
    <div class="card-header" style="color:white;"><div class="container"><h1>Step 4: Select Date</h1></div></div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            {{ Form::open(['action' => 'ApplicationController@storeStep5']) }}
            <div class="card">
                <div class="card-body" style="background-color:#6fc7c0;color:white;">
                    <div class="container form-container">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('errors'))
                            <div class="alert alert-danger" role="alert">
                                @foreach(session('errors')->all() as $error)
                                * {{ $error }}<br />
                                @endforeach
                            </div>
                        @endif

                            <div class="form-group col-md-6">
                                <p>Select available date</p>
                                <small id="calendar-instructions"></small>
                                {{ Form::label('dates', 'Select available date') }}
                                {{ Form::hidden('dates', null, ['id' => 'applicant_date', 'class' => 'form-control']) }}
                                <div id="applicant_calendar"></div>
                                <p><small>Please note, this job shadow has a duration of {{ $application->job->days_per_job_shadow }} day/s</small></p>
                            </div>
                    </div>
                </div>
            </div>
                <br />
                &nbsp;&nbsp;&nbsp;&nbsp; <div class="container form-container"><a class="btn btn-danger" href="{{ action('ApplicationController@createStep3') }}">Back to Step 3</a>
                {{ Form::submit('Continue to Step 5', ['class' => 'btn btn-primary']) }}</div>
            {{ Form::close() }}
        </div>
    </div>
</div>

@endsection