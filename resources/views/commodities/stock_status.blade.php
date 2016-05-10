@extends('commodities.commodities.container')
@section('cmdt_content')
<div class="panel panel-default">
	<div class="panel-heading"><b>Commodities</b></div>
	<div class="panel-body">

		<table class='table table-striped table table-condensed' id='tab_id'>
		  <thead>
			<tr>
				<th>Commodity</th>
				<th>Category</th>
				<th>Initial Quantity</th>
				<th>Number of Tests per unit</th>
				<th width='8%' />
				
			</tr>
		  </thead>
		  <tbody>
			@foreach ($commodities AS $commodity)		 
			<tr>
			<?php 
			echo "<td>$commodity->commodity</td>";
			echo "<td>$commodity->category</td>";
			echo "<td>$commodity->initial_quantity</td>";
			echo "<td>$commodity->tests_per_unit</td>";
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

