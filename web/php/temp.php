<!DOCTYPE html>
<html>
<body>
<h1>Temperaturlogger</h1>

<?php
$servername = "kolaasbakkanen02.mysql.domeneshop.no";
$username = "kolaasbakkanen02";
$password = "9Volapyk-tvetunget-vestyrje-lavpunkt";
$dbname = "kolaasbakkanen02";

 
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

$start = '05:00:00' ;
$stop = '08:00:00 ';

 
 $sql = "SELECT  * from `kolaasbakkanen02`.`logentry` where TIME(timestamp) > CAST('05:00:00' AS time) AND TIME(timestamp) < CAST('07:00:00' AS time)  ORDER BY timestamp DESC ";
//$sql = "SELECT  * from `kolaasbakkanen02`.`logentry`    ORDER BY timestamp DESC ";
 

$result = mysqli_query($conn, $sql);

if( true ) {
	echo "<table><tr><th>Site</th><th>Sensor</th><th>Temp</th><th>time</th></tr>";

	while ($row = mysqli_fetch_assoc($result)) {
		echo "<tr><td>".$row['site']."</td><td>".$row['sensor']."</td><td>".$row['value']."</td><td>".$row['timestamp']."</td></tr>" ;
	}	
		
	echo "</table>";
}
else {
	echo "<table><tr><th>Site</th><th>Temp</th></tr>";

	 
	for($x=0; $x < 2 ; $x++ ) {
		$row = mysqli_fetch_assoc($result);
		 
		echo "<tr><td>".$row['site']."</td><td>".$row['value']."</td></tr>" ;
		
	}	
	
	echo "</table>";
	
}



 
 
mysqli_close($conn);

?> 
 
</body>
</html>