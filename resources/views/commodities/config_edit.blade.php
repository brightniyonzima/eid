@extends('commodities.app')
@section('content')
<div class="panel panel-default">
	<div class="panel-heading"><b>Config :: Edit</b></div>
	<div class="panel-body">
		{!! Session::get('msge') !!}
		{!! Form::open(array('url'=>'commodities/config/update/'.$id,'id'=>'form_id')) !!}

		<table class='table table-bordered'>
			<tr>
				<td class='td_label' width='20%'><label for='a'>{!! $item->item !!}:</label></td>
				<td>{!! MyHTML::text('value',$item->value,'form-control','a') !!} </td>
			</tr>	

			<tr><td/><td>{!! MyHTML::submit('Save') !!} </td></tr>
		</table>
		<input type='hidden' name='item' value="{!! $item->item !!}">

		{!! Form::close() !!}
	</div>
</div>
@endsection
		{!! Form::close() !!}
	</div>
</div>
@endsection

