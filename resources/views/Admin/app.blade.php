@extends('layouts/layout')

@section('content2')

<?php
$links=[];
$links[]=['url'=>'/user_roles/index','lbl'=>'Manage User Roles','id'=>'r1'];
$links[]=['url'=>'/users/index','lbl'=>'Manage Users','id'=>'r2'];
$links[]=['url'=>'/appendices/index/1','lbl'=>'Manage Appendices','id'=>'r3'];
$links[]=['url'=>'/ips/index','lbl'=>'Manage IPs','id'=>'r4'];
$links[]=['url'=>'/facilities/index','lbl'=>'Manage Facilities','id'=>'r5'];
$links[]=['url'=>'/locations/regions','lbl'=>'Manage Regions','id'=>'r6'];
$links[]=['url'=>'/locations/districts','lbl'=>'Manage Districts','id'=>'r7'];
$links[]=['url'=>'/locations/hubs','lbl'=>'Manage Hubs','id'=>'r8'];
//$links[]=['url'=>'#','lbl'=>'View User Logs','icon'=>'eye-open'];
?>
<div id='s8' class="row mm" >
	<div class='col-md-2'>
		<ul class="nav nav-pills nav-stacked nav-stacked-info">
			@foreach ($links as $link)
			<li id="{!! $link['id'] !!}" role="presentation" class="">{!! link_to($link['url'],$link['lbl']) !!}</li>
			@endforeach
		</ul>
	</div>
	<div class='col-md-9'>
		@yield('admin-content')
	</div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    
    for (var i = 1; i <= 8; i++) {
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