@extends('customer_care.container')
@section('content_cc')
<div id='d2' class="panel panel-default">
	<div class="panel-heading"><b>New Complaint</b></div>
	<div class="panel-body">
		{!! Session::get('msge') !!}
		{!! Form::open(['url'=>'customer_care/complaints/store','id'=>'form_id','onsubmit'=>'return chkForm(this)']) !!}

		<table class='table table-bordered'>
			<tr>
				<td class='td_label' width='20%'><label for='a'>Name of Complainant:</label></td>
				<td>{!! Form::text('complainant','',['class'=>'form-control','id'=>'a','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='b'>Facility:</label></td>
				<td>{!! Form::select('facilityID',[""=>"Select"]+$facilities,"",['class'=>'form-control','id'=>'b','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='c'>Complaint:</label></td>
				<td>{!! Form::textarea('complaint','',['class'=>'form-control','id'=>'c','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='d'>Category:</label></td>
				<td>{!! Form::select('categoryID',[""=>"Select"]+$categories,"",['class'=>'form-control','id'=>'d','required'=>1]) !!} </td>
			</tr>
			
			<tr>
				<td class='td_label'><label for='h'>Telephone:</label></td>
				<td>{!! Form::text('complainant_telephone','',['class'=>'form-control','id'=>'h','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='g'>Email:</label></td>
				<td>{!! Form::email('complainant_email','',['class'=>'form-control','id'=>'g','required'=>1]) !!} </td>
			</tr>
			
			<tr><td/><td>{!! MyHTML::submit('Save') !!} {!! MyHTML::submit('Save & Record new complaint','btn btn-primary','create_new') !!} </td></tr>
		</table>

		{!! Form::close() !!}
	</div>
</div>
<script type="text/javascript">
 function chkForm(d){
 	return true;
 }
</script>
@endsection



