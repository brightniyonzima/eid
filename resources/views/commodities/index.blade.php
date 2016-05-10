@extends('layouts.master')

@section('content')

    <h1>Commodities <a href="{{ route('commodities.create') }}" class="btn btn-primary pull-right btn-sm">Add New Commodities</a></h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th><th>Commodity Name</th><th>Category Id</th><th>Tests Per Unit</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            {{-- */$x=0;/* --}}
            @foreach($commodities as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $x }}</td>
                    <td><a href="{{ url('/commodities', $item->id) }}">{{ $item->commodity_name }}</a></td><td>{{ $item->category_id }}</td><td>{{ $item->tests_per_unit }}</td>
                    <td>
                        <a href="{{ route('commodities.edit', $item->id) }}">
                            <button type="submit" class="btn btn-primary btn-xs">Update</button>
                        </a> /
                        {!! Form::open([
                            'method'=>'DELETE',
                            'route' => ['commodities.destroy', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs', 'name' => "delete_" . $item->id]) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $commodities->render() !!} </div>
    </div>

@endsection
