
@extends('commodities.app')

@section('content')

	<div class="row" id='s3'>
		<div class="col-md-2">	
		 	<ul class="nav nav-pills nav-stacked">
				<li role="presentation" class="">{!! link_to('commodities/commodities/create','Create New Commodity') !!}</li>
				<li role="presentation" class="">{!! link_to('commodities/commodities/index','List of Commodities') !!}</li>
			</ul>
			
		</div>
		<div class="col-md-9">			
			@yield('cmdt_content')
		</div>
	</div>

@endsection
