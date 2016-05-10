@extends('commodities.facility_reqs.container')
@section('cmdt_content')
<div class="panel panel-default">
	<div class="panel-heading"><b>Stock in history</b></div>
	<div class="panel-body">

		<table class='table table-striped table table-condensed' id='tab_id'>
		  <thead>
			<tr>
				<th>Commodity</th>
				<th>Quantity</th>
				<th>Arrival Date</th>
				<th>Batch No.</th>
				<th>Expiry Date</th>
				<th width='10%' />
				
			</tr>
		  </thead>
		  <tbody>
			@foreach ($stockins AS $stockin)		 
			<tr>
			<?php 
			echo "<td>$stockin->commodity</td>";
			echo "<td>$stockin->quantity</td>";
			echo "<td>$stockin->arrival_date</td>";
			echo "<td>$stockin->batchno</td>";
			echo "<td>$stockin->expiry_date</td>";
			echo "<td>".link_to("commodities/stockin/show/$stockin->id","View")."";
			echo " | ".link_to("commodities/stockin/edit/$stockin->id","Edit")."</td>";

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
