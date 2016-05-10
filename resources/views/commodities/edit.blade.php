@extends('layouts.master')

@section('content')

    <h1>Edit Commodity</h1>
    <hr/>

    {!! Form::model($commodity, [
        'method' => 'PATCH',
        'route' => ['commodities.update', $commodity->id],
        'class' => 'form-horizontal'
    ]) !!}

                <div class="form-group {{ $errors->has('commodity_name') ? 'has-error' : ''}}">
                {!! Form::label('commodity_name', 'Commodity Name: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('commodity_name', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('commodity_name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('category_id') ? 'has-error' : ''}}">
                {!! Form::label('category_id', 'Category Id: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('category_id', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('category_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('tests_per_unit') ? 'has-error' : ''}}">
                {!! Form::label('tests_per_unit', 'Tests Per Unit: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::number('tests_per_unit', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('tests_per_unit', '<p class="help-block">:message</p>') !!}
                </div>
            </div>


    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            {!! Form::submit('Update', ['class' => 'btn btn-primary form-control', 'name' => 'update_commodity']) !!}
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