<!DOCTYPE html>
<html ng-app="MyApp">
<head>
	<title>Angular AJAX Test</title>
</head>
<body ng-controller="TestController">
	From: <input ng-model="from" /><br>
	To: <input name="to" /><br>
	<textarea id="msg" ng-model="contents"></textarea><br>
	<button id="go">POST</button>
	<hr>
	<div id="display" style="overflow: scroll" ignore-ng-repeat="msg in messages">
		{{ from }} --> {{ msg.to }}<br>
		{{ contents }}
	</div>
</body>
<script src="/js/angular.js"></script>
<script type="text/javascript">

	var ThisApp = angular.module("MyApp", []);

		ThisApp.controller('TestController', ['$log', '$scope', '$http', function(log, scope, http) {
			log.info("you have successfully registered TestController.");

			http.get({
		  		url: '/save_sms',
		  		params: {
		    		api_key: 'abc'
		  		}
			});

		}]);

</script>

</html>
<!-- Fix Ctrl+H -->