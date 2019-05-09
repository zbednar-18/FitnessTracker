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
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
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

		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link rel="stylesheet" href="css/profile.css" type= "text/css">
		<link rel="stylesheet" href="css/monthly.css" type= "text/css">

		<script src="js/fitnessTracker.js" type="text/javascript"></script>
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Website Title</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Profile Page</h2>
			<div>
				<p>Your account details are below:</p>
				<table>
					<tr>
						<td>Username:</td>
						<td><?=$_SESSION['name']?></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><?=$password?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?=$email?></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="container-fluid">
  <div class="row">
    <div class="col-sm-4" style="background-color:yellow">calendar will go here</div>
    <div class="col-sm-4">
		<fieldset>
		  <legend>Select a date</legend>
		  	<input id="date" type="date" value="2017-06-01">
		  <h5 id="Date"></h5>
		</fieldset>
      <fieldset>
        <legend>Calculate Your BMI</legend>
        <p>Height (feet): <input type="text" id="heightFeet"></p></br>
        <p>Height (inches): <input type="text" id="heightInch"></p><br>
        <p>Weight (LBs): <input type="text" id="weight"></p><br>
        <button id="calcBMI">Calculate BMI</button><br>
        <h5 id="BMI"></h5>
      </fieldset>

      <Fieldset>
        <legend>Calculate Calories Burned During Weight Lifting</legend>
        <p>Weight (LBs): <input type="text" id="calBurnWeight"></p><br>
        <p>Number of minutes you lifted weights (including rest): <input type="text" id="minutesLifting"></p><br>
        <p>Intensity of Training: <select id="intensity">
          <option value="0"></option>
          <option value=".055">Body Building</option>
          <option value=".042">Circuit Training</option>
          <option value=".039">Strength Training</option>
          <option value=".028">Light Weight Lifting</option>
        </select></p>
        <button id="calsBurnedBtn">Calculate Calories Burned</button><br>
        <h5 id="calsBurned"></h5>
      </Fieldset>

      <fieldset>
        <legend>Calculate Calories Burned During Aerobic Exercise</legend>
        <p>Type of Exercises: <select id="typeExercises">
          <option value="0"></option>
          <option value="1">Sitting</option>
          <option value="4">Bicycling ( < 10 mph, general Leisure )</option>
          <option value="6">Bicycling ( 10 - 11.9 mph )</option>
          <option value="8">Bicycling ( 12 - 13.9 mph )</option>
          <option value="10">Bicycling ( 14 - 15.9 mph )</option>
          <option value="12">Bicycling ( 16 - 19 mph )</option>
          <option value="16">Bicycling ( > 20 mph, racing ) </option>
          <option value="3">Stationary Bike, Very Low Effort </option>
          <option value="5.5">Stationary Bike, Low Effort</option>
          <option value="7">Stationary Bike, Moderate Effort</option>
          <option value="10.5">Stationary Bike, Vigorous Effort</option>
          <option value="12.5">Stationary Bike, Very Vigorous Effort</option>
          <option value="4.5">Calisthenics, Light / Moderate Effort</option>
          <option value="8">Calisthenics, Vigorous Effort ( pushup, pullups, situps etc. )</option>
          <option value="8">Running, 12 min mile</option>
          <option value="9">Running, 11.5 min mile</option>
          <option value="11">Running, 10 min mile</option>
          <option value="11.5">Running, 9 min mile</option>
          <option value="12.5">Running, 8 min mile</option>
          <option value="14">Running, 7 min mile</option>
          <option value="15">Running, 6.5 min mile</option>
          <option value="16">Running, 6 min mile</option>
          <option value="18">Running, 5.5 min mile</option>
          <option value="16">Running Stairs</option>
          <option value="6">Swimming Leisurely</option>
          <option value="8">Swimming, Backstroke Laps</option>
          <option value="10">Swimming, Breaststroke Laps</option>
          <option value="11">Swimming, Butterfly laps</option>
          <option value="2.5">Walking, Slow Pace</option>
          <option value="3.5">Walking, Moderate Pace</option>
          <option value="4">Walking, Brisk Pace</option>
          <option value="4.5">Walking, Very Brisk Pace</option>
          <option value="6.5">Walking, Race Walking, > 4.5 mph</option>
        </select></p><br>
        <p>Number of minutes you exercised: <input type="text" id="aerobicMinutes"></p><br>
        <p>Weight (LBs): <input type="text" id="aerobicWeight"></p><br>
        <button id="aerobicCalsBurnedBtn">Calculate Calories Burned</button><br>
        <h5 id="aerobicCalsBurned"></h5>
      </fieldset>
	  <button id="addToCalendar">Add stats to calendar</button><br>
    </div>
    <div class="col-sm-4" style="background-color:yellow">display user info here</div>
  </div>
</div>

<div class="page">
		<div style="width:100%; max-width:600px; display:inline-block;">
			<div class="monthly" id="mycalendar"></div>
		</div>
		<br><br>
		<br>
</div>
<!-- JS ======================================================= -->
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/monthly.js"></script>
<script type="text/javascript">
	$(window).load( function() {

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

	switch(window.location.protocol) {
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
