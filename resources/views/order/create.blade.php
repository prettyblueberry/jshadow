@extends('layouts.admin')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">New Job Shadow</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('errors'))
                        <div class="alert alert-danger" role="alert">
                            @foreach(session('errors')->all() as $error)
                                * {{ $error }}<br />
                            @endforeach
                        </div>
                    @endif
                    <h3></h3>
                    {{ Form::open(['action' => ['JobController@store'], 'files' => true]) }}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                {{ Form::label('sector', 'Sector (comma-separated list)') }}
                                {{ Form::text('sector', null, ['class' => 'form-control', 'placeholder' => 'Sector']) }}
                                {{-- {{ Form::select('sector', $sectors, null, ['class' => 'form-control', 'placeholder' => 'Select a sector']) }} --}}
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('career', 'Career') }}
                                {{ Form::text('career', null, ['class' => 'form-control', 'placeholder' => 'Career']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('description', 'Description') }}
                            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                {{ Form::label('company', 'Company') }}
                                {{ Form::text('company', null, ['class' => 'form-control', 'placeholder' => 'Company']) }}
                            </div>
                            <div class="form-group col-md-2">
                                {{ Form::label('company_code', 'Company Code') }}
                                {{ Form::text('company_code', null, ['class' => 'form-control', 'placeholder' => 'Company Code']) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('location', 'Location') }}
                                {{ Form::text('location', null, ['class' => 'form-control', 'placeholder' => 'Location']) }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-8">
                                {{ Form::label('address', 'Address') }}
                                {{ Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Address']) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('website', 'Website') }}
                                {{ Form::text('website', null, ['class' => 'form-control', 'placeholder' => 'Website']) }}
                            </div>
                        </div>

                        <h3>Contacts</h3>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                {{ Form::label('', 'Job Mentor') }}
                                {{ Form::text('job_mentor[name]', null, ['class' => 'form-control my-3', 'placeholder' => 'Name']) }}
                                {{ Form::text('job_mentor[email]', null, ['class' => 'form-control my-3', 'placeholder' => 'Email']) }}
                                {{ Form::text('job_mentor[telephone]', null, ['class' => 'form-control my-3', 'placeholder' => 'Telephone']) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('', 'Backup Job Mentor') }}
                                {{ Form::text('backup_job_mentor[name]', null, ['class' => 'form-control my-3', 'placeholder' => 'Name']) }}
                                {{ Form::text('backup_job_mentor[email]', null, ['class' => 'form-control my-3', 'placeholder' => 'Email']) }}
                                {{ Form::text('backup_job_mentor[telephone]', null, ['class' => 'form-control my-3', 'placeholder' => 'Telephone']) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('', 'HR Contact') }}
                                {{ Form::text('hr_contact[name]', null, ['class' => 'form-control my-3', 'placeholder' => 'Name']) }}
                                {{ Form::text('hr_contact[email]', null, ['class' => 'form-control my-3', 'placeholder' => 'Email']) }}
                                {{ Form::text('hr_contact[telephone]', null, ['class' => 'form-control my-3', 'placeholder' => 'Telephone']) }}
                            </div>
                        </div>

                        <h3>Job Shadow Details</h3>

                        <div class="form-row">
                            <div class="form-group col-md-2">
                                {{ Form::label('', 'Calendar Period 1') }}
                                {{ Form::text('availability[period_1]', null, ['id' => 'period-calendar-1', 'class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                            <div class="form-group col-md-2">
                                {{ Form::label('', 'Calendar Period 2') }}
                                {{ Form::text('availability[period_2]', null, ['id' => 'period-calendar-2', 'class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                            <div class="form-group col-md-2">
                                {{ Form::label('', 'Calendar Period 3') }}
                                {{ Form::text('availability[period_3]', null, ['id' => 'period-calendar-3', 'class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                            <div class="form-group col-md-2">
                                {{ Form::label('', 'Calendar Period 4') }}
                                {{ Form::text('availability[period_4]', null, ['id' => 'period-calendar-4', 'class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                            <div class="form-group col-md-2">
                                {{ Form::label('', 'Calendar Period 5') }}
                                {{ Form::text('availability[period_5]', null, ['id' => 'period-calendar-5', 'class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                {{ Form::label('max_applicants', 'Max Applicants') }}
                                {{ Form::text('max_applicants', null, ['class' => 'form-control', 'placeholder' => 'Max Applicants']) }}
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('', 'Days per job shadow') }}
                                {{ Form::number('days_per_job_shadow', 1, ['class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('', 'Arrival Time') }}
                                {{ Form::text('arrival_time', null, ['class' => 'form-control', 'placeholder' => 'Arrival Time']) }}
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('', 'Collection Time') }}
                                {{ Form::text('collection_time', null, ['class' => 'form-control', 'placeholder' => 'Collection Time']) }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                {{ Form::label('', 'Total Days') }}
                                {{ Form::text('total_days', null, ['class' => 'form-control', 'placeholder' => 'Total Days']) }}
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('amount', 'Amount') }}
                                {{ Form::text('amount', null, ['class' => 'form-control', 'placeholder' => 'Amount']) }}
                                @if ($errors->has('amount'))
                                    <span role="alert" class="invalid-feedback d-block"><strong>{{ $errors->first('amount') }}</strong></span>
                                @endif
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                {{ Form::label('indemnity_file', 'Indemnity Form') }}
                                {{ Form::file('indemnity_file[]', ['class' => 'form-control-file', 'multiple' => 'multiple']) }}
                                @if ($errors->has('indemnity_file'))
                                    <span role="alert" class="invalid-feedback d-block"><strong>{{ $errors->first('indemnity_file') }}</strong></span>
                                @endif
                            </div>
                        </div>

                        {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
