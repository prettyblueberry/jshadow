@extends('layouts.app')

@section('content')

<div class="">
    <div class="card-header" style="color:white;">
        <div class="container">
            <h1>Your basket</h1>
            <small>
                Please make sure that your basket has all the correct details. The next step is your payment. Get your credit card details ready! Only once you have checked out will you receive the details of your job shadow booking.
            </small>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="background-color:#6fc7c0;color:white;">
                    <div class="container form-container ">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="row my-3">
                                    <div class="col-md-10">
                                        {{-- <h3><strong>Sector:</strong> {{ ucfirst($application->toArray()['sector']['name']) }}</h3> --}}
                                    </div><hr>
                                </div>

                                <div class="row my-3">
                                    <div class="col-md-10">
                                        <h3><strong>Career:</strong> {{ ucfirst($application->careerName) }}</h3>
                                    </div><hr>
                                </div>

                                <!--div class="row my-3">
                                    <div class="col-md-10">
                                        <h3><strong>Physical Address:</strong> {{ $application->job->address }}</h3>
                                    </div><hr>
                                </div-->

                                <div class="row my-3">
                                    <div class="col-md-10">
                                        {{-- {{ dd($application->toArray()) }} --}}
                                        <h3><strong>Location:</strong> {{ $application->job->location }}</h3>
                                    </div><hr>
                                </div>

                                <div class="row my-3">
                                    <div class="col-md-6">
                                        <h3>
                                            <strong>Date(s):</strong> 
                                            @if($application->job->days_per_job_shadow > 1)
                                                {{ \Carbon\Carbon::createFromFormat('m/d/Y', $application->dates)->format('l jS \\of F Y') }} - {{ \Carbon\Carbon::createFromFormat('m/d/Y', $application->dates)->addDays($application->job->days_per_job_shadow - 1)->format('l jS \\of F Y') }}
                                            @else
                                                {{ \Carbon\Carbon::createFromFormat('m/d/Y', $application->dates)->format('l jS \\of F Y') }}
                                            @endif
                                        </h3>
                                        <p>The duration of this job shadow is {{ $application->job->days_per_job_shadow }} day/s.</p>
                                    </div>
                                    <div class="col-md-6">
                                        <a class="btn btn-primary" href="{{ action('ApplicationController@createStep5') }}">Change Date</a>
                                    </div><hr>
                                </div>

                                <div class="row my-3">
                                    <div class="col-md-6">
                                        <h3>
                                            <strong>Time:</strong> 
                                            {{ $application->job->arrival_time }} - {{ $application->job->collection_time }}
                                        </h3>
                                    </div><hr>
                                </div>

                                <div class="row my-3">
                                    <div class="col-md-10">
                                        <h3><strong>Total Due:</strong> <span class="currencySymbol">R</span><span class="amount">{{ ($application->voucher_id) ? number_format($application->amount, 2) : number_format($application->job->amount, 2) }}</span></h3>
                                    </div>
                                </div>
                            </div><hr>
                        </div>
                        
                        @if(!$application->voucher_id)
                            <div class="row">
                                <div class="col-md-10">
                                    <div id="promoCodeInput" class="form-group">
                                        <input type="text" id="promoCode" class="form-control" style="width:25%;display:inline;" placeholder="Promo code" name="promo_code" />
                                        <button id="applyPromoCode">Apply Code</button>
                                        <div id="promoCodeLoading" class="spinner-border text-danger spinner-border-sm" style="display:none;" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                    <p id="promoCodeMessage"></p>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div id="payfast-form-wrapper" class="col-md-10">
                                {{-- <a class="btn btn-primary" href="{{ action('PaymentController@confirmPayment') }}">Proceed to Payment</a> --}}
                                {!! $payfast_form !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
