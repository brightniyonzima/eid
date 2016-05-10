@extends('commodities.app')
@section('cm-content')
<div id='d7' class="panel panel-default">
	<div class="panel-body">
		<table class='table table-striped table table-condensed' id='tab_id'>
		  <thead>
			<tr>
				<th>Commodity</th>
				<th>Category</th>				
				<th>Current Balance</th>	
				<th>Alert Quantity</th>		
			</tr>
		  </thead>
		  <tbody>
			@foreach ($commodities AS $commodity)		 
			<tr>
			<?php
			$bal=$commodity->getCurrentQuantity($commodity->id);
			$alert_quant=$commodity->alert_quantity;
			$diff=abs($bal-$alert_quant);
			echo "<td>$commodity->commodity</td>";
			echo "<td>$commodity->category</td>";
			if($bal<=$alert_quant){
				$ttle=$bal==$alert_quant?"balance equal to alert quantity of $alert_quant":"$diff units below alert quantity of $alert_quant";
				echo "<td><label class='status_danger' title='$ttle'>$bal</label></td>";
			}else{
				$ttle="$diff units above alert quantity of $alert_quant";
				echo "<td><label class='status_ok' title='$ttle'>$bal</label></td>";
			}
			echo "<td>$alert_quant</td>";
			
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

