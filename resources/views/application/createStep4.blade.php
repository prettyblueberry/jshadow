@extends('layouts.app')

@section('content')

<div class="create-step-4 steps">
    <div class="card-header" style="color:white;"><div class="container"><h1>Step 4: Company</h1></div></div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            {{ Form::open(['action' => 'ApplicationController@storeStep4']) }}
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

                            <div class="form-group">
                                <p>Select available companies</p>
                                {{ Form::label('job_id', 'Select available companies') }}
                                {{ Form::select('job_id', $available_companies, null, ['class' => 'form-control']) }}
                            </div>
                    </div>
                </div>
            </div>
                <br />
            &nbsp;&nbsp;&nbsp; <div class="container form-container">&nbsp;<a class="btn btn-danger" href="{{ action('ApplicationController@createStep3') }}">Back to Step 3</a>
                {{ Form::submit('Continue to Step 5', ['class' => 'btn btn-primary']) }}</div>
            {{ Form::close() }}
        </div>
    </div>
</div>

@endsection