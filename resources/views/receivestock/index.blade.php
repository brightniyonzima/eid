@extends('layouts.master')

@section('content')

    <h1> 
        Incoming Deliveries to CPHL
        <a href="{{ route('receivestock.create') }}" class="btn btn-primary pull-right btn-sm">Receive Stock</a>
    </h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Commodity ID</th>
                    <th>Batch Number</th>
                    <th>Quantity</th>
                    <th>Arrival Date</th>
                    <th>Expiry Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            {{-- */$x=0;/* --}}
            @foreach($receivestocks as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $x }}</td>
                    <td><a href="{{ url('/receivestock', $item->id) }}">{{ $item->commodity_id }}</a></td>
                    <td>{{ $item->batch_number }}</td>
                    <td>{{ $item->qty_rcvd }}</td>
                    <td>{{ $item->arrival_date }}</td>
                    <td>{{ $item->expiry_date }}</td>
                    <td>
                        <a href="{{ route('receivestock.edit', $item->id) }}">
                            <button type="submit" class="btn btn-primary btn-xs">Update</button>
                        </a> /
                        {!! Form::open([
                            'method'=>'DELETE',
                            'route' => ['receivestock.destroy', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $receivestocks->render() !!} </div>
    </div>

@endsection
