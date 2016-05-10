@extends('commodities.facility_reqs.container')
@section('cmdt_content')
<div class="panel panel-default">
	<div class="panel-heading"><b>Requisition details</b></div>
	<div class="panel-body">

		@if(is_object($requisition))
		{!! Session::get('msge') !!}
		<table class='table borderless'>
			<tr>
				<td class='td_label'><label >Facility:</label></td>
				<td>{{ $requisition->facility }} </td>
			</tr>
			<tr>
				<td class='td_label'><label >Requisition Method:</label></td>
				<td>{{ $requisition->method }} </td>
			</tr>
			<tr>
				<td class='td_label'><label >Requisition Date:</label></td>
				<td>{{ MyHTML::localiseDate($requisition->requisition_date) }} </td>
			</tr>
			<tr>
				<td class='td_label' width='20%'><label >Commodity:</label></td>
				<td>{{ $requisition->commodity }} </td>
			</tr>
			<tr>
				<td class='td_label'><label >Quantity requisitioned:</label></td>
				<td>{{ $requisition->quantity_requisitioned }} </td>
			</tr>
			
			
						

			<!-- <tr><td/><td>{!! MyHTML::link_to("commodities/requis/edit/".$requisition->id,"Edit","btn btn-primary") !!} </td></tr> -->
		</table>
		@endif
	</div>
</div>

@endsection

