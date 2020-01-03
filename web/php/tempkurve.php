<!DOCTYPE html>
<html lang="en-US">
<head>
<title>Templogger</title>
<style>

	table {
		width : 70%;
	}
   table th {
		font-size: 4vw;
   }
   table tr {
	   font-size: 3vw;
   }
	h1 {
		font-size: 4vw;
	}
	th {
		text-align : left;
	}
	p {
		font-size : 2vw;
	}

</style>

</head>
<body>

<h1>Temperaturmåling</h1>
<div id="termometer"></div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

 <?php 
$servername = "kolaasbakkanen02.mysql.domeneshop.no";
$username = "kolaasbakkanen02";
$password = "9Volapyk-tvetunget-vestyrje-lavpunkt";
$dbname = "kolaasbakkanen02";

$site = "trollhaugenpi" ;
$sensor_ute = "28-021392467443";
$sensor_inne = "28-031597798e15"; 
 
$samp_per_day = 4;
$no_days = 30;
  
 
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
  
// Get sensors from current site
$sql = "select distinct sensor from kolaasbakkanen02.logentry   where site = '".$site."';" ;  
$sensors = mysqli_query($conn, $sql); 
$sensor_address = array( );
$sensor_count = $sensors->num_rows;
  	while($row = mysqli_fetch_assoc($sensors) ) {
		$sensor_address[] = $row['sensor'];
	}

//$print_r($sensor_address );

$sql = "SELECT A.*, B.`sensorName` FROM `kolaasbakkanen02`.`logentry` as A  left outer join `kolaasbakkanen02`.`sensorname` as B on A.sensor = B.sensorID WHERE `site`= '".$site."' order by `timestamp` DESC;";

$siste = array (); 

$result = mysqli_query($conn, $sql);
//	echo "<table>";
	 
	for($x=0; $x < $sensor_count ; $x++ ) {
		$row = mysqli_fetch_assoc($result);
		
		if($row['sensorName'] === null) {    
			$identifier = $row['sensor'];
		} else {
			$identifier =$row['sensorName'];
		} 
		$formatted_number = number_format((float)$row['value'], 1, '.', '');    
		$siste[$identifier] = $formatted_number; // to be used when showing gauge
//		echo "<tr><td>".$identifier."</td><td>".$formatted_number."</td></tr>" ;
		$lasttimestamp = $row['timestamp'] ;
	}
	
//	echo "</table>";                                 
	echo "<p>Time: ".$lasttimestamp.", Site: ".$site."</p>";
 
 ?>
 
 
 <div id="tempkurve"></div>
 
 <script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['line', 'gauge']});
google.charts.setOnLoadCallback(drawChart);
google.charts.setOnLoadCallback(drawChart2);
 // Draw the chart and set the chart values
function drawChart() {
 
  var data = new google.visualization.DataTable();
  data.addColumn('datetime', 'Time');
 <?php

 
 
 foreach( $sensor_address as $a ) {
	// Check for alternative name:
	$sqlName = "SELECT sensorName FROM kolaasbakkanen02.sensorname where sensorID = '".$a."'  ;";
	$result = mysqli_query($conn, $sqlName);
	$b=$a;
 	if( $result->num_rows >0 ) {
		$row = mysqli_fetch_assoc($result) ; // single value result
		$b = $row ['sensorName'];
 	}
	echo "data.addColumn('number', '" ;
	echo $b ; 
	echo "');\r\n";
 }	
	
 ?>  
  data.addRows([
 <?php
 
 
 
//  $sql = "SELECT  *  FROM `kolaasbakkanen02`.`logentry`   WHERE `site`= '".$site."' AND `sensor` =  '".$sensor_ute."' order by `timestamp` DESC ;";
    $sql = "SELECT  *  FROM `kolaasbakkanen02`.`logentry`   WHERE `site`= '".$site."'  order by `timestamp` DESC ;";
  $result = mysqli_query($conn, $sql);
  
  	for($x= ($samp_per_day * $no_days - 1); $x >= 0  ; $x-- ) {
		// New method; all sensors must be parsed to produce one data line
		$val = array( );
		for($s=0; $s< $sensor_count ; $s++) {
			$row = mysqli_fetch_assoc($result);
			$val[$row['sensor']] =  number_format((float)$row['value'], 1, '.', '');
		}

		
 
		$timestamp = str_replace(" ", "T", $row['timestamp']);
		$datetimestring = "new Date('".$timestamp."')";


		if($x > 0 ) {
			echo "[".$datetimestring ;
			foreach( $sensor_address as $a ) {
				echo ", ".$val[$a] ;
			}
			echo "],\r\n" ;
		}
		else {
			echo "[".$datetimestring ;
			foreach( $sensor_address as $a ) {
				echo ", ".$val[$a] ;
			}
			echo "]\r\n" ;
		}
		
		
	}
 
 
?>
]);

  // Optional; add a title and set the width and height of the chart
  var options = {
	  title:'Temperatur siste <?= $no_days ?> dager',
      hAxis: {
            format: 'dd.MM.yy HH:mm',
            gridlines: {count: 7}
      },	 
	  width:1200, height:600
	  
	};

  // Display the chart inside the <div> element with id="tempkurve"
  var chart = new google.charts.Line(document.getElementById('tempkurve'));
  chart.draw(data, google.charts.Line.convertOptions(options));
}
function drawChart2() {  
  var data2 = google.visualization.arrayToDataTable([
          ['Label', 'Value']
<?php
		foreach($siste as $key => $value) {
//			echo ",['".$key."', ".$value."]" ;
			echo ",['{$key}', {$value}]" ;
		}
//          ,['Inne', 10.8]
//          ,['Ute', -5.4]

?>
        ]);
        var options2 = {
		  min: -40,	max: 40,
          width: 600, height: 300,
          redFrom: 30, redTo: 40,
          yellowFrom:-40, yellowTo: -30,
          minorTicks: 10,
		  majorTicks: [ '-40', '-30', '-20', '-10', '0', '10',  '20',  '30',  '40' ]
		   
        };

        var chart2 = new google.visualization.Gauge(document.getElementById('termometer'));

        chart2.draw(data2, options2);
  
  
}
</script>

<?php

   mysqli_close($conn);

?>
</body>
</html>
