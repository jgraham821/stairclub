<!DOCTYPE html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<style>
			td {
				padding: 5px;
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
				<img src="https://www.gsn.com/dynamic/images/skin/template/gsn_logo.jpg" />
				<h1 style="display:inline; margin-left:50px;">GSN Stair Club!</h1>
				<image src="<?php echo BASE_URL ?>/images/stairs.png" alt="GSN Stair Club"/>
			</div>
			<div ng-controller="resultsCtrl" style="margin-left:auto; margin-right:auto; width:550px;" >
				<div style="float:left">
					<h2>Top 5 Times</h2>
					<table>
						<thead>
							<tr>
								<td>Username</td>
								<td>Time</td>
								<td>Date</td>
							</tr>
						</thead>

						<tr ng-repeat="result in topTimes">
							<td>{{result.username}}</td>
							<td>{{ result.time | convertSeconds }}</td>
							<td>{{ result.date }}</td>
						</tr>
					</table>
				</div>

				<div style="float:left; margin-left:50px;">
					<h2>Top 5 Climbers</h2>
					<table>
						<thead>
							<tr>
								<td>Username</td>
								<td>Climbs</td>
							</tr>
						</thead>

						<tr ng-repeat="climber in topClimbers">
							<td>{{climber.username}}</td>
							<td>{{ climber.total }}</td>
						</tr>
					</table>
				</div>
			</div>

			<br>

			<div ng-controller="submitCtrl" style="clear: both; margin-left: auto; margin-right: auto; width: 50%; padding-top:50px;">
				<h2 style="margin-left:auto; margin-right:auto; width:250px;">Submit A Time</h2>
				
				Username <input type="text" ng-model="username">

				Time <input type="text" ng-model="time">
				
				<button ng-click="submit()">Submit!</button><br>
				
				<p><span ng-show="submitted && submitSuccess">Success</span><span ng-show="submitted && !submitSuccess">Failed</span>
			</div>

			<div ng-controller="lookupCtrl" style="clear: both; margin-left: auto; margin-right: auto; width: 50%; padding-top:50px;">
				<h2 style="margin-left:auto; margin-right:auto; width:250px;">Lookup Times</h2>
				
				Username <input type="text" ng-model="username">
			
				<button ng-click="submit()">Submit!</button><br>
				
				<p><span ng-show="submitted && submitSuccess">Success</span><span ng-show="submitted && !submitSuccess">Failed</span>
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
		    var seconds = input % 60;
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