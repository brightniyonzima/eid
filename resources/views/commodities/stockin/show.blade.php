@extends('commodities.app')
@section('cm-content')
<div id='d4' class="panel panel-default">
	<div class="panel-body">
		{!! link_to('commodities/stockin/index','View stock in history',['class'=>'btn btn-primary btn-side']) !!}
		<br><br>

		@if(is_object($commodity_stockin))
		{!! Session::get('msge') !!}
		<table class='table table-bordered'>
			<tr>
				<td class='td_label' width='20%'><label for='a'>Commodity:</label></td>
				<td>{{ $commodity_stockin->commodity }} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='b'>Quantity:</label></td>
				<td>{{ $commodity_stockin->quantity }} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='c'>Arrival Date:</label></td>
				<td>{{ MyHTML::localiseDate($commodity_stockin->arrival_date) }} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='d'>Batch No.:</label></td>
				<td>{{ $commodity_stockin->batchno }} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='e'>Expiry Date:</label></td>
				<td>{{ MyHTML::localiseDate($commodity_stockin->expiry_date) }} </td>
			</tr>
			

			<tr><td/><td>{!! MyHTML::link_to("commodities/stockin/edit/".$commodity_stockin->id,"Edit","btn btn-primary") !!} </td></tr>
		</table>
		@endif
	</div>
</div>

@endsection

