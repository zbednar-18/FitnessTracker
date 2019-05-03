window.onload = pageLoad;

function pageLoad() {
    var calcBmiBtn = document.getElementById("calcBMI");
    calcBmiBtn.onclick = calcBMI;

    var calsBurnedBtn = document.getElementById("calsBurnedBtn");
    calsBurnedBtn.onclick = calcCalsBurned;

    var areobicCalsBurnedBtn = document.getElementById("aerobicCalsBurnedBtn");
    areobicCalsBurnedBtn.onclick = aerobicCalsBurned;
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
    document.getElementById("calsBurned").innerHTML = "You Burned Approximately " + caloriesBurned + " Calories"; 
}

function aerobicCalsBurned() {
    var activityChoice = document.getElementById("typeExercises");
    var finalActivityChoice = activityChoice.value;
    var weight = parseFloat(document.getElementById("aerobicWeight").value);
    var weightKg = weight / 2.2;
    var minutes = parseFloat(document.getElementById("aerobicMinutes").value);
    var caloriesBurned = ((minutes) * (finalActivityChoice * 3.5 * weightKg) / 200).toFixed(2);
    document.getElementById("aerobicCalsBurned").innerHTML = "You Burned Approximately " + caloriesBurned + " Calories";
}