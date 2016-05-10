@extends('layouts.master')


@section('content')

@if(\Request::has('i'))

    <h1>CPHL In-House Orders
        <a href="{{ route('stock_requisition_header.create') }}" 
            class="btn btn-primary pull-right btn-sm"
                >New Order</a>
    </h1>

@else
    <h1>New Delivery
        <a href="{{ route('stock_requisition_header.create') }}" 
            class="btn btn-primary pull-right btn-sm"
                >New Order</a>
    </h1>

@endif
  



    <div class="table">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Facility</th>
                    <th>Requisition Date</th>
                    <th>Requisition Type</th>
                    <th>Approved?</th>
                    <!-- <th>Delivery Status</th> -->
                    <th>Delivery Date</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
            <?php $x=1; ?>
            @foreach($stock_requisition_headers as $item)
                <tr>
                    <td>{{ $x++ }}</td>
                    <td><a href="{{ url('/stock_requisition_header', $item->id) }}"
                            >

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
                        </a>
                    </td>
                    <td>{{ $item->requisition_date }}</td>
                    <td>{{ $item->requisition_method }}</td>
                    <td>{!! StockManager::show_requisition_status('APPROVAL', $item) !!}</td>
                    <!-- <td>{!! StockManager::show_requisition_status('DISPATCH', $item) !!}</td> -->
                    <td>{{ $item->date_approved ?: " - " }}  </td>
                    
                    <td>
                        <a href="{{ 'stock_approval/' . $item->id }}">
                            <button type="submit" class="btn btn-primary btn-xs">Update</button>
                        </a> /
                        {!! Form::open([
                            'method'=>'DELETE',
                            'route' => ['stock_requisition_header.destroy', $item->id],
                            'style' => 'display:inline'
                        ]) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $stock_requisition_headers->render() !!} </div>
    </div>


    <button type="button" class="btn btn-info btn-sm" data-backdrop="false" data-toggle="modal" data-target="#myModal">Pop-up</button>


</div>

  <!-- Modal -->
  <div class="modal modal-lg" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">CHAI - Title Goes Here</h4>
        </div>
        <div class="modal-body">
          <p>CPHL - Contents go here.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>



@endsection