<!DOCTYPE html>
<html ng-app>
<head>
	<title>Angular AJAX Test</title>
</head>
<body ng-controller="TestController">
	From: <input name="from" /><br>
	To: <input name="to" /><br>
	<textarea id="msg"></textarea><br>
	<button id="go">POST</button>
	<hr>
	<div id="display" style="overflow: scroll" ng-repeat="msg in messages">
		{{ }}
	</div>
</body>
<script type="text/javascript"></script>

</html>
<script src="/js/angular.js"></script>
<!-- Fix Ctrl+H -->