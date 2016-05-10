@extends('layouts.master')

@section('content')
<!--use DataTable--> 
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">
  
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
<script type="text/javascript">
$(document).ready( function () {
    $('#tab_id').DataTable();
} );
</script>
<!--end of dataTable-->

    <h1>
        Stock Status
        <a href="{{ route('stock_adjustments.index') }}" class="btn btn-primary pull-right btn-sm">Stock Adjustments</a>
    </h1>      
<!--
<table class="table table-bordered table-striped table-hover">
    <tr>
        <td>Facility</td>
        <td>Commodity</td>
        <td>Current Stock</td>
        <td>AMC</td>
        <td>Adjustments</td>
        <td>Months of Stock on Hand</td>
        <td>Boxes To Send</td>
    </tr>
    <tr>
        <td>Obalanga HC II</td>
        <td>DBS KIts</td>
        <td>32</td>
        <td>20</td>
        <td style="color:blue;"><b>+20</b></td>
        <td>1</td>
        <td>2</td>
    </tr>
    <tr>
        <td>Kaberamaido HC IV</td>
        <td>DBS KIts</td>
        <td>57</td>
        <td>40</td>
        <td>0</td>
        <td>1</td>
        <td>3</td>
    </tr>
    <tr>
        <td>Mbarara Hospital</td>
        <td>DBS KIts</td>
        <td>250</td>
        <td>150</td>
        <td>0</td>
        <td>1</td>
        <td>10</td>
    </tr>
    <tr>
        <td>Ntungamo HC III</td>
        <td>DBS KIts</td>
        <td>64</td>
        <td>52</td>
        <td style="color:red;"><b>-10</b></td>
        <td>1</td>
        <td>5</td>
    </tr>
</table>
-->

<div id='d7' class="panel panel-default">
    <div class="panel-body">
        <table class='table table-striped table table-condensed' id='tab_id'>
          <thead>
            <tr>
              <td>Facility</td>
              <td>Commodity</td>
              <td>Current Stock</td>
              <td>AMC</td>
              <td>Adjustments</td>
              <td>Months of Stock on Hand</td>
              <td>Boxes To Send</td>
            </tr>
          </thead>
          <tbody>
            @foreach ($results AS $stock_status)      
            <tr>
                <?php

                  echo "<td>$stock_status->facility</td>";
                  echo "<td>$stock_status->commodity</td>";
                  echo "<td>$stock_status->total_stock_on_hand</td>";
                  echo "<td>$stock_status->average_monthly_consumption</td>";
                  echo "<td>$stock_status->adjustments</td>";

                  $months_of_stock_on_hand = StockManager::get_months_of_stock_on_hand($stock_status->facility,$stock_status->commodity);
                  //dd($months_of_stock_on_hand);
                  if($months_of_stock_on_hand < 0){
                    echo "<td>out of stock</td>";
                  }
                  else{
                    echo "<td>".$months_of_stock_on_hand."</td>";
                  }

                  //each box contains 50 items
                  $boxes_to_send=ceil(($stock_status->restock_quantity)/50);
                  echo "<td>".$boxes_to_send."</td>";
                  
                ?>
            </tr>        
            @endforeach             
            </tbody>
        </table>
    </div>
</div>

@stop