@extends('commodities.app')
@section('cm-content')
<div id='d5' class="panel panel-default">
	<div class="panel-body">
		{!! link_to('commodities/requisitions/pending_reqs','View pending requisitions',['class'=>'btn btn-primary btn-side']) !!}
		<br><br>
		{!! Session::get('msge') !!}
		{!! Form::open(array('url'=>'commodities/requisitions/store','id'=>'form_id')) !!}

		<table class='table table-bordered'>
			<tr>
				<td class='td_label' width='20%'><label for='a'>Commodity:</label></td>
				<td>{!! MyHTML::select('commodityID',$commodities,'','a') !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='b'>Quantity of units:</label></td>
				<td>{!! MyHTML::text('quantity_requisitioned',"","form-control",'b') !!} </td>
			</tr>
			
			<tr><td/><td>{!! MyHTML::submit('Save') !!} </td></tr>
		</table>

		{!! Form::close() !!}
	</div>
</div>
@endsection

