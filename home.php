<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="refresh" content="30"/>
	<meta charset="utf-8">
	<title>Pametno ogledalo</title>
	<style>
		#dateTime{
			font-size:70px;
			font-family:Gentium-R;
			color:white;
			float: left;
			margin-top:5px;
			margin-left:50px;
		}
		#tempHum{
			font-size:40px;
			font-family:Gentium-R;
			color:white;
			
		}
		.days {
			padding: 0px 10px 0px 10px;
			border-bottom: 1px solid white;
			border-top: 1px solid white;
		}
		#calendar {
			font-size:40px;
			font-family:Gentium-R;
			color:white;
			float: left;
			margin-left: 40px;
			border: 1px solid white;
		}
		th, td{
			text-align:center;
		}
		body{
			background: black;
		}
	</style>
	<script>
		function startTime() {
			var today = new Date();
			var h = today.getHours();
			var m = today.getMinutes();
			m = checkTime(m);
			document.getElementById('dateTime').innerHTML = h + ":" + m;
		}
		function checkTime(i) {
			if(i < 10) {i = "0" + i}; // dodaje nulu ispred brojeva manjih od < 10
			return i;
		}
	</script>
</head>
<body onload="startTime()">
<div id="calendar">
	<?php

		$date = time();

		$day = date('d', $date);
		$month = date('m', $date);
		$month_int = date('n')-1;
		$year = date('y', $date);
		$month_text = array("Siječanj", "Veljača", "Ožujak", "Travanj", "Svibanj", 
		"Lipanj" , "Srpanj" ,"Kolovoz" ,"Rujan" ,"Listopad" ,"Studeni" ,"Prosinac");

		$first_day_of_month = date('D',mktime(0, 0, 0, date('m'), 1));

		switch($first_day_of_month)
		{
			case "Mon": $blank = 0; break;
			case "Tue": $blank = 1; break;
			case "Wed": $blank = 2; break;
			case "Thu": $blank = 3; break;
			case "Fri": $blank = 4; break;
			case "Sat": $blank = 5; break;
			case "Sun": $blank = 6; break;
		}

		$days_in_month = cal_days_in_month(0, $month, $year);

		echo "<table>";
		echo "<tr>
			<th colspan='4'>$month_text[$month_int]</th>
			<th colspan='3'>20$year</th>
      		      </tr>";
		echo "<tr>
			<td class='days'>PON</td>
			<td class='days'>UTO</td>
			<td class='days'>SRI</td>
			<td class='days'>CET</td>
			<td class='days'>PET</td>
			<td class='days'>SUB</td>
			<td class='days'>NED</td>
      		      </tr>";

		$day_count = 1;
		echo "<tr>";
		while($blank > 0) {
			echo"<td></td>";
			$blank -=1;
			$day_count++;
		}
		$day_num = 1;
		while($day_count < 8) {
			if($day_num == $day){
				echo "<td style='border:2px solid white;font-weight:bold'>$day_num</td>";
			} else {
			echo "<td>$day_num</td>";
			}
			$day_num++;
			$day_count++;
		}
		echo "</tr>";
		$day_count = 1;

		while($day_num < $days_in_month) {
			echo "<tr>";
			while($day_count < 8) {
				if($day_num > $days_in_month) {break;}
				if($day_num == $day){
					echo "<td style='border:2px solid white;font-weight:bold'>$day_num</td>";
				} else {
					echo "<td>$day_num</td>";
				}
				$day_count++;
				$day_num++;
			}
			$day_count = 1;
			echo "</tr>";
		}
		echo "</table>";
	?>	
</div>
<div id="dateTime"></div>
<div id="tempHum">
	<?php
		$temperature = 0;
		$humidity = 0;
		exec("python /var/www/html/dht22.py", $sensorData);
		if(!isset($_SESSION["counter"]) or $_SESSION["counter"] == 10) {
			$_SESSION["counter"] = 0;
		}
		if(!isset($_SESSION["temp"])) {
			$_SESSION["temp"] = array(x,x,x,x,x,x,x,x,x,x);
		}
		if(!isset($_SESSION["hum"])) {
			$_SESSION["hum"] = array(x,x,x,x,x,x,x,x,x,x);
		}
		$_SESSION["temp"][$_SESSION["counter"]] = $sensorData[0];
		$_SESSION["hum"][$_SESSION["counter"]] = $sensorData[1];
		for($i = 0; $i < 10; $i++) {
			if($_SESSION["temp"][$i] == x) {break;}
			$temperature += $_SESSION["temp"][$i];
			$humidity += $_SESSION["hum"][$i];
		}
		echo "<img src='temp_icon.jpg' style='width:50px;height:50px;margin-left:85px;margin-top:15px;'/>";
		echo number_format((float)$temperature/$i,1, '.', '')."°C";
		
		echo "<img src='hum_icon.png' style='width:50px;height:50px;margin-left:85px;margin-top:15px;'/>";
		echo number_format((float)$humidity/$i,2, '.', '')."%";
		$_SESSION["counter"]++;
	?>
</div>
</body>
</html>
