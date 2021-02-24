@extends('layouts.admin')

@section('content')

<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-5" style="color:white;">Create Voucher</div>

                <div class="card-body p-5">
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

                    {{ Form::open(['action' => 'VoucherController@store']) }}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('name', 'Name') }}
                                    {{ Form::text('name', null, ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('code', 'Voucher Code') }}
                                    {{ Form::text('code', null, ['class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('discount', 'Discount') }}
                                    {{ Form::number('discount', null, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('available_from', 'Available from') }}
                                    {{ Form::text('available_from', null, ['class' => 'form-control voucher-date']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('available_until', 'Available until') }}
                                    {{ Form::text('available_until', null, ['class' => 'form-control voucher-date']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('limit', 'Limit per user') }}
                                    {{ Form::number('limit', null, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('career', 'Career') }}
                                    <select class="form-control" name="career">
                                        <option value="all">All</option>
                                        @foreach($careers as $career)
                                            <option value="{{ $career->id }}">{{ $career->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="col-md-4">
                                    {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
                                </div>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<style>
    select option {
        background: #6fc7c0;
        color: #fff;
    }
</style>
