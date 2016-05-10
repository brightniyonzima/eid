@extends('layouts.master')

@section('content')

    <h1>Create New Commodity</h1>
    <hr/>

    {!! Form::open(['route' => 'commodities.store', 'class' => 'form-horizontal']) !!}

                <div class="form-group {{ $errors->has('commodity_name') ? 'has-error' : ''}}">
                {!! Form::label('commodity_name', 'Commodity Name: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::hidden('id', null) !!}
                    {!! Form::text('commodity_name', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('commodity_name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
            <div class="form-group {{ $errors->has('category_id') ? 'has-error' : ''}}">
                {!! Form::label('category_id', 'Category: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6 ">

                <?php 
                    $arr = [];

                    $categories = StockManager::getCategories();
                    $nCategories = count($categories);

                    for ($i=0; $i < $nCategories ; $i++) {
                        $arr[] = rand();
                    }
                ?>

                    {!! Form::select('xyz', $arr,
                        null, ['class' => 'form-control']) 
                    !!}

                    {!! Form::select('category_id', StockManager::getCategories(), null, ['class' => 'form-control']) !!}
                    {!! $errors->first('category_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>



            <div class="form-group {{ $errors->has('tests_per_unit') ? 'has-error' : ''}}">
                {!! Form::label('tests_per_unit', 'Tests Per Unit: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    {!! Form::text('tests_per_unit', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('tests_per_unit', '<p class="help-block">:message</p>') !!}
                </div>
            </div>


    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            {!! Form::submit('SUBMIT', ['class' => 'btn btn-primary form-control', 'name'=>'store_commodity']) !!}
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



<?php
/*

    <select name="xyz">
        <option value="dd1">11</option>
        <option value="dd2">13</option>
        <option value="dd3">17</option>
    </select>
    <select class="form-control" id="category_id" name="category_id">
        <option value="3">REAGENTS KITS</option>
        <option value="4">TEST SPECIFIC CONSUMABLES</option>
        <option value="5">MACHINE CONSUMABLE</option>
        <option value="6">STATIONERY</option>
        <option value="7">FORMS</option>
        <option value="8">OTHERS</option>
    </select>
                    

*/

?>