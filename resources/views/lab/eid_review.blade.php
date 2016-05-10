@extends('layouts/layout')

@section('content')

<?php 

	$sql = "SELECT 	batch_id, 
					batch_number, 
					envelope_number, 
					COUNT(dbs_samples.id) AS nSamples, 
					PCR_results_released, 
					facility_name  
			FROM dbs_samples 
			LEFT JOIN batches ON batches.id = batch_id  
			WHERE dbs_samples.id in (SELECT sample_id FROM worksheet_index 
										WHERE worksheet_number = '$ws_id') 
			GROUP BY batch_id";

	$selected_batches = \DB::select($sql);
	$nBatches = count($selected_batches);

	if($nBatches == 0) dd('No batches to dispatch - Please go back');
?>

<style type="text/css">
	.ws_row:hover{
		background-color: #fcf8e3;
	}
	th{
		text-align: center;
	}

</style>

<section id='s4' class='mm'></section>


	<a href="/rejects" style="float: right; display: inline-block;">&nbsp; Rejected Results </a> 
	<span style="float:right; display: block;">&nbsp;EID Results | </span>
	<a href="/dispatch_scd" style="float: right; display: inline-block;"> &nbsp; Sickle Cell Results | </a>
	<span style="float:right; display: block;">Go To &raquo;</span>
	<h2 align="center">EID .::. Print &amp; Dispatch Results</h2>	

	<form>
	<table 	id='tab_id' class="table table-bordered" 
				cellspacing="0" cellpadding="4" align="center" 
					style="margin-top: 1em; border: 1px solid #ddd" >
		<thead>
			<tr>
				<th><small>Envelope Number</small></th>
				<th><small>Batch</small></th>
				<th><small>Facility</small>	</th>
				<th><small>No. of Samples</small></th>
				<th><small>Status</small>	</th>
			</tr>
		</thead>
		<tbody>
		@foreach($selected_batches as $w)
			<tr class="ws_row">
				<td> {{ substr($w->envelope_number, 0, 8) . " - " . substr($w->envelope_number, 8) }} </td>				
				<td> {{ $w->batch_number }} </td>
				<td> {{ $w->facility_name }} </td>			
				<td align="center"> {{ $w->nSamples }}</td>
				<td align="center"> 
					<input type="hidden" class="batches" id="release_{{ $w->batch_id }}" 
							key="{{ $w->batch_id }}" value="{{$w->PCR_results_released}}">
							
					@if($w->PCR_results_released == 'YES')
						<a href="/eid_review/{{ $w->batch_id }}" 
							batch="{{ $w->batch_id }}" class="btn btn-default trigger_button click_to_retain"
							alt="Click to retain" title="Click to retain">To Be Released</a>
					@else
						<a href="/eid_review/{{ $w->batch_id }}" 
							batch="{{ $w->batch_id }}" class="btn btn-danger trigger_button click_to_release"
							alt="Click to release" title="Click to release">To Be Retained</a>
					@endif
				</td>
			</tr>
		@endforeach
	</tbody>
	</table>
	
	<a href="#" class="btn btn-primary" id="release_all" style="float:right; margin-top: 1em">RELEASE THE BATCHES</a>

	<p style="margin-bottom: 10em;"> &nbsp; </p>
	<script type="text/javascript" src="/js/eid_review.js"></script>
@stop