@extends('layouts/layout')

@section('content')

<?php 

	$selected_batches = DispatchManager::getBatchesForDispatch(false);
	$nBatches = count($selected_batches);

	if($nBatches == 0) dd('No batches for Follow-Up - Please go back');
?>

<style type="text/css">
	.ws_row:hover{
		background-color: #fcf8e3;
	}
	th{
		text-align: center;
	}
</style>

<section id='s5' class='mm'></section>
	<h1 align="center">Follow-Up Forms</h1>
	<table id='tab_id' class="table table-bordered" cellspacing="0" cellpadding="4" align="center" style="margin-top: 1em; border: 1px solid #ddd" >
		<thead>
			<tr>
				<th><small>Hub</small></th>
				<th><small>Facility</small>	</th>
				<th><small>Batch</small></th>
				<th><small>Actions</small></th>
			</tr>	
		</thead>	
		<tbody>	

		@foreach($selected_batches as $w)

			<tr class="ws_row">
				<td>&nbsp;&nbsp;{{ $w->hubname }} </td>
				<td> {{ $w->facility }} </td>
				<td> {{ $w->batch_number }} </td>
				<td align="center">
					<div class="btn-group">

						<button type="button" 
								class="btn btn-primary btn-xs dropdown-toggle"
								style="font-size: 1.1em; float:right" 
								data-toggle="dropdown" aria-expanded="false">

							Action <span class="caret"></span>						
						</button>

						<ul class="dropdown-menu pull-right worksheet_actions" 	role="menu">
							<li><a 	action="edit" 	worksheet="{{ $w->id }}"	href="ai?f={{$w->id}}"><span class="glyphicon glyphicon-pencil pull-left">&nbsp;</span>Enter data</a></li>
							<li><a  action="uploadFile" worksheet="{{ $w->id }}"	href="ai?fd=1&b=1&f={{$w->id}}"><span class="glyphicon glyphicon-open-file pull-left">&nbsp;</span>Print</a></li>
						</ul>
					</div>
				</td>
			</tr>

		@endforeach
		</tbody>

	</table>
	<script type="text/javascript">
	$(document).ready(function() { $('#tab_id').DataTable(); });

	$(function (){
		var worksheet_from_menu = document.getElementById('cx2');

		$('.worksheet_actions li > a').click(function () {

				var __this = $(this);
				var action = __this.attr("action");
				var target = __this.attr("worksheet");


				if(	target === undefined ){// via menu
					
					target = parseInt(worksheet_from_menu.value);
					
					if(isNaN(target)){
						alert("Please select a worksheet");
						return false;
					}
				} 

				// doWorksheetAction(action, target);
			});

			// function doWorksheetAction(action, target){
			// 	switch(action){
			// 		case "create" : location = "/w";  return;
			// 		case "edit"	: 	location = "/ws?i=edit&ws="+target;  return;
			// 		case "view"	: 	location = "/ws?i=view&ws="+target;  return;
			// 		case "toPDF": 	location = "/ws?i=toPDF&ws="+target; return;
			// 		case "uploadFile" :	location = "/ws?i=uploadFile&ws="+target; return;
			// 		case "saveFile":location = "/ws?i=saveFile&ws="+target; return;	
			// 		case "delete" : location = "/ws?i=del&ws="+target; return;
			// 	}
			// }

			$("#make_new_worksheet").click(function (evt) {
				doWorksheetAction("create");
			});
		});
	</script>

@stop