@extends('layouts.master')

@section('content')

    <h1>Receivestock</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Commodity Id</th><th>Qty Rcvd</th><th>Batch Number</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $receivestock->id }}</td> <td> {{ $receivestock->commodity_id }} </td><td> {{ $receivestock->qty_rcvd }} </td><td> {{ $receivestock->batch_number }} </td>
                </tr>
            </tbody>    
        </table>
    </div>

@endsection