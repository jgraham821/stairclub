<!DOCTYPE html>
	<head>
		<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	</head>
	<body ng-app="myApp">
		<div class="container">
			<h1>GSN Stair Club!</h1>

			<div ng-controller="resultsCtrl">
				<h2>Latest Times</h2>
				<ul>
						<li ng-repeat="result in results">
							{{result.username}} - {{ result.time | convertSeconds }} on {{ result.date }}
						</li>
				</ul>
			</div>

			<div ng-controller="submitCtrl">
				<h2>Submit A Time</h2>
				Username <input type="text" ng-model="username"><br>
				Time <input type="text" ng-model="time"><br>
				<button ng-click="submit()">Submit!</button>
				<br>
				<p><span ng-show="submitted && submitSuccess">Success</span><span ng-show="submitted && !submitSuccess">Failed</span>
			</div>
		</div>

		<script>


		angular.module('stairclubFilters', []).filter('convertSeconds', function() {
		  return function(input) {
		  	var minutes = Math.floor(input/60);
		    var seconds = input % 60;
		    var result = "";
		    
		    if (minutes > 0) {
		    	result += minutes + " minutes";
		    }

		    if (seconds > 0) {
		    	if (minutes > 0) {
		    		result += " ";
		    	}
		    	result += seconds + " seconds";
		    }
		    return result;
		  };
		});

		var app = angular.module('myApp', ['stairclubFilters']);

		app.controller('resultsCtrl', function($scope, $http) {
		    $http.get("http://localhost/~jgraham/times")
    		.success(function(response) {
    			$scope.results = response;
    		});
		});

		app.controller('submitCtrl', function($scope, $http) {
		    $scope.submit = function() {

				$scope.submitted = false;

		    	var url = "http://localhost/~jgraham/users/" + $scope.username + "/times";
				var data = '{"time":' + $scope.time + '}';

		    	$http.post(url, data)
    			.success(function(response) {
		    		$scope.submitted = true;
		    		$scope.submitSuccess = true;
    			})
    			.error(function(data, status, headers, config) {
    				$scope.submitted = true;
		    		$scope.submitSuccess = false;
  				});
		    };
		});

		</script>
	</body>
</html>