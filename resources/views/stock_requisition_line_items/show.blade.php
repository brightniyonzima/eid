@extends('layouts.master')

@section('content')

    <h1>Stock_requisition_line_item</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Requisition Header Id</th><th>Commodity Id</th><th>Quantity Requested</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $stock_requisition_line_item->id }}</td> <td> {{ $stock_requisition_line_item->requisition_header_id }} </td><td> {{ $stock_requisition_line_item->commodity_id }} </td><td> {{ $stock_requisition_line_item->quantity_requested }} </td>
                </tr>
            </tbody>    
        </table>
    </div>

@endsection