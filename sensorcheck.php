<!DOCTYPE html>
<html>
<head>
<title>Test Sensor</title>
</head>
<body>
<?php

include 'conn.php';

$letzte_minuten = 15;

$conn = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($conn == false) {
	
echo "Error conn.";
}
$sensorselect = "select id, name from sensors where monitoring = 1 order by id asc";
$sensorresult = $conn->query($sensorselect);
if ($sensorresult->num_rows > 0) {
    while($sensorrow = $sensorresult->fetch_assoc()) {
        echo "<h1>Prüfe Sensor " . $sensorrow['id'] . "</h1><br>";

        $checkselect = "select id, `timestamp`, DATE_FORMAT(timestamp, '%d.%m.%Y, %T') formatted_date from sensorvalues where sensorid = " .$sensorrow['id'] . " and timestamp >= date_sub(now(), interval ". $letzte_minuten ." minute) order by id desc";
        $checkresult = $conn->query($checkselect);
        
        if ($checkresult->num_rows == 0) {
            echo "Keine Daten in den letzten ". $letzte_minuten ." Minuten vorhanden.<br>";
            // mail("mm", "Temperatursensor " . $sensorrow['id'] . " (" . $sensorrow['name'] . ") hat in den letzten ". $letzte_minuten ." Minuten keine Daten gemeldet!", "no text");
        } else {
            echo $checkresult->num_rows . " Datensätze in den letzten ". $letzte_minuten ." Minuten vorhanden. <br>";
			$resultrow = $checkresult->fetch_assoc();
			echo "Letzter: " .  $resultrow["formatted_date"] . "<br>";
		}
    }
} else {
	
	echo "sensors = 0";
}

echo "<br><br>Done.";

$conn->close();

?></body>
</html> 
