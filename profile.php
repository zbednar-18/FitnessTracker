<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit();
}

$DATABASE_HOST = "192.168.1.103:8457";
$DATABASE_USER = "admin";
$DATABASE_PASS = "adminp";
$DATABASE_NAME = "phplogin";

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	die('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('s', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Profile Page</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/x-icon" href="favicon.ico">
	<link rel="stylesheet" type="text/css" href="	https://unpkg.com/carbon-components/css/carbon-components.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<link rel="stylesheet" href="css/profile.css" type="text/css">
	<link rel="stylesheet" href="css/monthly.css" type="text/css">
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
	<script src="js/fitnessTracker.js" type="text/javascript"></script>
	<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="https://unpkg.com/carbon-components/scripts/carbon-components.min.js"></script>

</head>

<body class="loggedin">
	<nav class="navtop">
		<div>
			<h1>Fitness Tracker</h1>
			<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
			<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
		</div>
	</nav>
	<div class="content">
		<h2>Profile Page</h2>
		<p>Welcome back, <?= $_SESSION['name'] ?>!</p>
	</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-4">
				<div class="datePickerContainer">
					<div class="bx--form-item">
						<div class="bx--date-picker bx--date-picker--single bx--date-picker--light">
							<div class="innerContainer">
								<div class="bx--date-picker-container">
									<label for="date-picker-3" id="inputHeaders" class="bx--label">Please select a Date</label>
									<input id="date" class="bx--date-picker__input" type="date" value="2019-05-09">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div id="statContainer">

					<div class="bx--form-item bx--text-input-wrapper">
						<div class="innerContainerTwo">
							<label id="inputHeader2" for="text-input-3" class="bx--label">Calculate Your BMI</label>
							<div id="calculateContainer">
								<div class="bx--text-input__field-wrapper">
									<div class="inputTexts">
										<p class="fieldName">Height (feet) </p> <input class="bx--text-input bx--text-input--light" type="text" id="heightFeet"></br>
									</div>
									<div class="inputTexts">
										<p class="fieldName">Height (inches) </p> <input class="bx--text-input bx--text-input--light" type="text" id="heightInch"></br>
									</div>
									<div class="inputTexts">
										<p class="fieldName">Weight (LBs) </p> <input class="bx--text-input bx--text-input--light" type="text" id="weight"></br>
									</div>
								</div>
								<button id="calcBMI" class="bx--btn bx--btn--primary" type="button">
									Calculate BMI
									<svg focusable="false" preserveAspectRatio="xMidYMid meet" style="will-change: transform;" xmlns="http://www.w3.org/2000/svg" class="bx--btn__icon" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true">
										<path d="M3.5 14c-.3 0-.5-.2-.5-.5v-11c0-.2.1-.3.2-.4.2-.1.4-.1.6 0l9.5 5.5c.2.1.3.4.2.7 0 .1-.1.1-.2.2L3.8 14h-.3zM4 3.4v9.3L12 8 4 3.4z"></path>
									</svg>
								</button>
							</div>
							<h5 id="BMI"></h5>
						</div>
					</div>

					<div class="bx--form-item bx--text-input-wrapper">
						<div class="innerContainerTwo">
							<label id="inputHeader2" for="text-input-3" class="bx--label">Calculate Calories Burned During Weight Lifting</label>
							<div id="calculateContainer">
								<div class="bx--text-input__field-wrapper">
									<div class="inputTexts">
										<p class="fieldName">Weight (LBs) </p> <input class="bx--text-input bx--text-input--light" type="text" id="calBurnWeight"></br>
									</div>
									<div class="inputTexts">
										<p class="fieldName">Intensity of Training </p>
										<div class="bx--form-item">

											<div class="bx--form__helper-text"> </div>
											<ul data-dropdown data-value class="bx--dropdown bx--dropdown--light " tabindex="0">
												<li class="bx--dropdown-text">
													Select Training </li>
												<li class="bx--dropdown__arrow-container">
													<svg focusable="false" preserveAspectRatio="xMidYMid meet" style="will-change: transform;" xmlns="http://www.w3.org/2000/svg" class="bx--dropdown__arrow" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true">
														<path d="M8 11L3 6l.7-.7L8 9.6l4.3-4.3.7.7z"></path>
													</svg>
												</li>
												<li>
													<ul class="bx--dropdown-list">
														<li data-option data-value="all" class="bx--dropdown-item">
															<a onclick="getDropdownValue(0)" value="0" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1"></a>
														</li>
														<li data-option data-value="cloudFoundry" class="bx--dropdown-item">
															<a onclick="getDropdownValue(.055)" value=".055" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Body Building</a>
														</li>
														<li data-option data-value="staging" class="bx--dropdown-item">
															<a onclick="getDropdownValue(.042)" value=".042" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Circuit Training</a>
														</li>
														<li data-option data-value="dea" class="bx--dropdown-item">
															<a onclick="getDropdownValue(.039)" value=".039" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Strength Training</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValue(.028)" value=".028" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Light Weight Lifting</a>
														</li>
													</ul>
												</li>
											</ul>

										</div>
									</div>
									<div class="inputTexts">
										<p class="fieldName"></p> Number of minutes you lifted weights (including rest) <input class="bx--text-input bx--text-input--light" type="text" id="minutesLifting"></br>
									</div>
									<button id="calsBurnedBtn" class="bx--btn bx--btn--primary" type="button">
										Calculate Calories Burned
										<svg focusable="false" preserveAspectRatio="xMidYMid meet" style="will-change: transform;" xmlns="http://www.w3.org/2000/svg" class="bx--btn__icon" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true">
											<path d="M3.5 14c-.3 0-.5-.2-.5-.5v-11c0-.2.1-.3.2-.4.2-.1.4-.1.6 0l9.5 5.5c.2.1.3.4.2.7 0 .1-.1.1-.2.2L3.8 14h-.3zM4 3.4v9.3L12 8 4 3.4z"></path>
										</svg>
									</button>
								</div>
							</div>
							<h5 id="calsBurned"></h5>
						</div>
					</div>


					<div class="bx--form-item bx--text-input-wrapper">
						<div class="innerContainerTwo">
							<label id="inputHeader2" for="text-input-3" class="bx--label">Calculate Calories Burned During Aerobic Exercise</label>
							<div id="calculateContainer">
								<div class="bx--text-input__field-wrapper">
									<div class="inputTexts">
										<p class="fieldName">Weight (LBs) </p> <input class="bx--text-input bx--text-input--light" type="text" id="aerobicWeight"></br>
									</div>
									<div class="inputTexts">
										<p class="fieldName">Type of Exercises </p>
										<div class="bx--form-item">

											<div class="bx--form__helper-text"> </div>
											<ul data-dropdown data-value class="bx--dropdown bx--dropdown--light " tabindex="0">
												<li class="bx--dropdown-text">
													Select Exercises </li>
												<li class="bx--dropdown__arrow-container">
													<svg focusable="false" preserveAspectRatio="xMidYMid meet" style="will-change: transform;" xmlns="http://www.w3.org/2000/svg" class="bx--dropdown__arrow" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true">
														<path d="M8 11L3 6l.7-.7L8 9.6l4.3-4.3.7.7z"></path>
													</svg>
												</li>
												<li>
													<ul class="bx--dropdown-list">
														<li data-option data-value="all" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(0)" value="0" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1"></a>
														</li>
														<li data-option data-value="cloudFoundry" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(1)" value="1" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Sitting</a>
														</li>
														<li data-option data-value="staging" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(4)" value="4" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Bicycling ( < 10 mph, general Leisure )</a> </li> <li data-option data-value="dea" class="bx--dropdown-item">
																	<a onclick="getDropdownValueTwo(6)" value="6" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Bicycling ( 10 - 11.9 mph )</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(8)" value="8" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Bicycling ( 12 - 13.9 mph )</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(10)" value="10" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Bicycling ( 14 - 15.9 mph )</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(12)" value="12" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Bicycling ( 16 - 19 mph )</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(16)" value="16" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Bicycling ( > 20 mph, racing ) </a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(3)" value="8" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Stationary Bike, Very Low Effort </a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(5.5)" value="5.5" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Stationary Bike, Low Effort</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(7)" value="7" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Stationary Bike, Moderate Effort</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(10.5)" value="10.5" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Stationary Bike, Vigorous Effort</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(12.5)" value="12.5" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Stationary Bike, Very Vigorous Effort</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(4.5)" value="4.5" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Calisthenics, Light / Moderate Effort</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(8)" value="8" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Calisthenics, Vigorous Effort ( pushup, pullups, situps etc. )</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(8)" value="8" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Running, 12 min mile</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(9)" value="9" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Running, 11.5 min mile</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(11)" value="11" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Running, 10 min mile</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(11.5)" value="11.5" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Running, 9 min mil</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(12.5)" value="12.5" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Running, 8 min mile</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(14)" value="14" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Running, 7 min mile</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(15)" value="15" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Running, 6.5 min mile</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(16)" value="16" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Running, 6 min mile</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(18)" value="18" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Running, 5.5 min mile</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(16)" value="16" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Running Stairs</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(6)" value="6" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Swimming Leisurely</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(8)" value="8" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Swimming, Backstroke Laps</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(10)" value="10" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Swimming, Breaststroke Laps</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(11)" value="11" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Swimming, Butterfly laps</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(2.5)" value="2.5" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Walking, Slow Pace</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(3.5)" value="3.5" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Walking, Moderate Pace</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(4)" value="4" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Walking, Brisk Pace</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(4.5)" value="4.5" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Walking, Very Brisk Pace</a>
														</li>
														<li data-option data-value="router" class="bx--dropdown-item">
															<a onclick="getDropdownValueTwo(6.5)" value="6.5" class="bx--dropdown-link" href="javascript:void(0)" tabindex="-1">Walking, Race Walking, > 4.5 mph</a>
														</li>
													</ul>
												</li>
											</ul>
										</div>
										<div class="inputTexts">
											<p class="fieldName">Number of minutes you exercised: </p> <input class="bx--text-input bx--text-input--light" type="text" id="aerobicMinutes"></br>
										</div>
									</div>
								</div>
								<button id="aerobicCalsBurnedBtn" class="bx--btn bx--btn--primary" type="button">
									Calculate Calories Burned
									<svg focusable="false" preserveAspectRatio="xMidYMid meet" style="will-change: transform;" xmlns="http://www.w3.org/2000/svg" class="bx--btn__icon" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true">
										<path d="M3.5 14c-.3 0-.5-.2-.5-.5v-11c0-.2.1-.3.2-.4.2-.1.4-.1.6 0l9.5 5.5c.2.1.3.4.2.7 0 .1-.1.1-.2.2L3.8 14h-.3zM4 3.4v9.3L12 8 4 3.4z"></path>
									</svg>
								</button>
							</div>
							<h5 id="aerobicCalsBurned"></h5>
						</div>
					</div>
				</div>

				<button id="addToCalendar" class="bx--btn bx--btn--primary" type="button">
					Add stats to calendar
					<svg focusable="false" preserveAspectRatio="xMidYMid meet" style="will-change: transform;" xmlns="http://www.w3.org/2000/svg" class="bx--btn__icon" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true">
						<path d="M3.5 14c-.3 0-.5-.2-.5-.5v-11c0-.2.1-.3.2-.4.2-.1.4-.1.6 0l9.5 5.5c.2.1.3.4.2.7 0 .1-.1.1-.2.2L3.8 14h-.3zM4 3.4v9.3L12 8 4 3.4z"></path>
					</svg>
				</button>
			</div>
		</div>
	</div>

	<div class="page">
		<div id="chartAndCalendar">
			<div id ="calendar">
				<div class="monthly" id="mycalendar"></div>
			</div>
			<div id="chart">
				<canvas id="myChart" style="display: block; height: 200px; width: 200px;" width="500" height="50%"></canvas>
			</div>
		</div>
	</div>
	<!-- JS ======================================================= -->
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/monthly.js"></script>
	<script type="text/javascript">
		$(window).load(function() {

			$('#mycalendar').monthly({
				mode: 'event',
				jsonUrl: 'events.json',
				dataType: 'json'
				//xmlUrl: 'events.xml'
			});

			$('#mycalendar2').monthly({
				mode: 'picker',
				target: '#mytarget',
				setWidth: '250px',
				startHidden: true,
				showTrigger: '#mytarget',
				stylePast: true,
				disablePast: true
			});

			switch (window.location.protocol) {
				case 'http:':
				case 'https:':
					// running on a server, should be good.
					break;
				case 'file:':
					//alert('Just a heads-up, events will not work when run locally.');
			}

		});
	</script>

</body>

</html>