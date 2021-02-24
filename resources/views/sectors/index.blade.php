@extends('layouts.admin')

@section('content')
    <div class="px-3">

        <div class="row justify-content-center my-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Sectors</div>

                    <div class="card-body">

                        <div class="my-3">
                            <a href="{{ action('SectorController@create') }}" class="btn btn-success">Add a Sector</a>
                        </div>

                        @if($sectors)
                            <table id="jobs-table" class="table my-3">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Sector Name</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sectors as $key => $sector)
                                    <tr>
                                        <th scope="row">{{ ++$key }}</th>
                                        <td>{{ $sector->name }}</td>
                                        <td width="20%">
                                            {{ Form::open(['method' => 'DELETE', 'action' => ['SectorController@destroy', $sector->id]]) }}
                                            <a class="btn btn-info fas fa-edit" href="{{ action('SectorController@edit', ['id' => $sector->id]) }}"></a>
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
