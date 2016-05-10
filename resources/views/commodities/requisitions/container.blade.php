
@extends('commodities.app')

@section('content')

	<div class="row" id='s5'>
		<div class="col-md-2">	
		 	<ul class="nav nav-pills nav-stacked">
				<li role="presentation" class="">{!! link_to('commodities/requisitions/create','Make a Requisition') !!}</li>
				<li role="presentation" class="">{!! link_to('commodities/requisitions/pending_reqs','Approve Requisitions') !!}</li>
			</ul>
			
		</div>
		<div class="col-md-9">			
			@yield('cmdt_content')
		</div>
	</div>

@endsection
