@extends('commodities.app')
@section('cm-content')
<div id='d3' class="panel panel-default">
	<div class="panel-body">
		{!! Session::get('msge') !!}
		{!! Form::open(array('url'=>'commodities/commodities/update/'.$id,'id'=>'form_id')) !!}

		<table class='table table-bordered'>
			<tr>
				<td class='td_label' width='20%'><label for='a'>Commodity:</label></td>
				<td>{!! MyHTML::text('commodity',$commodity->commodity,'form-control','a') !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='b'>Category:</label></td>
				<td>{!! MyHTML::select('categoryID',$categories,'','b') !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='c'>Initial Quantity:</label></td>
				<td>{!! MyHTML::text('initial_quantity',$commodity->initial_quantity,"form-control",'c') !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='d'>Number tests per unit:</label></td>
				<td>{!! MyHTML::text('tests_per_unit',$commodity->tests_per_unit,"form-control",'d') !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='e'>Alert Quantity:</label></td>
				<td>{!! MyHTML::text('alert_quantity',$commodity->alert_quantity,"form-control",'e') !!} </td>
			</tr>			

			<tr><td/><td>{!! MyHTML::submit('Save') !!} </td></tr>
		</table>

		{!! Form::close() !!}
	</div>
</div>
@endsection



		{!! Form::close() !!}
	</div>
</div>
@endsection

