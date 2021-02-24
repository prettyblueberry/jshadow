@extends('layouts.app')

@section('content')

<div class="create-step-3 steps">
    <div class="card-header" style="color:white;"><div class="container"><h1>Step 3: Location</h1></div></div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            {{ Form::open(['action' => 'ApplicationController@storeStep3']) }}
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
                                <p>Select Location</p>
                                {{ Form::label('location', 'Select Location') }}
                                {{ Form::select('location', $available_locations, null, ['class' => 'form-control']) }}
                            </div>
                            <hr>
                    </div>
                </div>
            </div>
                <br />
            &nbsp;&nbsp;&nbsp;&nbsp; <div class="container form-container"><a class="btn btn-danger" href="{{ action('ApplicationController@createStep2') }}">Back to Step 2</a>
                {{ Form::submit('Continue to Step 4', ['class' => 'btn btn-primary']) }}</div>
            {{ Form::close() }}
        </div>
    </div>
</div>

@endsection