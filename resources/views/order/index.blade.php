@extends('layouts.admin')

@section('content')
<div class="px-3">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h3>Upload Job Shadows</h3></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h3>Upload Spreadsheet</h3>
                    @if ($errors->has('jobs'))
                        <span role="alert" class="invalid-feedback d-block"><strong>{{ $errors->first('jobs') }}</strong></span>
                    @endif
                    {{ Form::open(['action' => 'JobController@store', 'files' => true]) }}
                        <div class="form-group">
                            {{ Form::file('jobs', ['class' => 'form-control-file']) }}
                        </div>
                        {{ Form::submit('Upload File', ['class' => 'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center my-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            Jobs
                        </div>
                        <div class="col-md-8 text-right">
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    {{ Form::label('from', 'Dates From') }}
                                    {{ Form::text('from', null, ['class' => 'form-control applications-date-ranges', 'placeholder' => '', 'id' => 'job_date_start']) }}
                                </div>
                                <div class="form-group col-md-2">
                                    {{ Form::label('to', 'Dates To') }}
                                    {{ Form::text('to', null, ['class' => 'form-control applications-date-ranges', 'placeholder' => '', 'id' => 'job_date_end']) }}
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-6 text-right">
                            <a href="{{ url('application/create') }}" class="btn btn-success">+ Create Application</a>
                        </div> --}}
                    </div>
                </div>

                <div class="card-body">

                    <div class="my-3">
                        <a href="{{ action('JobController@create') }}" class="btn btn-success">Add Job Shadow</a>
                        <a id="export_jobs" href="{{ action('JobController@export') }}" class="btn btn-primary">Export</a>
                    </div>

                    @if($jobs)
                    <table id="jobs-table" class="table my-3">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Company</th>
                                <th scope="col">Period</th>
                                <th scope="col">Career</th>
                                <th scope="col">Sector</th>
                                <th scope="col">Dates</th>
                                <th style="display: none;"></th>
                                <th scope="col">Location</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jobs as $key => $job)
                            <tr>
                                <th scope="row">{{ $job->id }}</th>
                                <td width="15%">{{ $job->company }}</td>
                                <td width="15%">
                                    @if($job->period != '')
                                        <div id="period_{{ $job->id }}" style="display:flex">
                                            <div style='margin-right:20px;'>{{ $job->period }} hours </div>
                                            <button class='btn btn-info fas fa-edit m-8' onclick="changePeriod{{ $job->id }}()" />
                                        </div>
                                        <div id="period_{{ $job->id }}_edit" style="display:none">
                                            {{ Form::open(['action' => ['JobController@period'], 'class' => 'period-edit-form']) }}
                                                {{ Form::number('period', $job->period, ['class' => 'form-control period-edit', 'placeholder' => '']) }}
                                                {{ Form::text('id', $job->id, ['class' => 'period-edit_id', 'placeholder' => '']) }}
                                                {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                                            {{ Form::close() }}
                                        </div>
                                        <script>
                                            function changePeriod{{ $job->id }}(){
                                                document.getElementById('period_{{ $job->id }}').style.display = 'none'
                                                document.getElementById('period_{{ $job->id }}_edit').style.display = 'flex'

                                            }
                                        </script>
                                     @endif
                                </td>
                                <td width="10%">{{ ($job->career) ? ucfirst($job->career->name) : '' }}</td>
                                <td width="10%">{{ implode(', ', array_column($job->sectors->toArray(), 'name')) }}</td>
                                <td class="dates">{{ $job->dates }}</td>
                                <td class="dates_hidden" style="display: none">{{ $job->dates }}</td>
                                <td width="20%">
                                    @if($job->location)
                                    {{ $job->location }}
                                    @endif
                                </td>
                                <td width="10%">
                                    {{ Form::open(['method' => 'DELETE', 'action' => ['JobController@destroy', $job->id]]) }}
                                        <a class="btn btn-info fas fa-edit" href="{{ action('JobController@edit', ['id' => $job->id]) }}"></a>
                                        <button class="btn btn-danger fas fa-trash-alt" type="submit"></button>
                                    {{ Form::close() }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
