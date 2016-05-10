@extends('commodities.stock_status.container')
@section('cmdt_content')
<div class="panel panel-default">
	<div class="panel-heading"><b>Commodities</b></div>
	<div class="panel-body">

		<table class='table table-striped table table-condensed' id='tab_id'>
		  <thead>
			<tr>
				<th>Commodity</th>
				<th>Category</th>				
				<th>Current Balance</th>			
			</tr>
		  </thead>
		  <tbody>
			@foreach ($commodities AS $commodity)		 
			<tr>
			<?php
			echo "<td>$commodity->commodity</td>";
			echo "<td>$commodity->category</td>";
			echo "<td>".$commodity->getCurrentQuantity($commodity->id)."</td>";
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

