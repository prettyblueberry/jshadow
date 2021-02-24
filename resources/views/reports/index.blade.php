@extends('layouts.app')

@section('content')

<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="color:white;">
                    <div class="row">
                        <div class="col-md-6">
                            Reports
                        </div>
                        <div class="col-md-6 text-right">
                            
                        </div>
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

                    <div class="row">
                        <div class="col-md-12">

                            {{ Form::open(['action' => 'ReportController@export']) }}
                                {{-- <div class="form-group">
                                    {{ Form::label('name', 'Name') }}
                                    {{ Form::text('name', null, ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('code', 'Voucher Code') }}
                                    {{ Form::text('code', null, ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('discount', 'Discount') }}
                                    {{ Form::number('discount', null, ['class' => 'form-control']) }}
                                </div> --}}
                                {{ Form::submit('Export', ['class' => 'btn btn-primary']) }}
                            {{ Form::close() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection