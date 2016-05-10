@extends('commodities.app')
@section('cm-content')
<div id='d6' class="panel panel-default">
	<div class="panel-body">
		{!! link_to('commodities/facility_reqs/pending_reqs','View pending orders',['class'=>'btn btn-primary btn-side']) !!}
		<br><br>
		{!! Session::get('msge') !!}
		{!! Form::open(array('url'=>'commodities/facility_reqs/store','id'=>'form_id')) !!}

		<table class='table table-bordered'>
			<tr>
				<td class='td_label' width='20%'><label for='a'>Facility:</label></td>
				<td>{!! MyHTML::select('facilityID',$facilities,'','a') !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='b'>Requisition Method:</label></td>
				<td>{!! MyHTML::select('req_methodID',$mthds,'','b') !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='req_date'>Requisition Date :</label></td>
				<td>{!! MyHTML::text('requisition_date',"","form-control",'req_date') !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='d'>Commodity:</label></td>
				<td>{!! MyHTML::select('commodityID',$commodities,'','d') !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='e'>Quantity of units:</label></td>
				<td>{!! MyHTML::text('quantity_requisitioned',"","form-control",'e') !!} </td>
			</tr>
			
			<tr><td/><td>{!! MyHTML::submit('Save') !!} </td></tr>
		</table>

		{!! Form::close() !!}
	</div>
</div>
<script type="text/javascript">
$(function() { 
	$( "#req_date" ).datepicker();
});
</script>
@endsection

