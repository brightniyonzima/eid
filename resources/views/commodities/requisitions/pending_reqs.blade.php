@extends('commodities.app')
@section('cm-content')
<div id='d5' class="panel panel-default">
	<div class="panel-body">
		{!! link_to('commodities/requisitions/create','Make new requisition',['class'=>'btn btn-primary btn-side']) !!}
		<br><br>

		<table class='table table-striped table table-condensed' id='tab_id'>
		  <thead>
			<tr>
				<th>Commodity</th>
				<th>Quantity</th>
				<th>Requisition Date</th>
				<th>Requisition By</th>
				<th width='10%' />
				
			</tr>
		  </thead>
		  <tbody>
			@foreach ($requisitions AS $requisition)		 
			<tr>
			<?php 
			echo "<td>$requisition->commodity</td>";
			echo "<td>$requisition->quantity_requisitioned</td>";
			echo "<td>".MyHTML::localiseDate($requisition->created)."</td>";
			echo "<td>$requisition->createdby</td>";
			echo "<td>".link_to("commodities/requisitions/approve/$requisition->id","Approve")."</td>";
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

