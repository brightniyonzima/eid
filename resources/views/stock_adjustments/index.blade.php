@extends('layouts.master')

@section('content')

    <div style="display:none;">STOCK_ADJUSTMENTS_INDEX_PAGE</div>
    <h1>Stock Adjustments <a href="{{ route('stock_adjustments.create') }}" class="btn btn-primary pull-right btn-sm">New Adjustment</a></h1>
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th><th>Facility </th><th>Commodity</th><th>Adjustment Type</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            {{-- */$x=0;/* --}}
            @foreach($stock_adjustments as $item)
                {{-- */$x++;/* --}}
                <tr>
                    <td>{{ $x }}</td>
                    <td><a href="{{ url('/stock_adjustments', $item->id) }}"> <!-- {{ $item->facility_id }} -->
                         <?php
                                $facilityName="";
                                $facility=DB::table('facilities')
                                          ->select('facility')
                                          ->where('id',$item->facility_id)
                                          ->get();  
                                 foreach ($facility as $facility_name ) {
                                     $facilityName=$facility_name->facility;
                                 }
                                echo "$facilityName";; 
                            ?>

                    </a></td>
                    <td><!-- {{ $item->commodity_id }} -->
                        <?php
                                $commodityName="";
                                $commodity=DB::table('commodities')
                                          ->select('commodity')
                                          ->where('id',$item->commodity_id)
                                          ->get();  
                                 foreach ($commodity as $commodity_name ) {
                                     $commodityName=$commodity_name->commodity;
                                 }
                                echo "$commodityName";; 
                        ?>
                    </td>
                    <td>{{ $item->adjustment_type }}</td>
                    <td>
                        <a href="{{ route('stock_adjustments.edit', $item->id) }}">
                            <button type="submit" class="btn btn-primary btn-xs">Update</button>
                        </a> /
                        {!! Form::open([
                            'method'=>'DELETE',
                            'route' => ['stock_adjustments.destroy', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $stock_adjustments->render() !!} </div>
    </div>

@endsection
