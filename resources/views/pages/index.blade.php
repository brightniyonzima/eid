@extends('layouts/layout')

@section('content')
<div class="starter-template">
    <h1>{{ Auth::check() ? "Welcome, " . Auth::user()->username : "Welcome to the EID database" }}</h1>
<br>
<div class='row'>
	@if(session('is_admin')==1)<div class='col-lg-2'><div class="home-links home" onclick="go('admin')"><br><span style="font-size:55px" class="glyphicon glyphicon-cog"></span><br><br>SYSTEM ADMIN</div></div>	
	<div class='col-lg-1'></div>@endif
	@if(MyHTML::permit(11))<div class='col-lg-2'><div class="home-links home" onclick="go('samples')"><br><span style="font-size:55px" class="glyphicon glyphicon-plus-sign"></span><br><br>ADD BATCH</div></div>
	<div class='col-lg-1'></div>@endif	
	@if(MyHTML::permit(12))<div class='col-lg-2'><div class="home-links home" onclick="go('batches')"><br><span class="glyphicon glyphicon-folder-open home-glyphicon"></span><br><br>BATCHES</div></div>@endif	
</div>
<br>
<br>

<div class='row'>
	@if(MyHTML::permit(14))<div class='col-lg-2'><div class="home-links eid" onclick="go('batchQ')"><br><img src="{{ asset('images/check-circle-outline.png') }}"><br><br>APPROVALS</div></div>
	<div class='col-lg-1'></div>@endif
	@if(MyHTML::permit(2))<div class='col-lg-2'><div class="home-links eid" onclick="go('wlist')"><br><img src="{{ asset('images/drive-form.png') }}"><br><br>WORKSHEETS</div></div>	
	<div class='col-lg-1'></div>@endif
	@if(MyHTML::permit(3))<div class='col-lg-2'><div class="home-links eid" onclick="go('dispatch')"><br><img src="{{ asset('images/file-upload.png') }}"><br><br>DISPATCH</div></div>@endif	
</div>

<br>
<br>

<div class='row'>
	@if(MyHTML::permit(4))<div class='col-lg-2'><div class="home-links admin" onclick="go('follow')"><br><span style="font-size:55px" class="glyphicon glyphicon-share"></span><br><br>FOLLOW UP</div></div>	
	<div class='col-lg-1'></div>@endif
	@if(MyHTML::permit(6))<div class='col-lg-2'><div class="home-links admin" onclick="go('customer_care/complaints/index')"><br><span style="font-size:55px" class="glyphicon glyphicon-earphone"></span><br><br>CUSTOMER CARE</div></div>
	<div class='col-lg-1'></div>@endif
	@if(MyHTML::permit(5))<div class='col-lg-2'><div class="home-links admin" onclick="go('commodities/home')"><br><img src="{{ asset('images/storage.png') }}"><br><br>COMMODITIES MANAG'T</div></div>@endif	
	
</div>

<br>
<br>

<div class='row'>
	<!-- <div class='col-md-2'><a class='dsktp' href="#"><img height='100' width='100' src="{{ asset('images/icon.reports.dashboard.gif') }}"><br>Reports</a></div> -->
</div>

</div>

<script type="text/javascript">
function go(url){
 return window.location.assign(url);
}
</script>
@stop

