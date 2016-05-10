
@extends('commodities.app')
@section('content')
<div class="panel panel-default">
	<div class="panel-heading"><b></b></div>
	<div class="panel-body">
		{!! Session::get('msge') !!}
		
		<table class='table table-striped table table-condensed' id='tab_id'>
			<tr>
				<th>Item</th>
				<th>Value</th>
				<th></th>
			</tr>			
			@foreach ($items AS $item)
			<tr>
				
				<td>{!! $item->item !!}</td>
				<td>{!! $item->value !!}</td>
				<?php echo "<td>".link_to('commodities/config_edit/'.$item->id,'Edit')."</td>" ?>
			</tr>
			@endforeach
		</table>
	</div>
</div>	

@endsection