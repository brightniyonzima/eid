@extends('layouts.master')

@section('content')

    <h1>Commodity</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>Commodity Name</th><th>Category Id</th><th>Tests Per Unit</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $commodity->id }}</td> <td> {{ $commodity->commodity_name }} </td><td> {{ $commodity->category_id }} </td><td> {{ $commodity->tests_per_unit }} </td>
                </tr>
            </tbody>    
        </table>
    </div>

@endsection