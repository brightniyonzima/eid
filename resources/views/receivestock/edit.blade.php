@extends('layouts.master')

@section('content')

    <h1>Received Stock</h1>
    <hr/>

    {!! Form::model($receivestock, [
        'method' => 'PATCH',
        'route' => ['receivestock.update', $receivestock->id],
        'class' => 'form-horizontal'
    ]) !!}

                <div class="form-group {{ $errors->has('commodity_id') ? 'has-error' : ''}}">
                {!! Form::label('commodity_id', 'Commodity Id: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('commodity_id', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('commodity_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('qty_rcvd') ? 'has-error' : ''}}">
                {!! Form::label('qty_rcvd', 'Quantity: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('qty_rcvd', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    {!! $errors->first('qty_rcvd', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('batch_number') ? 'has-error' : ''}}">
                {!! Form::label('batch_number', 'Batch Number: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('batch_number', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('batch_number', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('arrival_date') ? 'has-error' : ''}}">
                {!! Form::label('arrival_date', 'Arrival Date: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('arrival_date', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('arrival_date', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('expiry_date') ? 'has-error' : ''}}">
                {!! Form::label('expiry_date', 'Expiry Date: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('expiry_date', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('expiry_date', '<p class="help-block">:message</p>') !!}
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