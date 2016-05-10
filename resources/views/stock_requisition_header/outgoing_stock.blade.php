@extends('layouts.master')

@section('content')
    <h1> Authorize Stock Release </h1>
    <hr/>

<div class="container">

<form class="form-horizontal" role="form">

    <table class="table table-bordered">
        <tr>
            <th>&nbsp;&nbsp;#&nbsp;</th>
            <th>Commodity</th>
            <th>Category</th>
            <th>Qty Approved</th>
        </tr>
        <tr>
            <td>1.</td>
            <td>Test Tubes</td>
            <td>Lab Materials</td>
            <td>50</td>
        </tr>
        <tr>
            <td>2.</td>
            <td>A3 Envelopes</td>
            <td>Stationery</td>
            <td>100</td>
        </tr>
        <tr>
            <td colspan="9">
                <div style="text-align:center">
                        Stock Released by: Jonathan Domingues
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        Date Released: {{ date('Y-M-d') }}
                </div>
                <a href="#" class="btn btn-success" style="float:right">AUTHORIZE STOCK DISPATCH</a>
            </td>
        </tr>
    </table>

</div>
</form>



@endsection