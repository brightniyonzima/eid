@extends('layouts.master')

@section('content')

    <h1>Commodity_categories <a href="{{ route('commodity_categories.create') }}" class="btn btn-primary pull-right btn-sm">Add New Commodity_categories</a></h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th><th>Category Name</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            {{-- */$x=0;/* --}}
            @foreach($commodity_categories as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $x }}</td>
                    <td><a href="{{ url('/commodity_categories', $item->id) }}">{{ $item->category_name }}</a></td>
                    <td>
                        <a href="{{ route('commodity_categories.edit', $item->id) }}">
                            <button type="submit" class="btn btn-primary btn-xs">Update</button>
                        </a> /
                        {!! Form::open([
                            'method'=>'DELETE',
                            'route' => ['commodity_categories.destroy', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs', 'name' => "delete_" . $item->id]) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $commodity_categories->render() !!} </div>
    </div>

@endsection
