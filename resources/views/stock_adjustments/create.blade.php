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

    <h1>New Stock Adjustment</h1>
    <hr/>
    <?php   $commodities = StockManager::getCommodities();
                $nCommodities = count($commodities);
                
                $facilities = StockManager::getFacilities();
                $nFacilities = count($facilities);

                $district = "_NONE_";
    ?>

    {!! Form::open(['route' => 'stock_adjustments.store', 'class' => 'form-horizontal']) !!}

            <div class="form-group {{ $errors->has('facility_id') ? 'has-error' : ''}}">
                {!! Form::label('facility_id', 'Facility ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    <select class="js-example-basic-single" style="height:3em; width:30em;" id="facility_id" name="facility_id">
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
                    {!! $errors->first('facility_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group {{ $errors->has('commodity_id') ? 'has-error' : ''}}">
                {!! Form::label('commodity_id', 'Commodity: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    <select class="form-control" style="height: 3em; width: 30em;" name="commodity[]">
                    @foreach($commodities as $commodity)
                        <option value="{{ $commodity->id }}"> {{ $commodity->commodity }} </option>
                    @endforeach
                    </select>
                    {!! $errors->first('commodity_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('adjustment_type') ? 'has-error' : ''}}">
                {!! Form::label('adjustment_type', 'Adjustment Type: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    <select class="form-control" style="height: 3em; width: 30em;" name="adjustment_type">
                        <option value="INCREASE"> INCREMENT </option>
                        <option value="DECREASE"> DECREMENT </option>
                    </select>
                    {!! $errors->first('adjustment_type', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('change_in_quantity') ? 'has-error' : ''}}">
                {!! Form::label('change_in_quantity', 'Change In Quantity: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('change_in_quantity', null, ['class' => 'form-control', 'required'=>'yes']) !!}
                    {!! $errors->first('change_in_quantity', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('remarks') ? 'has-error' : ''}}">
                {!! Form::label('remarks', 'Remarks: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('remarks', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('remarks', '<p class="help-block">:message</p>') !!}
                </div>
            </div>


    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            {!! Form::submit('Create', ['class' => 'btn btn-primary form-control']) !!}
        </div>
    </div>
    {!! Form::close() !!}

    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

<script src="/js/select2.min.js"></script>

@endsection