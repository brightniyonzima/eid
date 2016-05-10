@extends('commodities.stockin.container')
@section('cmdt_content')
<div class="panel panel-default">
	<div class="panel-heading"><b>Stockin :: Edit</b></div>
	<div class="panel-body">
		{!! Session::get('msge') !!}
		{!! Form::open(array('url'=>'commodities/stockin/update/'.$id,'id'=>'form_id')) !!}

		<table class='table table-bordered'>
			<tr>
				<td class='td_label' width='20%'><label for='a'>Commodity:</label></td>
				<td>{!! MyHTML::select('commodityID',$commodities,$commodity_stockin->commodityID,'a') !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='b'>Quantity of units in:</label></td>
				<td>{!! MyHTML::text('quantity',$commodity_stockin->quantity,"form-control",'b') !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='arr_date'>Arrival Date :</label></td>
				<td>{!! MyHTML::text('arrival_date',MyHTML::localiseDate($commodity_stockin->arrival_date),"form-control",'arr_date') !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='d'>Batch No.</label></td>
				<td>{!! MyHTML::text('batchno',$commodity_stockin->batchno,"form-control",'d') !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='exp_date'>Expiry Date</label></td>
				<td>{!! MyHTML::text('expiry_date',MyHTML::localiseDate($commodity_stockin->expiry_date),"form-control",'exp_date') !!} </td>
			</tr>
			
			

			<tr><td/><td>{!! MyHTML::submit('Save') !!} </td></tr>
		</table>

		{!! Form::close() !!}
	</div>
</div>
<script type="text/javascript">
$(function() { 
	$( "#arr_date" ).datepicker(); 
	$( "#exp_date" ).datepicker();

});
</script>
@endsection

