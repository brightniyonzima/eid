@extends('commodities.app')
@section('cm-content')
<div id='d6' class="panel panel-default">
	<div class="panel-body">
		{!! link_to('commodities/facility_reqs/create','Record new order',['class'=>'btn btn-primary btn-side']) !!}
		<br><br>

		<table class='table table-striped table table-condensed' id='tab_id'>
		  <thead>
			<tr>
				<th>Facility</th>
				<th>Method</th>
				<th>Date</th>
				<th>Commodity</th>
				<th>Quantity</th>
				<th width='10%' />
				
			</tr>
		  </thead>
		  <tbody>
			@foreach ($requisitions AS $requisition)		 
			<tr>
			<?php 
			echo "<td>$requisition->facility</td>";
			echo "<td>$requisition->method</td>";
			echo "<td>".MyHTML::localiseDate($requisition->requisition_date)."</td>";
			echo "<td>$requisition->commodity</td>";
			echo "<td>$requisition->quantity_requisitioned</td>";
			echo "<td>".link_to("commodities/facility_reqs/approve/$requisition->id","Approve")."</td>";
			?>
			</tr>		 
			@endforeach
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
  	$('#tab_id').DataTable();
  });
</script>
@endsection

