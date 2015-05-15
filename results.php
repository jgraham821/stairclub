<!DOCTYPE html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

		<style>
			h1, h2, h3 {
				text-align: center;
			}

			label {
				margin-right: 5px;
			}

			.nav-tabs > li, .nav-pills > li {
			    float:none;
			    display:inline-block;
			    *display:inline; /* ie7 fix */
			     zoom:1; /* hasLayout ie7 trigger */
			}

			.nav-tabs, .nav-pills {
			    text-align:center;
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

			<div>
			    <ul class="nav nav-tabs">
			        <li class="active"><a data-toggle="tab" href="#results">Results</a></li>
			        <li><a data-toggle="tab" href="#instructions">Instructions</a></li>
			        <li><a data-toggle="tab" href="#faq">FAQ</a></li>
			        <li><a data-toggle="tab" href="#modes">Modes</a></li>
			    </ul>
			    <div class="tab-content">
			        <div id="results" class="tab-pane fade in active">
			        	<h3>Results</h3>
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

						<div id="curve_chart" style="margin-left: auto; margin-right: auto; width: 900px; height: 500px"></div>
			        </div>

			        <div id="instructions" class="tab-pane fade">
			            <h3>Instructions</h3>
			            <div style="margin-left:auto; margin-right:auto; width:450px;">
				            <p><strong>Finding the stairs:</strong></p>
							<ul>
								<li>Take the elevator to the second floor.</li>
								<li>Get off, head toward the Green Line Extension Project office.</li>
								<li>Take hall to the right.</li>
								<li>Go through the white door with the Exit sign above it.</li>
								<li>On the 11th floor, you'll need to scan your card to get out of the stairwell.</li>
							</ul>

							<p><strong>Timing rules:</strong></p>
							<ul>
								<li>Get the app ready to go at the bottom of the stairs.</li>
								<li>Both feet stay on the ground.</li>
								<li>Take the first step when the timer starts.</li>
								<li>Go through the white door with the Exit sign above it.</li>
								<li>Stop the timer or scan the finish line tag when BOTH feet are on the top step.</li>
							</ul>
						</div>
			        </div>
			        <div id="faq" class="tab-pane fade">
			            <h3>Frequently Asked Questions</h3>
			           	<div style="margin-left:auto; margin-right:auto; width:450px;">

							<p><strong>Q:</strong> Can I wear a bag? Do I have to wear a bag?</p>
							<p><strong>A:</strong>: Up to you. Most people submit times on their way into work, so they're carrying a backpack full of electronics. If enough people ask, we might add a With Bag/Without Bag division.</p>

							<br />

							<p><strong>Q:</strong>: How do you make it all the way up? It's, like, really far!</p>
							<p><strong>A:</strong>: It sure is. You totally don't have to run up. Slow and steady gets you there without dying. Find a pace that works, and try and keep it up for the whole trip. You don't have to be the fastest, just faster than your last time.</p>

							<br />

							<p><strong>Q:</strong>: Wait, there's no way that time is legit! Someone's cheating!</p>
							<p><strong>A:</strong>: That wasn't actually a question, but I get it. If you suspect shenanigans with the leaderboard, find the potential culprit, and challenge them to do it again (or at least come close). But really, this whole thing is for fun and on the honor system, so hopefully nobody's a cheating cheater. But if there's definitely a score that needs to come down, email Jason Graham, Jonathan Rubinger, or Chuck Alessi, and an electronic beatdown will be initiated (by removing the bad score).</p>
						</div>
			        </div>
			        <div id="modes" class="tab-pane fade">
			            <h3>Modes</h3>
			            	<div style="margin-left: auto; margin-right: auto; width:480px; margin-top:20px;">
								<image src="<?php echo BASE_URL ?>/images/modes.jpg" alt="GSN Stair Club Modes">
							</div>
			        </div>
			    </div>
			</div>
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