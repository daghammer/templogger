<!DOCTYPE html>
<html>
<body>
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

 
$sql = "INSERT INTO `kolaasbakkanen02`.`logentry` (site, sensor, timestamp, value, datatype) VALUES('".$hostname."', '".$sensor."', CURRENT_TIMESTAMP(), ".$temp." ,'C' )";
 
// echo "<p> sql : ".$sql."</p>";  

$result = mysqli_query($conn, $sql);
	
echo "<p> result : ".$result."</p>";

/*
echo "<p> hostname : ".$hostname."</p>";  
echo "<p> sensor : ".$sensor."</p>";
echo "<p> timestamp : ".$timestamp."</p>";  
echo "<p> temp : ".$temp."</p>";
 */
 
mysqli_close($conn);

?> 
 
</body>
</html>