@extends('commodities.app')
@section('cm-content')
<div id='d5' class="panel panel-default">
	<div class="panel-body">
		{!! link_to('commodities/requisitions/pending_reqs','View pending requisitions',['class'=>'btn btn-primary btn-side']) !!}
		<br><br>
		@if(is_object($requisition))
		{!! Session::get('msge') !!}
		{!! Form::open(array('url'=>'commodities/requisitions/post_approve/'.$id,'id'=>'form_id')) !!}
		<?php
		$cmdty=EID\Models\Commodities\Commodity::findOrFail($requisition->commodityID);
		$cmdty_bal=$cmdty->getCurrentQuantity($cmdty->id);
		$cmdty_bal.=" units of $cmdty->commodity left";
		?>
		<table class='table borderless'>
			<tr>
				<td class='td_label' width='20%'><label >Commodity:</label></td>
				<td>{{ $requisition->commodity }} </td>
			</tr>
			<tr>
				<td class='td_label'><label >Quantity requisitioned:</label></td>
				<td>{{ $requisition->quantity_requisitioned }} </td>
			</tr>
			<tr>
				<td class='td_label'><label >Date of Requisition:</label></td>
				<td>{{ MyHTML::localiseDate($requisition->created) }} </td>
			</tr>
			<tr>
				<td class='td_label'><label >Requisitioned by:</label></td>
				<td>{{ $requisition->createdby }} </td>
			</tr>
			<tr>
				<td class='td_label'><label for='a'>Quantity approved:</label></td>
				<td>
					{!! MyHTML::text('quantity_approved',$requisition->quantity_requisitioned,'form-control','a') !!} 
					<label class='status_highlite'>{!! $cmdty_bal !!}</label>
				</td>
			</tr>
			<tr>
				<td class='td_label'><label for='a'>Comments:</label></td>
				<td>{!! MyHTML::text('comments','','form-control','a') !!} </td>
			</tr>
			<tr><td/><td>{!! MyHTML::submit('Approve') !!} </td></tr>
						

			<!-- <tr><td/><td>{!! MyHTML::link_to("commodities/requis/edit/".$requisition->id,"Edit","btn btn-primary") !!} </td></tr> -->
		</table>
		{!! Form::close() !!}
		@endif
	</div>
</div>

@endsection

