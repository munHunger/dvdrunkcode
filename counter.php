<?php
	if(isset($_GET["type"]))
	{

		$date1 = new DateTime("2015-10-07 20:15:00"); //Date for launch... to be edited
		$date2 = new DateTime("2015-10-08 01:00:00"); //Date for end... to be edited
		$date3 = new DateTime(date("Y-m-d H:i:s")); //Now
		$interval = $date1->diff($date3);
		if( strtotime($date1->format("Y-m-d H:i:s")) < strtotime('now') )
			$interval = $date2->diff($date3);

		/*
		echo $interval->y . "-" . $interval->m . "-" . $interval->d . " " . $interval->h . ":" . $interval->i . ":" . $interval->s . "<br />"; 
		echo $interval->format("%D %H:%I:%S");*/
		if($_GET["type"] == "days")
			echo $interval->format("%D");
		else if($_GET["type"] == "hours")
			echo $interval->format("%H");
		else if($_GET["type"] == "minutes")
			echo $interval->format("%I");
		else if($_GET["type"] == "seconds")
			echo $interval->format("%S");
	}
?>