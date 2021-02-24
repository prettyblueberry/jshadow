@extends('layouts.admin')

@section('content')

<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="color:white;">
                    <div class="row">
                        <div class="col-md-4">
                            Applications
                        </div>
                        <div class="col-md-8 text-right">
                            {{ Form::open(['action' => ['ApplicationController@export'], 'files' => true, 'id' => 'export_apps']) }}
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        {{ Form::label('from', 'From') }}
                                        {{ Form::text('from', null, ['class' => 'form-control applications-date-ranges', 'placeholder' => '']) }}
                                    </div>
                                    <div class="form-group col-md-2">
                                        {{ Form::label('to', 'To') }}
                                        {{ Form::text('to', null, ['class' => 'form-control applications-date-ranges', 'placeholder' => '']) }}
                                    </div>
                                    <div class="form-group col-md-5">
                                        {{ Form::label('company', 'Select company') }}
                                        {{ Form::select('company',[null => 'All companies'] + $companies, null, ['class' => 'form-control']) }}
                                    </div>
                                    {{ Form::hidden('search', null, ['id' => 'export_search']) }}
                                    <div class="form-group col-md-3">
                                        {{ Form::submit('Export', ['class' => 'btn btn-success']) }}
                                    </div>
                                </div>
                            {{ Form::close() }}
                        </div>
                        {{-- <div class="col-md-6 text-right">
                            <a href="{{ url('application/create') }}" class="btn btn-success">+ Create Application</a>
                        </div> --}}
                    </div>
                </div>

                <div class="card-body">
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

                    @if($applications->count())
                    <table id="applications-table" border="0" class="table-responsive">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>ID No.</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Referral</th>
                                <th>School</th>
                                <th>Company</th>
                                <th>Company Code</th>
                                <th>Career</th>
                                <th>Sector</th>
                                <th>Location</th>
                                <th>Dates</th>
                                <th>Paid</th>
                                <th>Indemnity</th>
                                <th>Cancelled?</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        @foreach($applications as $application)
                        <tr>
                            <td>{{ $application->id }}</td>
                            <td>{{ $application->user->name }}</td>
                            <td>{{ $application->user->profile->id_no }}</td>
                            <td>{{ $application->user->email }}</td>
                            <td>{{ $application->user->profile->contact_no }}</td>
                            <td>{{ $application->user->where_hear }}</td>
                            <td>{{ $application->user->profile->school_of_attendance }}</td>
                            <td>{{ ($application->job) ? $application->job->company : '' }}</td>
                            <td>{{ ($application->job) ? $application->job->company_code : '' }}</td>
                            <td>{{ ($application->job) ? ucwords($application->job->career->name) : '' }}</td>
                            <td>{{ isset($application->sector) ? ucwords($application->sector->name) : '' }}</td>
                            <td>{{ isset($application->location->city) ? $application->location->city : '' }}</td>
                            <td>{{ $application->dates }}</td>
                            <td>{{ ($application->paid) ? 'Yes' : 'No' }}</td>
                            <td>{{ ($application->indemnity_file) ? 'Yes' : 'No' }}</td>
                            <td>{{ ($application->deleted_at) ? 'Yes' : 'No' }}</td>
                            {{-- <td class="text-center">
                                <a href="{{ action('ApplicationController@view', $application->id) }}" class="btn btn-warning">View</a>
                            </td> --}}
                            <td class="text-center">
                                <a href="{{ action('ApplicationController@edit', $application->id) }}" class="btn btn-info">Change</a>
                                {{-- <a href="{{ route('application.destroy') }}" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('application-destroy-form').submit();">Delete</a> --}}
                            </td>
                            {{-- <form id="application-destroy-form" action="{{ route('application.destroy') }}" method="POST" style="display: none;">
                                @csrf
                                {{ Form::hidden('id', $application->id) }}
                            </form> --}}
                            <td class="text-center">
                                {{ Form::open(['url' => 'application/' . $application->id, 'method' => 'DELETE']) }}
                                    {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                                {{ Form::close() }}
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    @else
                    No applications submitted yet!
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
