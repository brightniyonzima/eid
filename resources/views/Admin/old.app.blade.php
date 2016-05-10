<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>EID LIMS</title>
	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/eid.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/jquery.dataTables.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/jquery-ui.css') }}" rel="stylesheet">
	<link   href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('/js/general.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/js/jquery-2.1.3.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/js/jquery-ui.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/js/plugins/bootstrap-select.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/js/plugins/bootstrap-form-buttonset.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/twitter-bootstrap-3.3/js/bootstrap.min.js') }}" type="text/javascript"></script>
	
    <script src="{{ asset('/js/select2.min.js') }}" type="text/javascript"></script>
	<!-- Fonts -->
	<!-- <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'> -->

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar-custom navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/"><span class='glyphicon glyphicon-home'></span> EID LIMS</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li id='l1'>{!! link_to('admin','System Admin') !!}</li>
					<li id='l2'>{!! link_to('user_roles/index', 'Manage User Roles') !!}</li>
					<li id='l3'>{!! link_to('users/index', 'Manage Users') !!}</li>
					<!-- <li id='l4'>{!! link_to('appendix', 'Appendices') !!}</li> -->
					<li id='l4'>{!! link_to('appendices/index/0', 'Appendices') !!}</li>
                    <li id='l5'>{!! link_to('ips/index', 'IPs') !!}</li>
                    <li id='l6'>{!! link_to('facilities/index','Facilities') !!}</li>
                    <li id='l7'>{!! link_to('locations/home','Locations') !!}</li>
                    <!-- <li>{!! link_to('#', 'User Logs') !!}</li> -->
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					
					<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> {{ Auth::user()->username }} <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                            	<li><a href="/user_pwd_change">Change Password</a></li>
                                <li><a href="/logout">Logout</a></li>
                            </ul>
                        </li>
						
				</ul>
			</div>
		</div>
	</nav>

<script type="text/javascript">
$(document).ready(function () {
	
	for (var i = 1; i <= 7; i++) {
		var sect=$("#s"+i);
		if(sect.hasClass("row")){
			var lnk=$("#l"+i);
			if (!lnk.hasClass('active')) {
				lnk.addClass('active');
			}
		}
	}
});
</script>
	@yield('content')

	<!-- Scripts -->
	<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
-->
</body>
</html>
