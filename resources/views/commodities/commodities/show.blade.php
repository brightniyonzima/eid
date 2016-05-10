@extends('commodities.app')
@section('cm-content')
<div id='d3' class="panel panel-default">
	<div class="panel-heading"><b>Commodities::{!! $commodity->commodity !!}</b></div>
	<div class="panel-body">

		@if(is_object($commodity))
		{!! Session::get('msge') !!}
		<table class='table table-bordered'>
			<tr>
				<td class='td_label' width='20%'><label for='a'>Commodity:</label></td>
				<td>{{ $commodity->commodity }} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='b'>Category:</label></td>
				<td>{{ $commodity->category }} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='c'>Initial Quantity:</label></td>
				<td>{{ $commodity->initial_quantity }} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='d'>Tests per unit:</label></td>
				<td>{{ $commodity->tests_per_unit }} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='e'>Alert Quantity:</label></td>
				<td>{!! $commodity->alert_quantity !!} </td>
			</tr>	
			

			<tr><td/><td>{!! MyHTML::link_to("commodities/commodities/edit/".$commodity->id,"Edit","btn btn-primary") !!} </td></tr>
		</table>
		@endif
	</div>
</div>

@endsection

