@extends('Admin.app')
@section('admin-content')
<link   href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" />
<script src="{{ asset('/js/select2.min.js') }}" type="text/javascript"></script>
<div id="d2" class="panel panel-default">
	<div class="panel-body">
		{!! Session::get('msge') !!}
		{!! Form::open(['enctype'=>'multipart/form-data','url'=>'users/store','id'=>'form_id','onsubmit'=>'return chkForm(this)']) !!}

		<table class='table table-bordered'>
			<tr>
				<td class='td_label' width='20%'><label for='a'>First Name:</label></td>
				<td>{!! Form::text('other_name','',['class'=>'form-control','id'=>'a','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='b'>Family Name:</label></td>
				<td>{!! Form::text('family_name','',['class'=>'form-control','id'=>'b','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='c'>User name:</label></td>
				<td>{!! Form::text('username','',['class'=>'form-control','id'=>'c','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='d'>Password:</label></td>
				<td>{!! Form::password('password',['class'=>'form-control','id'=>'d','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='e'>Confirm Password:</label></td>
				<td>{!! Form::password('confirm_password',['class'=>'form-control','id'=>'e','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='user_r'>User role:</label></td>
				<td>{!! Form::select('type',[""=>""]+$user_roles,"",['id'=>'user_r','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='g'>Email:</label></td>
				<td>{!! Form::email('email','',['class'=>'form-control','id'=>'g','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='h'>Signature:</label>

				</td>
				<td>
					<input type='file' name='signature' value='' onchange="previewImage(this,'sign')"><br>
					<img src="" id='sign' height='80' width='150' alt=''> 
				</td>
			</tr>
			<tr>
				<td class='td_label'><label for='i'>Telephone:</label></td>
				<td>{!! Form::text('telephone','',['class'=>'form-control phone','id'=>'i','required'=>1]) !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='k'>Limit by:</label></td>
				<td >
					{!! Form::radio('limit_by','1','',['onchange'=>'showLimit(1)']) !!} Facility
					{!! Form::radio('limit_by','2','',['onchange'=>'showLimit(2)']) !!} Hub
					{!! Form::radio('limit_by','3','',['onchange'=>'showLimit(3)']) !!} IP
					<br>
					<div class='limitby' id='limit1'>{!! Form::select('facility',[""=>""]+$facilities_arr,"",['id'=>'fclty']) !!}</div>
					<div class='limitby' id='limit2'>{!! Form::select('hub',[""=>""]+$hubs_arr,"",['id'=>'hb']) !!}</div>
					<div class='limitby' id='limit3'>{!! Form::select('ip',[""=>""]+$ips_arr,"",['id'=>'ip']) !!}</div>
				</td>
			</tr>
			<tr><td/><td>{!! MyHTML::submit('Save User') !!} {!! MyHTML::submit('Save & Create new user','btn btn-primary','create_new') !!} </td></tr>
		</table>

		{!! Form::close() !!}
	</div>
</div>
<script type="text/javascript">
 function chkForm(d){
 	if(d.password.value!=d.confirm_password.value){
 		alert('Password mismatch!!');
 		return false;
 	}else{
 		return true;
 	} 	
 }

 function showLimit(val){
 	$(".limitby").attr('style','display:none');
 	document.getElementById('limit'+val).style.display="block";
 }

 $(document).ready(function() {
 	document.getElementById('a').focus();
 	$("#user_r").select2({	placeholder:"Select user role", allowClear:true, width: '40%' });
 	$("#fclty").select2({	placeholder:"Select facility", allowClear:true, width: '40%' });
 	$("#hb").select2({	placeholder:"Select hub", allowClear:true, width: '40%' });
 	$("#ip").select2({	placeholder:"Select IP", allowClear:true, width: '40%' });
 });

 $(".phone").on("change", function() {

 	var formattedPhoneNo = formatPhoneNumber(this.value);

 	if(formattedPhoneNo === ""){
 		alert("Phone Number is NOT valid: Please type it again");
 		this.value = ""; // this.value = this.value.replace(/\D+/g, "");
 		return false;
 	}
 	this.value = "+" + formattedPhoneNo.replace(/([\S\s]{3})/g , "$1 ");
 });
</script>

<style type="text/css">
.limitby{
	display: none;
}
</style>
@endsection



