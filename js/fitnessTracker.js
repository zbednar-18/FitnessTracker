window.onload = pageLoad;

function pageLoad() {

	var calcBmiBtn = document.getElementById("calcBMI");
	calcBmiBtn.onclick = calcBMI;

	var areobicCalsBurnedBtn = document.getElementById("aerobicCalsBurnedBtn");
	areobicCalsBurnedBtn.onclick = aerobicCalsBurned;

	var calsBurnedBtn = document.getElementById("calsBurnedBtn");
	calsBurnedBtn.onclick = calcCalsBurned;

	var addToCalendar = document.getElementById("addToCalendar");
	addToCalendar.onclick = calendarUpdate;

	$(document).ready(function() {

		$.getJSON("events.json", function(data) {
			let lables = [];
			let weight = [];
			let bmi = [];
			data.monthly.forEach(function(element) {
				lables.push(new Date(element.startdate).toLocaleTimeString('en-US', {
					hour12: false,
					year: "numeric",
					month: "2-digit",
					day: "numeric"
				}).split(',')[0])

				if(!element.weight){
					weight.push(0)
				}
				if(!element.bmi){
					bmi.push(0)
				}

				weight.push(element.weight)
				bmi.push(element.bmi)
			});

			var ctx = document.getElementById('myChart').getContext("2d");
			ctx.canvas.width = 75;
			ctx.canvas.height = 50;
			var myChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: lables,
					datasets: [{
							label: 'Weight',
							data: weight,
							backgroundColor: [

							],
							borderColor: [

							],
							borderWidth: 2
						},
						{
							label: "Body Mass Index",
							data: bmi,
							backgroundColor: [
								'rgba(255, 99, 132, 0.2)',
								'rgba(54, 162, 235, 0.2)',
								'rgba(255, 206, 86, 0.2)',
								'rgba(75, 192, 192, 0.2)',
								'rgba(153, 102, 255, 0.2)',
								'rgba(255, 159, 64, 0.2)'
							],
							borderColor: [
								'rgba(255, 99, 132, 1)',
								'rgba(54, 162, 235, 1)',
								'rgba(255, 206, 86, 1)',
								'rgba(75, 192, 192, 1)',
								'rgba(153, 102, 255, 1)',
								'rgba(255, 159, 64, 1)'
							],
						}
					]
				},
				options: {
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero: true
							}
						}]
					}
				}
			});
		});
	});
}

function calcBMI() {
	var weight = parseInt(document.getElementById("weight").value);
	var feet = parseInt(document.getElementById("heightFeet").value);
	var inches = parseInt(document.getElementById("heightInch").value);
	var height = (feet * 12) + inches;
	var BMI = ((weight / (Math.pow(height, 2))) * 703).toFixed(2);
	document.getElementById("BMI").innerHTML = "You're BMI is: " + BMI;
}

let intensityValue = 0;
function getDropdownValue(value){
	if(value){
		intensityValue = value;
	}
}

function calcCalsBurned() {
	var weight = parseFloat(document.getElementById("calBurnWeight").value);
	var minutes = parseFloat(document.getElementById("minutesLifting").value);
	var caloriesBurned = ((weight * minutes) * intensityValue).toFixed(2)
	document.getElementById("calsBurned").innerHTML = "Calories Burned: " + caloriesBurned;
}

let finalActivityChoice = 0;
function getDropdownValueTwo(value){
	if(value){
		finalActivityChoice = value;
	}
}
function aerobicCalsBurned() {
	var weight = parseFloat(document.getElementById("aerobicWeight").value);
	var weightKg = weight / 2.2;
	var minutes = parseFloat(document.getElementById("aerobicMinutes").value);
	var caloriesBurned = ((minutes) * (finalActivityChoice * 3.5 * weightKg) / 200).toFixed(2);
	document.getElementById("aerobicCalsBurned").innerHTML = "Calories Burned:" + caloriesBurned;
}

function calendarUpdate() {
	var date = document.getElementById("date").value;
	console.log(document.getElementById("date").value)
	var weight = parseInt(document.getElementById("weight").value);
	var bmiInnerHtml = document.getElementById("BMI").innerHTML;
	var bmi = bmiInnerHtml.split(":")[1];
	var caloriesBurnedLiftingInnerHtml = document.getElementById("calsBurned").innerHTML;
	var caloriesBurnedLifting = caloriesBurnedLiftingInnerHtml.split(":")[1];
	var caloriesBurnedAerobicInnerHtml = document.getElementById("aerobicCalsBurned").innerHTML;
	var caloriesBurnedAerobic = caloriesBurnedAerobicInnerHtml.split(":")[1];
	var color = '#' + Math.random().toString(16).slice(-6);

	$.getJSON("events.json", function(data) {
		var newData = {
			id: data.monthly.length + 1,
			startdate: date,
			enddate: date,
			bmi: bmi,
			weight: weight,
			caloriesBurnedLifting: caloriesBurnedLifting,
			caloriesBurnedAerobic: caloriesBurnedAerobic,
			color: color,
			url: ""
		}
		data.monthly.push(newData);
		var dataNew = JSON.stringify(data);
		jQuery.post('saveJson.php', {
			newData: dataNew
		}, function(response) {
			window.location.reload(true)
		})
	});

}
