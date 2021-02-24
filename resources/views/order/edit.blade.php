@extends('layouts.admin')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Edit Job Shadow #{{ $job->id }}</div>

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
                    {{ Form::open(['method' => 'PUT', 'action' => ['JobController@update', $job->id], 'files' => true]) }}

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                {{ Form::label('sector', 'Sector (comma-separated list)') }}
                                {{ Form::text('sector', implode(', ', array_column($job->sectors->toArray(), 'name')), ['class' => 'form-control', 'placeholder' => 'Career']) }}
                                {{-- {{ Form::select('sector', $sectors, $job->sector, ['class' => 'form-control', 'placeholder' => 'Select a sector']) }} --}}
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('career', 'Career') }}
                                {{ Form::text('career', ucfirst(($job->career) ? $job->career->name : ''), ['class' => 'form-control', 'placeholder' => 'Career']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('description', 'Description') }}
                            {{ Form::textarea('description', $job->description, ['class' => 'form-control', 'placeholder' => 'Description']) }}
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                {{ Form::label('company', 'Company') }}
                                {{ Form::text('company', $job->company, ['class' => 'form-control', 'placeholder' => 'Company']) }}
                            </div>
                            <div class="form-group col-md-2">
                                {{ Form::label('company_code', 'Company Code') }}
                                {{ Form::text('company_code', $job->company_code, ['class' => 'form-control', 'placeholder' => 'Company Code']) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('location_id', 'Location') }}
                                {{ Form::text('location', $job->location, ['class' => 'form-control', 'placeholder' => 'Location']) }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-8">
                                {{ Form::label('address', 'Address') }}
                                {{ Form::text('address', $job->address, ['class' => 'form-control', 'placeholder' => 'Address']) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('website', 'Website') }}
                                {{ Form::text('website', $job->website, ['class' => 'form-control', 'placeholder' => 'Website']) }}
                            </div>
                        </div>

                        <h3>Contacts</h3>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                {{ Form::label('', 'Job Mentor') }}
                                {{ Form::text('job_mentor[name]', $job->job_mentor['name'], ['class' => 'form-control my-3', 'placeholder' => 'Name']) }}
                                {{ Form::text('job_mentor[email]', $job->job_mentor['email'], ['class' => 'form-control my-3', 'placeholder' => 'Email']) }}
                                {{ Form::text('job_mentor[telephone]', $job->job_mentor['telephone'], ['class' => 'form-control my-3', 'placeholder' => 'Telephone']) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('', 'Backup Job Mentor') }}
                                {{ Form::text('backup_job_mentor[name]', $job->backup_job_mentor['name'], ['class' => 'form-control my-3', 'placeholder' => 'Name']) }}
                                {{ Form::text('backup_job_mentor[email]', $job->backup_job_mentor['email'], ['class' => 'form-control my-3', 'placeholder' => 'Email']) }}
                                {{ Form::text('backup_job_mentor[telephone]', $job->backup_job_mentor['telephone'], ['class' => 'form-control my-3', 'placeholder' => 'Telephone']) }}
                            </div>
                            <div class="form-group col-md-4">
                                {{ Form::label('', 'HR Contact') }}
                                {{ Form::text('hr_contact[name]', $job->hr_contact['name'], ['class' => 'form-control my-3', 'placeholder' => 'Name']) }}
                                {{ Form::text('hr_contact[email]', $job->hr_contact['email'], ['class' => 'form-control my-3', 'placeholder' => 'Email']) }}
                                {{ Form::text('hr_contact[telephone]', $job->hr_contact['telephone'], ['class' => 'form-control my-3', 'placeholder' => 'Telephone']) }}
                            </div>
                        </div>

                        <h3>Job Shadow Details</h3>

                        <div class="form-row">
                            <div class="form-group col-md-2">
                                {{ Form::label('', 'Calendar Period 1') }}
                                {{ Form::text('availability[period_1]', $job->availability['period_1'], ['id' => 'period-calendar-1', 'class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                            <div class="form-group col-md-2">
                                {{ Form::label('', 'Calendar Period 2') }}
                                {{ Form::text('availability[period_2]', $job->availability['period_2'], ['id' => 'period-calendar-2', 'class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                            <div class="form-group col-md-2">
                                {{ Form::label('', 'Calendar Period 3') }}
                                {{ Form::text('availability[period_3]', $job->availability['period_3'], ['id' => 'period-calendar-3', 'class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                            <div class="form-group col-md-2">
                                {{ Form::label('', 'Calendar Period 4') }}
                                {{ Form::text('availability[period_4]', $job->availability['period_4'], ['id' => 'period-calendar-4', 'class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                            <div class="form-group col-md-2">
                                {{ Form::label('', 'Calendar Period 5') }}
                                {{ Form::text('availability[period_5]', $job->availability['period_5'], ['id' => 'period-calendar-5', 'class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                {{ Form::label('max_applicants', 'Max Applicants') }}
                                {{ Form::text('max_applicants', $job->max_applicants, ['class' => 'form-control', 'placeholder' => 'Max Applicants']) }}
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('', 'Days per job shadow') }}
                                {{ Form::number('days_per_job_shadow', $job->days_per_job_shadow, ['class' => 'form-control', 'placeholder' => '']) }}
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('', 'Arrival Time') }}
                                {{ Form::text('arrival_time', $job->arrival_time, ['class' => 'form-control', 'placeholder' => 'Arrival Time']) }}
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('', 'Collection Time') }}
                                {{ Form::text('collection_time', $job->collection_time, ['class' => 'form-control', 'placeholder' => 'Collection Time']) }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                {{ Form::label('', 'Total Days') }}
                                {{ Form::text('total_days', $job->total_days, ['class' => 'form-control', 'placeholder' => 'Total Days']) }}
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('amount', 'Amount') }}
                                {{ Form::text('amount', $job->amount, ['class' => 'form-control', 'placeholder' => 'Amount']) }}
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
                                @if($job->indemnity_file)
                                <div class="alert alert-dark" role="alert">
                                    {{ $job->indemnity_file }}
                                </div>
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
