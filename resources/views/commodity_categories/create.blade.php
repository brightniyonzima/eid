@extends('layouts.master')

@section('content')

    <h1>Create New Commodity_category</h1>
    <hr/>

    {!! Form::open(['route' => 'commodity_categories.store', 'class' => 'form-horizontal']) !!}

                <div class="form-group {{ $errors->has('category_name') ? 'has-error' : ''}}">
                {!! Form::label('category_name', 'Category Name: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::hidden('id', null) !!}
                    {!! Form::text('category_name', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('category_name', '<p class="help-block">:message</p>') !!}
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