@extends('layouts.master')

@section('content')

    <h1>Stock_requisition_line_items 
        <a href="{{ route('stock_requisition_line_items.create') }}" 
            class="btn btn-primary pull-right btn-sm">Add New Stock_requisition_line_items</a>
    </h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Requisition Header Id</th>
                    <th>Commodity Id</th>
                    <th>Quantity Requested</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            {{-- */$x=0;/* --}}
            @foreach($stock_requisition_line_items as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $x }}</td>
                    <td><a href="{{ url('/stock_requisition_line_items', $item->id) }}"
                            >{{ $item->requisition_header_id }}</a>
                    </td>
                    <td>{{ $item->commodity_id }}</td>
                    <td>{{ $item->quantity_requested }}</td>
                    <td>
                        <a href="{{ route('stock_requisition_line_items.edit', $item->id) }}">
                            <button type="submit" class="btn btn-primary btn-xs">Update</button>
                        </a> /
                        {!! Form::open([
                            'method'=>'DELETE',
                            'route' => ['stock_requisition_line_items.destroy', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $stock_requisition_line_items->render() !!} </div>
    </div>

@endsection
