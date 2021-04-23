@extends('layouts.app')

@section('content')

<div class="create-step-2 steps">
    <div class="card-header" style="color:white;"><div class="container"><h1>Step 2: Career</h1></div></div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            {{ Form::open(['action' => 'ApplicationController@storeStep2']) }}
            {{ Form::hidden('sector', $application->sector, ['id' => 'business-hidden']) }}
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
                        <hr>
                            <div class="form-group col-md-6">
                                <p>Select career</p>
                                {{ Form::label('career', 'Select career') }}
                                <select name="career" class="form-control" id="career">
                                    <option value="0" selected>Select a career</option>
                                    @foreach($careers as $career)
                                        @if(isset($career->jobs[0]) && $career->availableDates)
                                            <option value="{{ $career->id }}"
                                                    @if(isset($careerApplications[$career->id]))
                                                        {{ (count($careerApplications[$career->id]) >
                                                        $career->jobs[0]->max_applicants ? 'disabled data-first' : '') }}
                                                    @else
                                                        {{ $career->jobs[0]->max_applicants == 0 ? 'disabled data-second' : '' }}
                                                    @endif
                                                    >
                                                    {{ ucfirst($career->name) }}
                                            </option>
                                        @else
                                            <option value="{{ $career->id }}" data-test="third">{{ ucfirst($career->name) }}</option>
                                        @endif

                                    @endforeach
                                </select>
                                <div id="careerLoading" class="spinner-border text-danger spinner-border-sm" style="display:none;" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p id="duplicate-career-app" style="color:red;"></p>

                                <br />
                                <small id="career-desc" class="career-desc"></small>
                            </div>
                        <hr>
                    </div>
                </div>
            </div>
                <br />
                &nbsp;&nbsp;&nbsp;&nbsp; <div class="container form-container"><a class="btn btn-danger" href="{{ action('ApplicationController@createStep1') }}">Back to Step 1</a>
                {{ Form::submit('Continue to Step 3', ['id' => 'career-continue', 'class' => 'btn btn-primary', 'disabled' => true]) }}</div>
            {{ Form::close() }}
        </div>
    </div>
</div>

@endsection
