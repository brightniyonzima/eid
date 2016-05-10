@extends('commodities.app')
@section('cm-content')
<div id='d3' class="panel panel-default">
	<div class="panel-body">
		{!! link_to('commodities/commodities/create','Create new commodity',['class'=>'btn btn-primary btn-side']) !!}
		<br><br>
		<table class='table table-striped table table-condensed' id='tab_id'>
		  <thead>
			<tr>
				<th>Commodity</th>
				<th>Category</th>
				<th>Number of Tests per unit</th>
				<th>Initial Quantity</th>
				<th>Alert Quantity</th>
				<th width='10%' />				
			</tr>
		  </thead>
		  <tbody>
			@foreach ($commodities AS $commodity)		 
			<tr>
			<?php
			echo "<td>$commodity->commodity</td>";
			echo "<td>$commodity->category</td>";
			echo "<td>$commodity->tests_per_unit</td>";
			echo "<td>$commodity->initial_quantity</td>";
			echo "<td>$commodity->alert_quantity</td>";
			echo "<td>".link_to("commodities/commodities/show/$commodity->id","View")."";
			echo " | ".link_to("commodities/commodities/edit/$commodity->id","Edit")."</td>";

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

