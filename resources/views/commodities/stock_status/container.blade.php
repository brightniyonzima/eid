
@extends('commodities.app')

@section('content')
<?php 
use EID\Models\Commodities\Commodity;
$low_cts=count(Commodity::getLowQuantCommodities());
$all_cts=Commodity::count();
$hi_cts=$all_cts-$low_cts;
?>

	<div class="row" id='s7'>
		<div class="col-md-3">	
		 	<ul class="nav nav-pills nav-stacked">
				<li role="presentation" class="">
					<a href="/commodities/stock_status/balances">
						Balances
						<sup class='status_danger'>{!! $low_cts !!}</sup>
						<sup class='status_ok'>{!! $hi_cts !!}</sup>
					</a>
				</li>
				<li role="presentation" class="">{!! link_to('commodities/stock_status/expiry_date_track','Expiry Date Track') !!}</li>
			</ul>
			
		</div>
		<div class="col-md-8">			
			@yield('cmdt_content')
		</div>
	</div>

@endsection
