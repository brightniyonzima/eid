@extends('layouts.master')

@section('content')

    <h1>Edit Stock_requisition_header</h1>
    <hr/>

    {!! Form::model($stock_requisition_header, [
        'method' => 'PATCH',
        'route' => ['stock_requisition_header.update', $stock_requisition_header->id],
        'class' => 'form-horizontal'
    ]) !!}

                <div class="form-group {{ $errors->has('facility_id') ? 'has-error' : ''}}">
                {!! Form::label('facility_id', 'Facility Id: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('facility_id', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('facility_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('requisition_date') ? 'has-error' : ''}}">
                {!! Form::label('requisition_date', 'Requisition Date: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('requisition_date', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('requisition_date', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('requisition_method') ? 'has-error' : ''}}">
                {!! Form::label('requisition_method', 'Requisition Method: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('requisition_method', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('requisition_method', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('requestors_name') ? 'has-error' : ''}}" style="display:none">
                {!! Form::label('requestors_name', 'Requestors Name: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::hidden('requestors_name', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('requestors_name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('requestors_phone') ? 'has-error' : ''}}" style="display:none">
                {!! Form::label('requestors_phone', 'Requestors Phone: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::hidden('requestors_phone', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('requestors_phone', '<p class="help-block">:message</p>') !!}
                </div>
            </div>


            <div class="form-group {{ $errors->has('requestors_batch_number') ? 'has-error' : ''}}">
                {!! Form::label('requestors_batch_number', 'Requestors Batch Number: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('requestors_batch_number', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('requestors_batch_number', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('approved_by') ? 'has-error' : ''}}">
                {!! Form::label('approved_by', 'Approved By: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('approved_by', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('approved_by', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('date_approved') ? 'has-error' : ''}}">
                {!! Form::label('date_approved', 'Date Approved: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('date_approved', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('date_approved', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('dispatched_by') ? 'has-error' : ''}}">
                {!! Form::label('dispatched_by', 'Dispatched By: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('dispatched_by', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('dispatched_by', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('date_dispatched') ? 'has-error' : ''}}">
                {!! Form::label('date_dispatched', 'Date Dispatched: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('date_dispatched', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('date_dispatched', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('receivers_name') ? 'has-error' : ''}}">
                {!! Form::label('receivers_name', 'Receivers Name: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('receivers_name', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('receivers_name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('receivers_phone') ? 'has-error' : ''}}">
                {!! Form::label('receivers_phone', 'Receivers Phone: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('receivers_phone', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('receivers_phone', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('date_received') ? 'has-error' : ''}}">
                {!! Form::label('date_received', 'Date Received: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::date('date_received', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('date_received', '<p class="help-block">:message</p>') !!}
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