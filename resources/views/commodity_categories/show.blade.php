@extends('layouts.master')

@section('content')

    <h1>Commodity_category</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Category Name</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $commodity_category->id }}</td> <td> {{ $commodity_category->category_name }} </td>
                </tr>
            </tbody>    
        </table>
    </div>

@endsection