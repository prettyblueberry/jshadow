@extends('layouts.app')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                {{-- <div class="card-header">Dashboard</div> --}}

                <div class="card-body" style="background-color: #e01b42; color:white;">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @applicant
                    @if($application)
                        @if(\Route::current()->getName() == 'home' && $application->indemnity_file)
                            <div class="text-center">
                                <h2 style="background: #69d2cb; margin-bottom: 0; text-align: center; font-family: 'Cinnabar Brush', sans-serif; font-weight: 400; font-size: 3vw;">THANK YOU FOR USING JOB SHADOW</h2>
                                <h3 class="yellow_bg"><strong>THE RELEVANT DETAILS ARE ON THEIR WAY!</strong></h3>
                                <div class="green_bg">
                                    <h3><strong>TO VIEW CONFIRMED BOOKING</strong></h3>
                                    <p>
                                        <a class="btn btn-success" href="{{ action('ProfileController@applicationsByUser', Auth::user()->id) }}">View BOOKINGS</a>&nbsp;&nbsp;
                                        <br><br>
                                        <a class="btn btn-secondary" href="{{ action('ApplicationController@createStep1') }}">BOOK ANOTHER JOB SHADOW</a>
                                    </p>
                                </div>

                            </div>
                        @else 
                            <div class="text-center">
                                <img src="{{ asset('img/home.png') }}" alt="" data-ww="1263px" data-hh="338px" width="1263" height="338" data-no-retina="" style="width: 832.133px; height: 222.693px; transition: none 0s ease 0s; text-align: inherit; line-height: 0px; border-width: 0px; margin: 0px; padding: 0px; letter-spacing: 0px; font-weight: 300; font-size: 7px;"><br /><br />
                                <a class="btn btn-success" href="{{ action('ApplicationController@createStep1') }}">BOOK JOB SHADOW</a>
                            </div>
                        @endif
                    @else
                        <div class="text-center">
                            <img src="{{ asset('img/home.png') }}" alt="" data-ww="1263px" data-hh="338px" width="1263" height="338" data-no-retina="" style="width: 832.133px; height: 222.693px; transition: none 0s ease 0s; text-align: inherit; line-height: 0px; border-width: 0px; margin: 0px; padding: 0px; letter-spacing: 0px; font-weight: 300; font-size: 7px;"><br /><br />
                            <a class="btn btn-success" href="{{ action('ApplicationController@createStep1') }}">BOOK JOB SHADOW</a>
                        </div>
                    @endif

                    @if($missing_indemnity_files)
                        {{-- Modal for uncompleted indemnities --}}
                        <div class="modal hide fade" id="indemnityReminderModal" tabindex="-1" role="dialog" aria-labelledby="indemnityReminderModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content" style="background-color:#e5c611;color:white;">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="indemnityReminderModalLabel"></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="modal-body-content">
                                            <h5>Welcome back {{ Auth::user()->name }}!</h5>
                                            <br>
                                            <p>It looks like you have incomplete bookings that are missing indemnity forms. Please <a href="{{ action('ProfileController@applicationsByUser', Auth::user()->id) }}">click here</a> to go to your bookings page and upload your missing indemnity forms.</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ action('ProfileController@applicationsByUser', Auth::user()->id) }}" style="background-color: #7dc600;border-color:#7dc600;" class="btn btn-secondary">Go to Bookings Page</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @endapplicant

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
