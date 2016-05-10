@extends('customer_care.container')
@section('content_cc')
<div class="panel panel-default">
	<div class="panel-heading"><b>Complaint from {!! $complaint->facility !!}</b></div>
	<div class="panel-body">
		{!! Session::get('msge') !!}
		<table class='table table-bordered'>
			<tr>
				<td class='td_label' width='20%'><label for='a'>Name of Complainant:</label></td>
				<td>{!! $complaint->complainant !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='b'>Facility:</label></td>
				<td>{!! $complaint->facility !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='c'>Complaint:</label></td>
				<td>{!! $complaint->complaint !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='b'>Category:</label></td>
				<td>{!! $complaint->category !!} </td>
			</tr>
			
			<tr>
				<td class='td_label'><label for='h'>Telephone:</label></td>
				<td>{!! $complaint->complainant_telephone !!} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='g'>Email:</label></td>
				<td>{!! $complaint->complainant_email !!} </td>
			</tr>
		</table>
	</div>
</div>
@endsection



