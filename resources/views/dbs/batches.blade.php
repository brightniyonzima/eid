@extends('layouts/layout')

@section('content')
<section id='s1' class='mm'></section>
<table id='tab_id' class='table table-striped table-bordered'>
	<thead>
		<tr>
            <th><small>Envelope No</small></th>
            <th><small>Batch No</small></th>
            <th><small>Facility</small></th>
            <th><small>Date Dispatched<br> from Facility</small></th>
            <th><small>Date Received<br> at Lab</small></th>
            <th><small># Samples</small></th>
            <th><small>(# Not checked,<br># Approved, # Rejected)</small></th>
            <th><small>Batch<br> checked</small></th>
            <th></th>
		</tr>
	</thead>
	<tbody>
		@foreach($batches AS $b)
		<tr>
			<td>{!! $b->envelope_number !!}</td>
			<td>{!! $b->batch_number !!}</td>
			<td>{!! $b->facility_name !!}</td>
			<td>{!! MyHTML::localiseDate($b->date_dispatched_from_facility) !!} </td>
			<td>{!! MyHTML::localiseDate($b->date_rcvd_by_cphl) !!} </td>
			<td>{!! $b->nr_smpls !!}</td>
			<td>(
				<span class='status' title='Number of those not yet checked'>{!! $b->nr_not_yet_checked !!} </span>,
				<span class='status_ok' title='Number approved'>{!! $b->nr_approved !!} </span>,
				<span class='status_danger' title='Number Rejected'>{!! $b->nr_rejected !!} </span>
				)
			</td>
			<td>{!! $b->batch_checked !!}</td>
			<td>
			<div class="btn-group">
				<button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
					Options <span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li><a href="/dbsQ/{!! $b->id !!}">View</a></li>
					<li><a href="/samples/{!! $b->id !!}">Edit</a></li>
				</ul>
			</div>
		    </td>
		</tr>
		
		@endforeach
	</tbody>

</table>
<script type="text/javascript">
$(document).ready(function() {
  	$('#tab_id').DataTable();
  });

</script>
@endsection