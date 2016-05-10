@extends('layouts.master')

@section('content')

    <h1>Stock_adjustment</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Facility Id</th><th>Commodity Id</th><th>Adjustment Type</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                   <td>{{ $stock_adjustment->id }}</td> <td> <!-- {{ $stock_adjustment->facility_id }} -->
                       <?php
                                $facilityName="";
                                $facility=DB::table('facilities')
                                          ->select('facility')
                                          ->where('id',$stock_adjustment->facility_id)
                                          ->get();  
                                 foreach ($facility as $facility_name ) {
                                     $facilityName=$facility_name->facility;
                                 }
                                echo "$facilityName";; 
                        ?>
                   </td>
                   <td><!-- {{ $stock_adjustment->commodity_id }} -->
                    <?php
                                $commodityName="";
                                $commodity=DB::table('commodities')
                                          ->select('commodity')
                                          ->where('id',$stock_adjustment->commodity_id)
                                          ->get();  
                                 foreach ($commodity as $commodity_name ) {
                                     $commodityName=$commodity_name->commodity;
                                 }
                                echo "$commodityName";; 
                    ?>
                   </td>
                   <td> {{ $stock_adjustment->adjustment_type }} </td>
                </tr>
            </tbody>    
        </table>
    </div>

@endsection