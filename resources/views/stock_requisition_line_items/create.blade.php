@extends('layouts.master')

@section('content')

    <h1>Create New Stock_requisition_line_item</h1>
    <hr/>

    {!! Form::open(['route' => 'stock_requisition_line_items.store', 'class' => 'form-horizontal']) !!}

                <div class="form-group {{ $errors->has('requisition_header_id') ? 'has-error' : ''}}">
                {!! Form::label('requisition_header_id', 'Requisition Header Id: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('requisition_header_id', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('requisition_header_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('commodity_id') ? 'has-error' : ''}}">
                {!! Form::label('commodity_id', 'Commodity Id: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('commodity_id', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('commodity_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('quantity_requested') ? 'has-error' : ''}}">
                {!! Form::label('quantity_requested', 'Quantity Requested: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('quantity_requested', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('quantity_requested', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('quantity_forecasted') ? 'has-error' : ''}}">
                {!! Form::label('quantity_forecasted', 'Quantity Forecasted: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('quantity_forecasted', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('quantity_forecasted', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('quantity_approved') ? 'has-error' : ''}}">
                {!! Form::label('quantity_approved', 'Quantity Approved: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('quantity_approved', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('quantity_approved', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('approval_result') ? 'has-error' : ''}}">
                {!! Form::label('approval_result', 'Approval Result: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('approval_result', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('approval_result', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('approval_comment') ? 'has-error' : ''}}">
                {!! Form::label('approval_comment', 'Approval Comment: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('approval_comment', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('approval_comment', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('requisition_status') ? 'has-error' : ''}}">
                {!! Form::label('requisition_status', 'Requisition Status: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('requisition_status', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('requisition_status', '<p class="help-block">:message</p>') !!}
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

@endsection