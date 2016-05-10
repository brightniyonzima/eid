@extends('layouts.master')

@section('content')
    <h1> New Stock Requisition</h1>
    <hr/>


<div class="container">

<form class="form-horizontal" role="form">
    <div class="form-group">
        <label for="input1" class="col-lg-2 control-label">Requesting Facility:</label>
        <div class="col-lg-3">
            <input type="text" class="form-control" id="input1" placeholder="Input1">
        </div>
    </div>

    <div class="form-group">
        <label for="input1" class="col-lg-2 control-label">Requisition Method:</label>
        <div class="col-lg-3">
            <input type="text" class="form-control" id="input1" placeholder="Input1">
        </div>
        <div class="col-lg-2">
            <input type="text" class="form-control" id="input2" placeholder="Input2">
        </div>
    </div>


    <div class="form-group">
        <label for="input1" class="col-lg-2 control-label">Requester's Name:</label>
        <div class="col-lg-3">
            <input type="text" class="form-control" id="input1" placeholder="Input3">
        </div>
        <div class="col-lg-3">
            <input type="password" class="form-control" id="input3" placeholder="Input3">
        </div>
    </div>  

    <hr/>

    <table class="table table-bordered">
        <tr>
            <th>&nbsp;&nbsp;#&nbsp;</th>
            <th>Commodity</th>
            <th>Qty Requested</th>
            <th>+</th>
            <th>-</th>
        </tr>
        <tr>
            <td>1.</td>
            <td>Test Tubes</td>
            <td class="col-lg-3">
                    <input type="text" class="form-control" id="input2" placeholder="Input2">
            </td>
            <td>
                <a href="#" class="btn btn-danger" style="float:right"> Delete [-]</a>
            </td>
            <td>
                <a href="#" class="btn btn-primary" style="float:right">Add Row [+]</a>
            </td>
        </tr>
        <tr>
            <td colspan="5">
                <a href="#" class="btn btn-success" style="float:right">SAVE THIS REQUISITION</a>
            </td>
        </tr>
    </table>

</div>
</form>



@endsection