window.onload = pageLoad;

function pageLoad() {

    var calcBmiBtn = document.getElementById("calcBMI");
    calcBmiBtn.onclick = calcBMI;

    var calsBurnedBtn = document.getElementById("calsBurnedBtn");
    calsBurnedBtn.onclick = calcCalsBurned;

    var areobicCalsBurnedBtn = document.getElementById("aerobicCalsBurnedBtn");
    areobicCalsBurnedBtn.onclick = aerobicCalsBurned;

	var addToCalendar = document.getElementById("addToCalendar");
	addToCalendar.onclick = calendarUpdate;
}

function calcBMI() {
    var weight = parseInt(document.getElementById("weight").value);
    var feet = parseInt(document.getElementById("heightFeet").value);
    var inches = parseInt(document.getElementById("heightInch").value);
    var height = (feet * 12) + inches;
    var BMI = ((weight / (Math.pow(height, 2))) * 703).toFixed(2);
    document.getElementById("BMI").innerHTML = "You're BMI is: " + BMI;
}

function calcCalsBurned() {
    var weight = parseFloat(document.getElementById("calBurnWeight").value);
    var minutes = parseFloat(document.getElementById("minutesLifting").value);
    var intensityChoice = document.getElementById("intensity");
    var intensity = intensityChoice.value;
    var caloriesBurned = ((weight * minutes) * intensity).toFixed(2);
    document.getElementById("calsBurned").innerHTML = "Calories Burned: " + caloriesBurned;
}

function aerobicCalsBurned() {
    var activityChoice = document.getElementById("typeExercises");
    var finalActivityChoice = activityChoice.value;
    var weight = parseFloat(document.getElementById("aerobicWeight").value);
    var weightKg = weight / 2.2;
    var minutes = parseFloat(document.getElementById("aerobicMinutes").value);
    var caloriesBurned = ((minutes) * (finalActivityChoice * 3.5 * weightKg) / 200).toFixed(2);
    document.getElementById("aerobicCalsBurned").innerHTML = "Calories Burned:" + caloriesBurned;
}

function calendarUpdate(){
	console.log('her')
	var date = document.getElementById("date").value;
	var weight = parseInt(document.getElementById("weight").value);
	var bmiInnerHtml = document.getElementById("BMI").innerHTML;
	var bmi = bmiInnerHtml.split(":")[1];
	var caloriesBurnedLiftingInnerHtml = document.getElementById("calsBurned").innerHTML;
	var caloriesBurnedLifting = caloriesBurnedLiftingInnerHtml.split(":")[1];
	var caloriesBurnedAerobicInnerHtml = document.getElementById("aerobicCalsBurned").innerHTML;
	var caloriesBurnedAerobic = caloriesBurnedAerobicInnerHtml.split(":")[1];

	$.getJSON( "events.json", function( data ) {
		// now data is JSON converted to an object / array for you to use.
		alert( data[1].cast ) // Tim Robbins, Morgan Freeman, Bob Gunton
		var newMovie = {cast:'Jack Nicholson', director:...} // a new movie object
		// add a new movie to the set
		data.push(newMovie);
	});
}
