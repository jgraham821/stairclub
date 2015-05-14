<!DOCTYPE html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<style>
			h1, h2, h3 {
				text-align: center;
			}

			label {
				margin-right: 5px;
			}

		</style>

		<script type="text/javascript"
          src="https://www.google.com/jsapi?autoload={
            'modules':[{
              'name':'visualization',
              'version':'1',
              'packages':['corechart']
            }]
          }"></script>

	</head>
	<body ng-app="myApp">
		<div class="container">
			<div id="top" style="margin-left:auto; margin-right:auto; width:700px;">
				<span style="margin-left:50px;">
					<img src="https://www.gsn.com/dynamic/images/skin/template/gsn_logo.jpg" />
					<h1 style="display:inline; margin-left:50px;">GSN Stair Club!</h1>
					<image src="<?php echo BASE_URL ?>/images/stairs.png" alt="GSN Stair Club" style="position: relative; left: -25px; z-index:-1;"/>
				</span>
			</div>
			<div ng-controller="resultsCtrl" style="margin-left:auto; margin-right:auto; width:850px;" >
				<div style="float:left">
					<h3>Top 5 Times</h3>
					<table style="width: 400px;" class="table table-bordered table-hover table-striped text-center">
						<tr>
							<td><strong>Username</strong></td>
							<td><strong>Time</strong></td>
							<td><strong>Date</strong></td>
						</tr>
						<tr ng-repeat="result in topTimes">
							<td>{{result.username}}</td>
							<td>{{ result.time | convertSeconds }}</td>
							<td>{{ result.date }}</td>
						</tr>
					</table>
				</div>

				<div style="float:left; margin-left:50px;">
					<h3>Top 5 Climbers</h3>
					<table style="width: 400px;" class="table table-bordered table-hover table-striped text-center">
						<tr>
							<td><strong>Username</strong></td>
							<td><strong>Climbs</strong></td>
						</tr>

						<tr ng-repeat="climber in topClimbers">
							<td>{{climber.username}}</td>
							<td>{{ climber.total }}</td>
						</tr>
					</table>
				</div>
			</div>

			<br>

			<div ng-controller="submitCtrl" style="clear: both; margin-left: auto; margin-right: auto; width: 750; padding-top:50px;">
				<h3 style="margin-left:auto; margin-right:auto; width:250px;">Submit A Time</h3>
				
				<form class="form-inline" style="margin-left:auto; margin-right:auto; width:755px;">
					<label for="username">Username: </label><input type="text" ng-model="username" class="form-control">

					<label for="minutes">Mins: </label><input type="text" ng-model="minutes" class="form-control">

					<label for="seconds">Sec: </label><input type="text" ng-model="seconds" class="form-control">
					
					<button ng-click="submit()" class="btn btn-primary">Submit!</button><br>
				</form>
			</div>

			<div ng-controller="lookupCtrl" style="clear: both; margin-left: auto; margin-right: auto; width: 700px; padding-top:50px;">
				<h3>Lookup Times</h3>
				
				<form class="form-inline" class="form-inline" style="margin-left:auto; margin-right:auto; width:755px;">
					<span style="margin-left:195px;"><label for="username">Username: </label><input type="text" ng-model="username" class="form-control"><span>

					<button ng-click="submit()" class="btn btn-default">Lookup!</button><br>

					<p><span ng-show="submitted && !submitSuccess">Submit Failed :( Sorry, please contact the Stair Club admins about this issue.</span>
				</form>
			</div>

		</div>

		<div id="curve_chart" style="margin-left: auto; margin-right: auto; width: 900px; height: 500px"></div>

		<div style="margin-left: auto; margin-right: auto; width:480px; margin-top: 50px; margin-bottom: 50px;">
			<image src="<?php echo BASE_URL ?>/images/modes.jpg" alt="GSN Stair Club Modes">
		</div>

		<script>


		angular.module('stairclubFilters', []).filter('convertSeconds', function() {
		  return function(input) {
		  	var minutes = Math.floor(input/60);
		    var seconds = Math.floor(input % 60);
		    var result = "";
		    
		    if (minutes > 0) {
		    	result += minutes + "m";
		    }

		    if (seconds > 0) {
		    	if (minutes > 0) {
		    		result += " ";
		    	}
		    	result += seconds + "s";
		    }
		    return result;
		  };
		});

		var app = angular.module('myApp', ['stairclubFilters']);

		app.controller('resultsCtrl', function($scope, $http) {
		    $http.get("<?php echo BASE_URL ?>/times?order_by=time&order=ASC&limit=5")
    		.success(function(response) {
    			$scope.topTimes = response;
    		});

    		$http.get("<?php echo BASE_URL ?>/times/top")
    		.success(function(response) {
    			$scope.topClimbers = response;
    		});
		});

		app.controller('submitCtrl', function($scope, $http) {
		    $scope.submit = function() {
				$scope.submitted = false;
				$scope.time = (parseInt($scope.minutes) * 60) + parseInt($scope.seconds);
		    	var url = "<?php echo BASE_URL ?>/users/" + $scope.username + "/times";
				var data = '{"time":' + $scope.time + '}';

		    	$http.post(url, data)
    			.success(function(response) {
		    		$scope.submitted = true;
		    		$scope.submitSuccess = true;
		    		location.reload();
    			})
    			.error(function(data, status, headers, config) {
    				$scope.submitted = true;
		    		$scope.submitSuccess = false;
  				});
		    };
		});

		app.controller('lookupCtrl', function($scope, $http) {
			$scope.submit = function() { 
				$scope.submitted = false;
		    	var url = "<?php echo BASE_URL ?>/users/" + $scope.username + "/times";
		    	$http.get(url)
	    		.success(function(resultArray) {
					var data = [["Date", "Time (min)"]];
					for (i = 0; i < resultArray.length; i++) {
			      		var timeResult = resultArray[i];
			      		data[i + 1] = [timeResult.date, parseInt(timeResult.time) / 60];
			      	}

					// Draw the chart with that data.
					var data = google.visualization.arrayToDataTable(data);

					var options = {
					  title: 'Stair Climb Times for ' + $scope.username,
					  curveType: 'function',
					  legend: { position: 'bottom' }
					};

					var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

					chart.draw(data, options);
	    		});
			};
		});

		</script>
	</body>
</html>