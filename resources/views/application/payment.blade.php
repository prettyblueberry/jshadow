@extends('layouts.app')

@section('content')

<div class="">
    <div class="card-header" style="color:white;"><div class="container form-container "><h1>Almost done, just one more step...</h1></div></div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="background-color:#6fc7c0;color:white;">
                    <div class="container form-container ">
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row my-3">
                                    <div class="col-md-12">
                                        <h3><strong>Job Shadow Description</strong></h3>
                                        <p><strong>{{ ucfirst($application->job->career->name) }}</strong></p>
                                        <p>{{ $application->job->description }}</p>
                                    </div>
                                </div>

                                <div class="row my-3">
                                    <div class="col-md-12">
                                        <h3><strong>Date &amp; Time of Job Shadow</strong></h3>
                                        <p>
                                            @if($application->job->days_per_job_shadow > 1)
                                                {{ \Carbon\Carbon::createFromFormat('m/d/Y', $application->dates)->format('l jS \\of F Y') }} - {{ \Carbon\Carbon::createFromFormat('m/d/Y', $application->dates)->addDays($application->job->days_per_job_shadow - 1)->format('l jS \\of F Y') }}
                                            @else
                                                {{ \Carbon\Carbon::createFromFormat('m/d/Y', $application->dates)->format('l jS \\of F Y') }}
                                            @endif
                                        </p>
                                        <p>{{ $application->job->arrival_time }} - {{ $application->job->collection_time }}</p>
                                        <p>The duration of this job shadow is {{ $application->job->days_per_job_shadow }} day/s.</p>
                                        {{-- <a class="btn btn-primary" href="{{ action('ApplicationController@createStep5') }}">Change Date</a> --}}
                                    </div>
                                </div>

                                <div class="row my-3">
                                    <div class="col-md-12">
                                        <h3><strong>Company</strong></h3>
                                        <p>{{ $application->job->company }}</p>
                                    </div>
                                </div>

                                <div class="row my-3">
                                    <div class="col-md-12">
                                        <h3><strong>Physical Address</strong></h3>
                                        <p>{{ $application->job->address }}</p>
                                    </div>
                                </div>

                                <div class="row my-3">
                                    <div class="col-md-12">
                                        <h3><strong>Host</strong></h3>
                                        <ul>
                                            <li>Name: {{ $application->job->job_mentor['name'] }}</li>
                                            <li>Email: <a href="mailto:{{ $application->job->job_mentor['email'] }}">{{ $application->job->job_mentor['email'] }}</a></li>
                                            <li>Telephone: {{ $application->job->job_mentor['telephone'] }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5" style="margin-left: 20px">
                                <div class="row">
                                    <hr>
                                    <div class="col-md-12">
                                        <br />
                                        @if($application->job->indemnity_file)
                                            <div class="form-group">
                                                <div class="dropdown">
                                                    <a onclick="dropdownIndemnity()" class="btn btn-success download">Download Indemnity</a>
                                                    <div id="myDropdown" class="dropdown-content">
                                                        @foreach(json_decode($application->job->indemnity_file) as $index => $file)
                                                            <a class="dropped" href="{{ route('indemnity', Hashids::encode($application->id, $index)) }}">{{ $file }}</a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <p style="margin-top: 7px"><small><i>*pdf of indemnity form from company and any other legal requirements</i></small></p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <p><a class="btn btn-success upload" href="{{ route('create-indemnity', Hashids::encode($application->id)  ) }}">Upload Signed Indemnity</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function dropdownIndemnity() {
        document.getElementById("myDropdown").classList.toggle("show");
    }
    window.onclick = function(event) {
        if (!event.target.matches('.download')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>
@endsection