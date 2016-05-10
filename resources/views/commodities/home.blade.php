@extends('commodities.app')
@section('cm-content')

<?php
$links=[];
$links[]=['url'=>'/commodities/categories','lbl'=>'Commodity Categories','icon'=>'th-large'];
$links[]=['url'=>'/commodities/commodities/index','lbl'=>'Commodities','icon'=>'th'];
$links[]=['url'=>'/commodities/stockin/create','lbl'=>'Stockin','icon'=>'shopping-cart'];
$links[]=['url'=>'/commodities/requisitions/create','lbl'=>'In house Requisitions','icon'=>'list'];
$links[]=['url'=>'/commodities/facility_reqs/create','lbl'=>'Facility Orders','icon'=>'list-alt'];
$links[]=['url'=>'/commodities/stock_status/balances','lbl'=>'Stock Status','icon'=>'eye-open'];
//$links[]=['url'=>'#','lbl'=>'View User Logs','icon'=>'eye-open'];
?>

<div id='d1' class="panel panel-default">
	<div class="panel-heading">Commodity Management</div>
	<div class="list-group">
		@foreach ($links as $link)
		<a href="{!! $link['url'] !!}" class="list-group-item"><span class="blue-icon-md glyphicon glyphicon-{!! $link['icon'] !!}"></span> {!! $link['lbl'] !!} </a>
		@endforeach
	</div>
</div>
@endsection