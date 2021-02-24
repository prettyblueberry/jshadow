@extends('layouts.admin')

@section('content')

<div class="">
    <div class="row justify-content-center">

        <div class="col-md-12 p-3">
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
        </div>

        @if($application->dates && $application->job)
            <div class="col-md-6">
        @else
            <div class="col-md-12">
        @endif
                <div class="card">
                    <div class="card-header" style="color:white;">Edit Application #{{ $application->id }}</div>

                    <div class="card-body p-3">
                        {{ Form::model($application, ['url' => 'application/' . $application->id, 'method' => 'PUT', 'files' => true]) }}
                            <div class="form-group">
                                {{ Form::label('indemnity_file', $application->indemnity_file) }}
                                {{ Form::file('indemnity_file', ['class' => 'fofrm-control-file']) }}
                                @if ($errors->has('indemnity_file'))
                                    <span role="alert" class="invalid-feedback d-block"><strong>{{ $errors->first('indemnity_file') }}</strong></span>
                                @endif
                            </div>
                            {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
                        {{ Form::close() }}

                        <br>

                        @if($application->indemnity_file)
                            {{ Form::model($application, ['action' => ['ApplicationController@applicationIndemnity', $application->id], 'method' => 'POST']) }}
                                {{ Form::hidden('application_id', $application->id) }}
                                {{ Form::submit('Download File', ['class' => 'btn btn-primary']) }}
                            {{ Form::close() }}
                        @endif
                    </div>
                </div>
            </div>
        @if($application->dates && $application->job)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" style="color:white;">Resend Mails</div>
                        <a href="{{ route('mail.send', ['id' => $application->id, 'mail' => 'applicant_application_complete']) }}" class="btn btn-primary">Applicant Application Complete</a><br>
                        <a href="{{ route('mail.send', ['id' => $application->id, 'mail' => 'hr_application_complete']) }}" class="btn btn-primary">HR Application Complete</a><br>
                        <a href="{{ route('mail.send', ['id' => $application->id, 'mail' => 'applicant_thank_you']) }}" class="btn btn-primary">Applicant Thank You</a><br>
                        <a href="{{ route('mail.send', ['id' => $application->id, 'mail' => 'mentor_application_complete']) }}" class="btn btn-primary">Mentor Application Complete</a>
                        <div class="p-3"></div>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection