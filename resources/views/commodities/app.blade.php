@extends('layouts/layout')

@section('content2')
<div id='s7' class="row mm" >
	<div class='col-md-2'>
		<ul class="nav nav-pills nav-stacked">
			<li id='r1' role="presentation">{!! link_to('commodities/home',"Commodity Manag't") !!}</li>
			<li id='r2' role="presentation">{!! link_to('commodities/categories', 'Commodity Categories') !!}</li>
			<li id='r3' role="presentation">{!! link_to('commodities/commodities/index', 'Commodities') !!}</li>
			<li id='r4' role="presentation">{!! link_to('commodities/stockin/create', 'Stock in') !!}</li>
			<li id='r5' role="presentation">{!! link_to('commodities/requisitions/create', 'In house Requisitions') !!}</li>
			<li id='r6' role="presentation">{!! link_to('commodities/facility_reqs/create', 'Facility Orders') !!}</li>
			<li id='r7' role="presentation">{!! link_to('commodities/stock_status/balances', 'Stock Status') !!}</li>
		</ul>
	</div>
	<div class='col-md-9'>
		@yield('cm-content')
	</div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    
    for (var i = 1; i <= 7; i++) {
        var sect=$("#d"+i);
        if(sect.hasClass("panel")){
            var lnk=$("#r"+i);
            if (!lnk.hasClass('active')) {
                lnk.addClass('active');
            }
        }
    }
});
</script>
@endsection