
@extends('layouts/layout')
@section('content2')
	<div id='s6' class="row mm">
		<div class="col-md-2">	
		 	<ul class="nav nav-pills nav-stacked">
				<li id='r1' role="presentation" class="">{!! link_to('customer_care/categories','Categories') !!}</li>
				<li id='r2' role="presentation" class="">{!! link_to('customer_care/complaints/create','Add new complaint') !!}</li>
				<!-- <li role="presentation" class="">{!! link_to('customer_care/complaints/pending','Pending complaints') !!}</li> -->
				<!-- <li role="presentation" class="">{!! link_to('customer_care/complaints/resolved','Resolved complaints') !!}</li> -->
				<li id='r3' role="presentation" class="">{!! link_to('customer_care/complaints/index','Complaints') !!}</li>
			</ul>
			
		</div>
		<div class="col-md-9">			
			@yield('content_cc')
		</div>
	</div>
<script type="text/javascript">
$(document).ready(function () {
    
    for (var i = 1; i <= 3; i++) {
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
