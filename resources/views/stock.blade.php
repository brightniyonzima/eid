@extends('layouts.master')

@section('content')

  <div class="container">
    <h3>Commodity Management</h3>
  </div>
  <hr/>

  <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-4">
      <div class="list-group table-of-contents">
        <a class="list-group-item" href="/stock_status">Stock Status</a>
        <a class="list-group-item" href="/stock_requisition_header">Orders (Facility)</a>
        <a class="list-group-item" href="/stock_requisition_header?i=1">Orders (In House)</a>
        <a class="list-group-item" href="/receivestock">Delivery Log (In)</a>
        <a class="list-group-item" href="/stock_requisition_header?out=1">Delivery Log (Out)</a>
        <a class="list-group-item" href="/stock_settings">Settings</a>

      </div>
    </div>
  </div>


@endsection