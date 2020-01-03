<!DOCTYPE html>
<html>
<head>
<title>Temperaturlogger</title>
<style>

	table {
		width : 97%;
	}
   table th {
		font-size: 5vw;
   }
   table tr {
	   font-size: 4vw;
   }
	h1 {
		font-size: 7vw;
	}
	th {
		text-align : left;
	}
	p {
		font-size : 3vw;
	}

</style>

</head>

<body>
<h1>Temperaturlogger</h1>

<?php
$servername = "kolaasbakkanen02.mysql.domeneshop.no";
$username = "kolaasbakkanen02";
$password = "9Volapyk-tvetunget-vestyrje-lavpunkt";
$dbname = "kolaasbakkanen02";

$site = "trollhaugenpi" ;
 
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
 
$hostname = $_GET['hostname']; 
$sensor =   $_GET['sensor'];     
$temp =     $_GET['temp'];
$timestamp =  $_GET['time'];            

if(isset($_GET['site'])) {
	$site = $_GET['site'];
}
 
// $sql = "SELECT  * from `kolaasbakkanen02`.`logentry` WHERE site = '".$site."' ORDER BY timestamp DESC ";
 $sql = "SELECT A.*, B.`sensorName` FROM `kolaasbakkanen02`.`logentry` as A  left outer join `kolaasbakkanen02`.`sensorname` as B on A.sensor = B.sensorID WHERE `site`= '".$site."' order by `timestamp` DESC;";
echo "<p>Site: ".$site."</p>" ;

$result = mysqli_query($conn, $sql);

if( false ) {
	echo "<table><tr><th>Site</th><th>Sensor</th><th>Temp</th><th>time</th></tr>";

	while ($row = mysqli_fetch_assoc($result)) {
		echo "<tr><td>".$row['site']."</td><td>".$row['sensor']."</td><td>".$row['value']."</td><td>".$row['timestamp']."</td></tr>" ;
	}	
		
	echo "</table>";
}
else {
	echo "<table><tr><th>Sensor</th><th>Temp</th><th>Time</th></tr>";

	 
	for($x=0; $x < 8 ; $x++ ) {
		$row = mysqli_fetch_assoc($result);
		
		if($row['sensorName'] === null) {    
			$identifier = $row['sensor'];
		} else {
			$identifier =$row['sensorName'];
		}
 
		$formatted_number = number_format((float)$row['value'], 1, '.', '');

		echo "<tr><td>".$identifier."</td><td>".$formatted_number."</td><td>".$row['timestamp']."</td></tr>" ;
		
	}
	
	echo "</table>";                                 
	
	 
}



 
 
mysqli_close($conn);

?> 
 
</body>
</html>