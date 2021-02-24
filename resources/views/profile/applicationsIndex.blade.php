@extends('layouts.app')

@section('content')

<div class="">
    <div class="content py-3">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h1 style="color:white;">Your Bookings</h1>
                </div>
                <div class="col-md-6 text-right">
                    <a class="btn btn-success" href="{{ action('ApplicationController@createStep1') }}">BOOK JOB SHADOW</a>
                </div>
            </div>
        </div>
    </div>
    @if($applications->count())
    <table border="0" class="table" style="color:white;">
        <tr>
            <th>Application ID</th>
            <th>Company</th>
            <th>Career</th>
            <th>Sector</th>
            <th>Dates</th>
            <th>&nbsp;</th>
        </tr>
        @foreach($applications as $application)
        <tr style="{{$application->deleted_at || $application->outdated ? 'color: #AAAAAA;' : ''}}">
            <td>{{ $application->id }}</td>
            <td>{{ ($application->job) ? $application->job->company : '' }}</td>
            <td>{{ ucfirst($application->careerName) }}</td>
            <td>{{ $application->sectorName }}</td>
            <td>{{ $application->dates }}</td>
            @if($application->deleted_at)
                <td>Cancelled</td>
            @elseif($application->outdated)
                <td>Outdated</td>
            @else
            <td class="">
                <a href="#" data-toggle="modal" data-target="#applicationModal" data-user-id="{{ Auth::user()->id }}" data-application-id="{{ $application->id }}" class="btn btn-primary">View</a>
                @if(!$application->indemnity_file)
                    <a href="{{ route('create-indemnity', Hashids::encode($application->id)) }}" class="btn btn-success">Upload Indemnity</a>
                    @if($application->job->indemnity_file)
                        <div class="dropdown">
                            <a onclick="dropdownIndemnity()" class="btn btn-success download">Download Indemnity</a>
                            <div id="myDropdown" class="dropdown-content">
                                @foreach(json_decode($application->job->indemnity_file) as $index => $file)
                                    <a class="dropped" href="{{ route('indemnity', Hashids::encode($application->id, $index)) }}">{{ $file }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </td>
            @endif
        </tr>
        @endforeach
    </table>
    @else
    No applications submitted yet!
    @endif
</div>

<!-- Application Modal -->
<div class="modal hide fade" id="applicationModal" tabindex="-1" role="dialog" aria-labelledby="applicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color:#e5c611;color:white;">
            <div class="modal-header">
                <h5 class="modal-title" id="applicationModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div class="modal-body-content">{{-- AJAX Magic Here --}}</div>
            </div>
            <div class="modal-footer">
                <button type="button" style="background-color: #7dc600;border-color:#7dc600;padding:7px 20px;border-radius:0;font-weight:bold;text-transform:uppercase;font-size:20px;"
                        class="btn btn-secondary" data-dismiss="modal">Close
                </button>
                <form id="delete-application-form" onsubmit="return confirm('Are you sure you want to cancel this application?');" action="" method="DELETE" style="display: none;">
                    @csrf
                    <button class="btn btn-secondary" type="submit">Cancel</button>
                </form>
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
