
@extends('commodities.app')

@section('content')

	<div class="row" id='s4'>
		<div class="col-md-2">	
		 	<ul class="nav nav-pills nav-stacked">
				<li role="presentation" class="">{!! link_to('commodities/stockin/create','Record a stockin') !!}</li>
				<li role="presentation" class="">{!! link_to('commodities/stockin/index','Previous Stockin Entries') !!}</li>
			</ul>
			
		</div>
		<div class="col-md-9">			
			@yield('cmdt_content')
		</div>
	</div>

@endsection
