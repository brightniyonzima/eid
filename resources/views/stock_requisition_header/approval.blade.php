@extends('layouts.master')

@section('content')
    <link   href="/css/pikaday.css"     rel="stylesheet" >
    <link   href="/css/select2.min.css" rel="stylesheet" />
    <h1> Approve/Reject Requisitions</h1>
    <hr/>

<?php   
        $s = EID\stock_requisition_header::find($requisition_id);
        // dd($s->toArray());

        $line_items = DB::select("SELECT * FROM stock_requisition_line_items WHERE requisition_header_id = '$requisition_id'");
        // dd($line_items);

        // 'action' => 'stock_requisition_headerController@approval'


        if(Request::has('go')){
            $ff = Request::all();
            StockManager::store_approved_quantities($ff["requisition_header_id"], $ff["commodity_id"], 
                                                        $ff["quantity_requested"], $ff["quantity_approved"],$ff["facility_id"],
                                                        $ff["requestors_name"],$ff["requestors_phone"]);            
        }
?>
<div class="container">

    {!! Form::model($s, array('class'=> 'form-horizontal', 'role'=>'form', 'method'=>'get'
                                )) !!}

    <div class="form-group">
        <input  type="hidden" name="requisition_header_id" value="{{ $requisition_id }}" >

        <label for="input1" class="col-lg-2 control-label">Requesting Facility:</label>
        <div class="col-lg-3">

        <?php   $commodities = StockManager::getCommodities();
                $nCommodities = count($commodities);
                
                $facilities = StockManager::getFacilities();
                $nFacilities = count($facilities);

                if(Input::has('submit')) {
                    StockManager::store_requisition();
                }

                $district = "_NONE_";
        ?>

        <select class="js-example-basic-single"
                    id="facility_id" name="facility_id">
            <option></option>

            @for($i=0; $i < $nFacilities; $i++)
                <?php   $facility = $facilities[ $i ];
                        $selection_status = " ";

                        if($s != null){
                            if($s->facility_id == $facility->facility_id){
                                $selection_status = " selected = 'YES' ";
                            }
                        }
                 ?>

                    @if( $district !== $facility->district )
                        
                        @if( $district !== "_NONE_" )
                            </optgroup>
                        @endif

                        {{ $district =  $facility->district }}
                        <optgroup label="{{ $district }}">
                    @endif


                <option value="{{ $facility->facility_id }}" {{ $selection_status }}>{{ $facility->facility_name }}</option>
            @endfor

        </select>

        </div>
        <div class="col-lg-3">
            {!! Form::text("requisition_date", null, 
                    [   "class"=>"form-control dbs_date", 
                        "id"=>"requisition_date",
                        "placeholder"=>"Requisition Date"
                    ]); 
            !!}
            <!-- <input type="text" name="requisition_date" class="form-control dbs_date" id="requisition_date" placeholder="Requisition Date"> -->
        </div>
    </div>


    <div class="form-group">
        <label for="input1" class="col-lg-2 control-label">Requisition Method:</label>
        <div class="col-lg-6 ">
            {!! Form::select('requisition_method', StockManager::getRequisitionMethods(), null, ['class' => 'form-control']) !!}
            {!! $errors->first('requisition_method', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="col-lg-3" style="display:none">

            <input type="text" name="requestors_batch_number" class="form-control" 
                    id="requestors_batch_number" placeholder="requestors_batch_number">
        </div>
    </div>


    <div class="form-group">
        <label for="input1" class="col-lg-2 control-label">Requester's Name:</label>
        <div class="col-lg-3">
            {!! Form::text("requestors_name", null, 
                    [   "class"=>"form-control", 
                        "id"=>"requestors_name",
                        "placeholder"=>"Requester's Name"
                    ]); 
            !!}

<!--             <input type="text" class="form-control" 
                    id="requestors_name" name="requestors_name" placeholder="Requester's Name">
 -->        
        </div>
        <div class="col-lg-3">
            {!! Form::text("requestors_phone", null, 
                    [   "class"=>"form-control", 
                        "id"=>"requestors_phone",
                        "placeholder"=>"Requester's Phone"
                    ]); 
            !!}

<!--             
            <input type="text" class="form-control" 
                    id="requestors_phone" name="requestors_phone" placeholder="Requester's Phone">
 -->    
        </div>
    </div>  


    <hr/>

    <table class="table table-bordered">
        <tr>
            <th>&nbsp;&nbsp;#&nbsp;</th>
            <th>Commodity</th>
            <th>Qty Requested</th>
            <th>Qty Forecast</th>
            <th>Qty Approved</th>
            <th>Approver's Comments</th>
        </tr>
        <?php $i=1; ?>
        @foreach($line_items as $line)
        <tr>
            <td class="col-lg-1">{{ $i++ }}</td>
            <td class="col-lg-2">
            

                    <input  type="hidden" name="commodity_id[]" value="{{ $line->commodity_id }}" >
                    {{ StockManager::getCommodityName( $line->commodity_id ) }} 
            </td>
            <td class="col-lg-1">
                    <input  type="text" class="form-control" 
                            id="quantity_requested" 
                            name="quantity_requested[]" 
                            placeholder="Qty Requested" 
                            value="{{ $line->quantity_requested }}" >
            </td>
            <td class="col-lg-1">
                    <input type="text" class="form-control disabled" id="input2" placeholder="Input2" >
            </td>
            <td class="col-lg-1">
                    <input  type="text" class="form-control" 
                            id="quantity_approved" 
                            name="quantity_approved[]" 
                            placeholder="Qty Approved" 
                            value="{{ $line->quantity_approved }}" >
            </td>
            <td class="col-lg-3">
                    <input type="text" class="form-control" id="comment" placeholder="Input2" readonly="yes">
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="9">
                <input type="submit" class="btn btn-success" style="float:right" name="go" value="APPROVE">
            </td>
        </tr>
    </table>

</div>
<!-- </form> -->
{!! Form::close() !!}

<script src="/js/moment.js"></script>
<script src="/js/pikaday.js"></script>
<script src="/js/plugins/pikaday.jquery.js"></script>
<script src="/js/select2.min.js"></script>
<script src="/js/requisition.js"></script>

@endsection