@extends('customer_care.container')
@section('content_cc')
<div id='d3' class="panel panel-default">
	<div class="panel-heading"><b>Complaints</b></div>
	<div class="panel-body">
		{!! Session::get('msge') !!}
		
		<table class='table table-striped table table-condensed' id='tab_id'>
		  <thead>
			<tr>
				<td>Complaint</td>
				<td>Resolved</td>
				<td>Facility</td>
				<th>Complainant</th>
				<th>Telephone</th>
				<th>Email</th>
				<th></th>
			</tr>
		  </thead>
		  <tbody>
			@foreach ($complaints AS $complaint)		 
			<tr>
			<?php
			echo "<td>$complaint->complaint</td>"; 
			echo "<td>$complaint->resolved</td>";
			echo "<td>$complaint->facility</td>"; 
			echo "<td>$complaint->complainant</td>";
			echo "<td>$complaint->complainant_telephone</td>";
			echo "<td>$complaint->complainant_email</td>";

			?>
			<td>
			<div class="btn-group">
				<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					Options <span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li>{!! link_to("customer_care/complaints/show/$complaint->id","View") !!}</li>
					<!-- <li>{!! link_to("customer_care/complaints/edit/$complaint->id","Edit") !!}</li>
					<li>{!! link_to("customer_care/complaints/respond/$complaint->id","Respond") !!}</li> -->
					<li><a href="#">Edit</a></li>
					<li><a href="#">Respond</a></li>
				</ul>
			</div>
		    </td>
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

