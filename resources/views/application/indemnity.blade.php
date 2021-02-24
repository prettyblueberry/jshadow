@extends('layouts.app')

@section('content')

<div class="">
    <div class="card-header" style="color:white;">   <div class="container form-container"><h1>Submit Completed Indemnity Form</h1></div></div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="background-color:#6fc7c0;color:white;">
                    @if(!$application->indemnity_file)
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

                            {{ Form::open(['action' => 'ApplicationController@storeIndemnity', 'files' => true, 'id' => 'upload-indemnity-form']) }}
                                <div class="form-row">
                                    <hr>
                                    <div class="form-group col-md-4">
                                        {{ Form::hidden('id', $id) }}
                                        {{ Form::label('indemnity_file', 'Indemnity Form') }}
                                        {{ Form::file('indemnity_file', ['class' => 'form-control-file']) }}
                                        @if ($errors->has('indemnity_file'))
                                            <span role="alert" class="invalid-feedback d-block"><strong>{{ $errors->first('indemnity_file') }}</strong></span>
                                        @endif
                                    </div>
                                </div>
                                {{ Form::submit('Submit Indemnity', ['id' => 'submit-indemnity', 'class' => 'btn btn-primary']) }}
                                <hr>
                            {{ Form::close() }}
                        </div>
                    @else
                       <div class="container form-container">
                           <h2>You have successfully submitted your indemnity form for this application.</h2>
                       </div> 
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection