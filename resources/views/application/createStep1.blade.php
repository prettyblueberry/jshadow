@extends('layouts.app')

@section('content')

<div class="create-step-1 steps">
    <div class="card-header" style="color:white;"><div class="container"><h1>Step 1: Sector</h1></div></div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            {{ Form::open(['action' => 'ApplicationController@storeStep1']) }}
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
                                <p>Select sector</p>
                                {{ Form::label('sector', 'Select sector') }}
                                {{ Form::select('sector', $sectors, null, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-10">
                                <p>If you are not sure which sector your desired job shadow role falls into, please click on <a href="https://jobshadow.co.za/career-guidance-tool/">this link</a> to view our current sectors with their job listed below them.</p>
                            </div>
                            <div class="form-group col-md-6">
                                <p>If you don’t see the Career you are interested in, <a href="https://jobshadow.co.za/contact-us/">click here</a> and let us know!</p>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <div class="card-header" ><h2><strong>or</strong></h2></div>
                            </div>

                            <div class="form-group col-md-6">
                                <p>Enter company code (optional)</p>
                                {{ Form::label('company_code', 'Enter company code (optional)') }}
                                {{ Form::text('company_code', null, ['class' => 'form-control']) }}
                            </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="container form-container">{{ Form::submit('Continue to Step 2', ['class' => 'btn btn-primary']) }}</div>
            {{ Form::close() }}
        </div>
    </div>
</div>

@endsection