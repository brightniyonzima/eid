@extends('layouts.master')

@section('content')

    <h1>Stock_requisition_header</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Facility </th><th>Requisition Date</th><th>Requisition Method</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $stock_requisition_header->id }}</td> <td> <!-- {{ $stock_requisition_header->facility_id }}-->
                     <?php
                                $facilityName="";
                                $facility=DB::table('facilities')
                                          ->select('facility')
                                          ->where('id',$stock_requisition_header->facility_id)
                                          ->get();  
                                 foreach ($facility as $facility_name ) {
                                     $facilityName=$facility_name->facility;
                                 }
                                echo "$facilityName";; 
                    ?>
                     </td><td> {{ $stock_requisition_header->requisition_date }} </td><td> {{ $stock_requisition_header->requisition_method }} </td>
                </tr>
            </tbody>    
        </table>
    </div>

@endsection