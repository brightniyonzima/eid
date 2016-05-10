<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Laravel CRUD App</title>
	<link href="/twitter-bootstrap-3.3/css/bootstrap.min.css" rel="stylesheet">
	<link href="/twitter-bootstrap-3.3/css/bootswatch-flatly.min.css" rel="stylesheet">

	<style>
		body {
			padding-top: 70px;
		}
	</style>
</head>
<body>


	<nav class="navbar navbar-default navbar-fixed-top">
	    <div class="container">
	        <!-- Brand and toggle get grouped for better mobile display -->
	        <div class="navbar-header">
	            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
	                <span class="sr-only">Toggle navigation</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>
	            <a class="navbar-brand" href="/">Commodity Management</a>
	        </div>

			<div class="collapse navbar-collapse" id="navbar-collapse-1">
	            <ul class="nav navbar-nav">
	                <li class="dropdown">
	                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> Menu <span class="caret"></span></a>
	                    <ul class="dropdown-menu" role="menu">
							<li><a  href="/stock_status">Stock Status</a></li>
							<li><a  href="/stock_requisition_header">Orders (Facility)</a></li>
							<li><a  href="/stock_requisition_header?i=1">Orders (In House)</a></li>
							<li><a  href="/receivestock">Delivery Log (IN)</a></li>
							<li><a  href="/stock_requisition_header?out=1">Delivery Log (OUT)</a></li>
							<li><a  href="/stock_settings">Settings</a></li>
	                    </ul>
	                </li>
	            </ul>





			</div>

	    </div><!-- /.container-fluid -->
	</nav>

	<!-- Scripts -->
	<script src="/js/jquery-1.10.2.min.js"></script>
	<script src="/twitter-bootstrap-3.3/js/bootstrap.min.js"></script>

	<div class="container">
		@yield('content')
	</div>


</body>
</html>