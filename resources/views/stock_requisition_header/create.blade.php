@extends('layouts.master')

@section('content')
    
    <?php $web_server = ''; // env('WEB_HOST', "http://localhost"); 
        use EID\Models\User as User;
    ?>

    <link rel="stylesheet" href="{{$web_server}}/css/pikaday.css">

    <!-- Select2 -->
    <link   href="{{$web_server}}/css/select2.min.css" rel="stylesheet" />

    <script src="{{$web_server}}/js/select2.min.js"></script>
    <script src="{{$web_server}}/js/plugins/notify.min.js"></script>
    <script src="{{$web_server}}/js/plugins/jquery.validate.min.js"></script>
    <!-- end select2 -->
    
    <link   href="/css/pikaday.css"     rel="stylesheet" >
    <link   href="/css/select2.min.css" rel="stylesheet" />

    <h1> New Stock Requisition</h1>
    <hr/>

<form class="form-horizontal" role="form">
<div class="container">

    <div class="form-group">
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

        <!--<select class="js-example-basic-single" 
                    id="facility_id" name="facility_id">-->
         <select class="js-example-basic-single" style="width:225px; " tabindex="3" id="facility_id" name="facility_id">
            <option></option>

            @for($i=0; $i < $nFacilities; $i++)
                <?php $facility = $facilities[ $i ] ?>

                    @if( $district !== $facility->district )
                        
                        @if( $district !== "_NONE_" )
                        </optgroup>
                        @endif

                        {{ $district =  $facility->district }}
                        <optgroup label="{{ $district }}">
                    @endif

            <option value="{{ $facility->facility_id }}">{{ $facility->facility_name }}</option>
            @endfor

         </select>

        </div>
        <div class="col-lg-3">
            <input type="hidden" name="id" id="id" value="">
            <input type="text" name="requisition_date" class="form-control dbs_date" id="requisition_date" placeholder="Requisition Date">
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

<!-- Hidden for now, but fields are needed -->
    <div class="form-group" style="display: none">
        <label for="input1" class="col-lg-2 control-label">Requester's Name:</label>
        <div class="col-lg-3">
            <input type="text" class="form-control" 
                    id="requestors_name" name="requestors_name" value="" placeholder="Requester's Name">
        </div>
        <div class="col-lg-3">
            <input type="text" class="form-control" 
                    id="requestors_phone" name="requestors_phone"  value="" placeholder="Requester's Phone">
        </div>
    </div>  

 <hr/>


<div class="container">

    <div style="float:left; width: 20em;"><b>&nbsp;Commodity</b></div>
    <div style="float:left; width: 15em;"><b>&nbsp;Qty Requested</b> </div>
    <div class="clone" style="float:left; width: 10em;"> &nbsp;  </div>
    <div class="delete" style="float:left; width: 10em;"> &nbsp; </div>

    <div class="clone-wrapper">
        <div class="toclone">
            <div style="float:left; width: 20em; clear:both;">
                <select class="form-control" style="height: 3em; width: 20em;" name="commodity[]">
                    @foreach($commodities as $commodity)
                        <option value="{{ $commodity->id }}"> {{ $commodity->commodity }} </option>
                    @endforeach
                </select>
            </div>

            <div style="float:left; width: 15em;">
                <input type="text" style="float:left;height: 3em;" class="form-control" name="qty[]">
            </div>
            <div class="clone" style="float:left; width: 10em;padding-left: 2em;"><a href="#">ADD</a></div>
            <div class="delete" style="float:left; width: 10em;"><a href="#" tabindex="-1">DEL</a></div>
        </div>
    </div>


</div>
<hr/>

    <div style="width: 50em;">
        <input type="submit" name="submit" value="SUBMIT REQUISITION" class="btn btn-primary" style="float: right">
    </div>
</form>

<script src="/js/moment.js"></script>
<script src="/js/pikaday.js"></script>
<script src="/js/plugins/pikaday.jquery.js"></script>
<script src="/js/plugins/jquery-cloneya.min.js"></script>
<script src="/js/select2.min.js"></script>
<script src="/js/requisition.js"></script>

 @endsection
