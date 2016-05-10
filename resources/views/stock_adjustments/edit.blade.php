@extends('layouts.master')

@section('content')

    <h1>Edit Stock Adjustment</h1>
    <hr/>

    {!! Form::model($stock_adjustment, [
        'method' => 'PATCH',
        'route' => ['stock_adjustments.update', $stock_adjustment->id],
        'class' => 'form-horizontal'
    ]) !!}

                <div class="form-group {{ $errors->has('facility_id') ? 'has-error' : ''}}">
                {!! Form::label('facility_id', 'Facility Id: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('facility_id', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('facility_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('commodity_id') ? 'has-error' : ''}}">
                {!! Form::label('commodity_id', 'Commodity Id: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('commodity_id', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('commodity_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('adjustment_type') ? 'has-error' : ''}}">
                {!! Form::label('adjustment_type', 'Adjustment Type: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('adjustment_type', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('adjustment_type', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('change_in_quantity') ? 'has-error' : ''}}">
                {!! Form::label('change_in_quantity', 'Change In Quantity: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('change_in_quantity', null, ['class' => 'form-control']) !!}
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
            {!! Form::submit('Update', ['class' => 'btn btn-primary form-control']) !!}
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

@endsection