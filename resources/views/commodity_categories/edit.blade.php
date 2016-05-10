@extends('layouts.master')

@section('content')

    <h1>Edit Commodity_category</h1>
    <hr/>

    {!! Form::model($commodity_category, [
        'method' => 'PATCH',
        'route' => ['commodity_categories.update', $commodity_category->id],
        'class' => 'form-horizontal'
    ]) !!}

                <div class="form-group {{ $errors->has('category_name') ? 'has-error' : ''}}">
                {!! Form::label('category_name', 'Category Name: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('category_name', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('category_name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>


    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            {!! Form::submit('Update', ['class' => 'btn btn-primary form-control', 'name'=>'update_commodity']) !!}
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