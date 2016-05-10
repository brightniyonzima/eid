@extends('customer_care.container')
@section('content_cc')
<div class="panel panel-default">
	<div class="panel-heading"><b>Edit User</b></div>
	<div class="panel-body">
		{!! Session::get('msge') !!}
		{!! Form::open(['url'=>'users/update/'.$id,'id'=>'form_id','onsubmit'=>'return chkForm(this)']) !!}

		<table class='table table-bordered'>
			<tr>
				<td class='td_label' width='20%'><label for='a'>First Name:</label></td>
				<td>{!! Form::text('other_name',$user->other_name,['class'=>'form-control','id'=>'a','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='b'>Family Name:</label></td>
				<td>{!! Form::text('family_name',$user->family_name,['class'=>'form-control','id'=>'b','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='c'>User name:</label></td>
				<td>{!! Form::text('username',$user->username,['class'=>'form-control','id'=>'c','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='f'>User role:</label></td>
				<td>{!! Form::select('type',[""=>"Select"]+$user_roles,$user->type,['class'=>'form-control','id'=>'f','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='g'>Email:</label></td>
				<td>{!! Form::email('email',$user->email,['class'=>'form-control','id'=>'g','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='h'>Telephone:</label></td>
				<td>{!! Form::text('telephone',$user->telephone,['class'=>'form-control','id'=>'h','required'=>1]) !!} </td>
			</tr>
			<tr><td/><td>{!! MyHTML::submit('Save') !!} </td></tr>
		</table>

		{!! Form::close() !!}
	</div>
</div>
<script type="text/javascript">
 
</script>
@endsection



