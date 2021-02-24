@extends('layouts.admin')

@section('content')

<div class="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="color:white;">
                    <div class="row">
                        <div class="col-md-6">
                            Vouchers
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ url('voucher/create') }}" class="btn btn-success">+ Create Voucher</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
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

                    @if($vouchers->count())
                    <table border="0" class="table">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Discount</th>
                            <th>Career</th>
                            <th>&nbsp;</th>
                        </tr>
                        @foreach($vouchers as $key => $voucher)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $voucher->name }}</td>
                            <td>{{ $voucher->code }}</td>
                            <td>{{ $voucher->discount }}%</td>
                            <td>{{ $voucher->career_id ? $voucher->career->name : 'All' }}</td>
                            <td class="text-center">
                                {{ Form::open(['action' => ['VoucherController@destroy', $voucher->id], 'method' => 'DELETE', 'class' => 'form-inline float-right']) }}
                                    <a href="{{ 'voucher/' . $voucher->id . '/edit' }}" class="btn btn-info">Edit</a>
                                    {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                                {{ Form::close() }}
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    @else
                    No vouchers submitted yet!
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection