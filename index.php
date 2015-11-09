<?php
if(isset($_POST['zipcode']) && is_numeric($_POST['zipcode'])){
    $zipcode = $_POST['zipcode'];
}else{
    $zipcode = '20008';
}
$result = file_get_contents('http://weather.yahooapis.com/forecastrss?p=' . $zipcode . '&u=f');
$xml = simplexml_load_string($result);

//echo htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

$xml->registerXPathNamespace('yweather', 'http://xml.weather.yahoo.com/ns/rss/1.0');
$location = $xml->channel->xpath('yweather:location');

if(!empty($location)){
    foreach($xml->channel->item as $item){
        $current = $item->xpath('yweather:condition');
        $forecast = $item->xpath('yweather:forecast');
        $current = $current[0];
        $output = <<<END
		
		<div>
            <h1 style="margin-bottom: 15px;; text-align:center;">Weather for {$location[0]['city']}, {$location[0]['region']}</h1>
			
            <h2 style="margin-left:20px; text-decoration:underline;">Current Conditions</h2>
			
            <h3 style="margin-left:20px;">
            <span style="font-size:72px; font-weight:bold;">{$current['temp']}&deg;F</span>
            <img src="http://l.yimg.com/a/i/us/we/52/{$current['code']}.gif" style="height:65px;"/>&nbsp;
            {$current['text']}
            </h3>
		</div>
		

		<div class="forecast">		
            <h2 style="margin-top:15px; text-decoration:underline;">Forecast</h2>
			<h3>Today - {$forecast[0]['text']}. <a style="color:#DE4747;">High:</a> {$forecast[0]['high']} <a style="color:#47ACDE;">Low:</a> {$forecast[0]['low']} <img src="http://l.yimg.com/a/i/us/we/52/{$forecast[0]['code']}.gif" style="vertical-align: middle;"/>&nbsp;</p>

            <br/>
			<h3>Tomorrow - {$forecast[1]['text']}. <a style="color:#DE4747;">High:</a> {$forecast[1]['high']} <a style="color:#47ACDE;">Low:</a> {$forecast[1]['low']} <img src="http://l.yimg.com/a/i/us/we/52/{$forecast[1]['code']}.gif" style="vertical-align: middle;"/>&nbsp;</h3>

            <br/>
            <h3>{$forecast[2]['day']} - {$forecast[2]['text']}. <a style="color:#DE4747;">High:</a> {$forecast[2]['high']} <a style="color:#47ACDE;">Low:</a> {$forecast[2]['low']} <img src="http://l.yimg.com/a/i/us/we/52/{$forecast[2]['code']}.gif" style="vertical-align: middle;"/>&nbsp;</h3>

            <br/>
			<h3>{$forecast[3]['day']} - {$forecast[3]['text']}. <a style="color:#DE4747;">High:</a> {$forecast[3]['high']} <a style="color:#47ACDE;">Low:</a> {$forecast[3]['low']} <img src="http://l.yimg.com/a/i/us/we/52/{$forecast[3]['code']}.gif" style="vertical-align: middle;"/>&nbsp;</h3>			
		</div>
END;
    }
}else{
    $output = '<h1>No results found, please try a different zip code.</h1>';
}
?>

<html>

<head>
<title>Weather</title>
<meta http-equiv="refresh" content="300">
<link href="style.css" rel="stylesheet">
	<script>
		function updateTime(){
			var now = new Date();
			var hours = now.getHours();
			var minutes = now.getMinutes();
			var seconds = now.getSeconds();

			if (minutes < 10) { 
				minutes = '0' + minutes;
			}

			if (seconds < 10) { 
				seconds = '0' + seconds;
			}		
		 
			if (hours >= 12 && hours < 24) { 
				var timeOfDay = 'PM';
			}
			else { 
				var timeOfDay = 'AM'; 
			}
		  
			if (hours > 12) { 
				hours = hours - 12;
			} 

			var currentTime = hours + ':' + minutes + ' ' + timeOfDay;
			var myClock = document.getElementById('clock');
			myClock.innerHTML = currentTime;
			var t = setTimeout(updateTime, 500);
		}
	</script>

</head>

<body onload="updateTime()">
	<div class="container">
	
			<div class="content">
			
				<div>
						<table>
							<tr>
								<td class="dateBox" id="sun"><p>Sun</p></td>
								<td class="dateBox" id="mon"><p>Mon</p></td>
								<td class="dateBox" id="tue"><p>Tue</p></td>
								<td class="dateBox" id="wed"><p>Wed</p></td>
								<td class="dateBox" id="thu"><p>Thu</p></td>
								<td class="dateBox" id="fri"><p>Fri</p></td>
								<td class="dateBox" id="sat"><p>Sat</p></td>						
							</tr>
						</table>					
				
					<div id="clock" class="time"></div>
					<h2 class="date"><?php echo date("F j, Y"); ?></h2>
						
				</div>	
				
					<?php echo $output; ?>
       
			</div>
			
	</div>
</body>

<script>

switch (new Date().getDay()) {
    case 0:	
		document.getElementById("sun").style.backgroundColor = "rgb(222,71,71)"; 
        break;
    case 1:
		document.getElementById("mon").style.backgroundColor = "rgb(222,71,71)"; 
        break;
    case 2:
		document.getElementById("tue").style.backgroundColor = "rgb(222,71,71)";
        break;
    case 3:
		document.getElementById("wed").style.backgroundColor = "rgb(222,71,71)"; 
        break;
    case 4:
		document.getElementById("thu").style.backgroundColor = "rgb(222,71,71)"; 
        break;
    case 5:
		document.getElementById("fri").style.backgroundColor = "rgb(222,71,71)"; 
        break;
    case  6:
		document.getElementById("sat").style.backgroundColor = "rgb(222,71,71)"; 
        break;
}

</script>	

</html>