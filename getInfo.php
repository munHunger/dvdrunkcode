<?php
	include_once "connect.php";
	
	if(isset($_GET["action"])) {
		if($_GET["action"] == "getAll") {
			echo (flattenDoubleArray(getUsersAndScore()));
		}
	} else if(isset($_GET["getUserPoints"])) {
		$p = getUserPoints($_GET["getUserPoints"]);
		echo ($p[0] . "@" . $p[1]);
	} else if(isset($_GET["getPercentages"])) {
		echo (getPercentages($_GET["getPercentages"]));
	} else if(isset($_GET["getProblemScore"])) {
		echo (getProblemScore($_GET["getProblemScore"])); 
	} else if(isset($_GET["getAlcoholScore"])) {
		echo (getAlcoholScore($_GET["getAlcoholScore"]));
	} else if(isset($_GET["getScoreInfo"])) {
		echo getScoreInfo($_GET["getScoreInfo"]);
	} else if(isset($_GET["tester"])) {
		echo (tester($_GET["tester"]));
	}	else { 
		//Suppressed warning for use in include statements
		//echo ("Something went wrong");
	}


	// <3 <3 <3 function section <3 <3 <3 //
	function getUsersAndScore() {
		$userAndScore = array(array(),array());
		$findUsers = mysql_query("SELECT ID, name FROM user;");
		if (!$findUsers) { // add this check.
    	die('Invalid query: ' . mysql_error());
		}		
		while($row = mysql_fetch_assoc($findUsers)) {
			$pArr = getUserPoints($row["ID"]);
			$p = intval($pArr[0]) + intval($pArr[1]);
			$userAndScore[0][] = $p;
			$userAndScore[1][] = $row["name"];
		}	
		array_multisort($userAndScore[0], SORT_DESC, $userAndScore[1]);
		return $userAndScore;
	}

	function flattenDoubleArray($a) {
		$temp = "";
		for($i = 0; $i < count($a[0]); $i++) {
		$temp = $temp . $a[0][$i] . "@" . $a[1][$i] . "@";
		}
		return ($temp);
	}

	function getUserPoints($userID) {
		$allPoints = mysql_query("SELECT problemID, ID FROM score WHERE userID=" . $userID . ";");
		if (!$allPoints) { // add this check.
    	die('Invalid query: ' . mysql_error());
		}
		$drink = 0;
		$code = 0;
		while($points = mysql_fetch_assoc($allPoints)) {
			if ($points["problemID"] == (-1)) {
				$score = getAlcoholScore($points["ID"]);
				if ($score == (-1))  				
					$drink = $drink + 0;
				else 
					$drink = $drink + $score;	
			} else {
				$score = getProblemScore($points["problemID"]);
				if ($score == (-1))
					$code = $code + 0;
				else 
					$code = $code + $score;
			}
		}
		return (array($drink, $code));
	}


	function getPercentages($userID){
		$userAndScore = array(array(),array());
		$userAndDrinkScore = array(array(),array());
		$userAndProgScore = array(array(),array());
		
		$findUsers = mysql_query("SELECT ID, name FROM user;");
		if (!$findUsers) { // add this check.
    	die('Invalid query: ' . mysql_error());
		}
		while($row = mysql_fetch_assoc($findUsers)) {
			$findPoints = mysql_query("SELECT problemID, ID FROM score WHERE userID=" . $row["ID"] . ";");
			if (!$findPoints) {
 				$userAndScore[0][] = 0;
				$userAndScore[1][] = $row["ID"];
				$userAndDrinkScore[0][] = 0;
				$userAndDrinkScore[1][] = $row["ID"];
				$userAndProgScore[0][] = 0;
				$userAndProgScore[1][] = $row["ID"];				
			} else {
				$accDrinkPoints = 0;
				$accProgPoints = 0;
				while($p = mysql_fetch_assoc($findPoints)) {
					if ($p["problemID"] == -1) {
						$score = getAlcoholScore($p["ID"]);
						if ($score == (-1))  				
							$accDrinkPoints = $accDrinkPoints + 0;
						else 
							$accDrinkPoints = $accDrinkPoints + $score;	
					} else { 
						$score = getProblemScore($p["problemID"]);
						if ($score == (-1)) 				
							$accProgPoints = $accProgPoints + 0;
						else 				
							$accProgPoints = $accProgPoints + $score;					
					}
				}
				$userAndScore[0][] = $accDrinkPoints + $accProgPoints;
				$userAndScore[1][] = $row["ID"];	
				$userAndDrinkScore[0][] = $accDrinkPoints;
				$userAndDrinkScore[1][] = $row["ID"];
				$userAndProgScore[0][] = $accProgPoints;
				$userAndProgScore[1][] = $row["ID"];
			}
		}
	
		array_multisort($userAndScore[0], SORT_DESC, $userAndScore[1]);
		array_multisort($userAndDrinkScore[0], SORT_DESC, $userAndDrinkScore[1]);
		array_multisort($userAndProgScore[0], SORT_DESC, $userAndProgScore[1]);

		$drinkPercent = 0;
		$progPercent = 0;
		$allPercent = 0;
		
		for ($i = 0; $i < count($userAndScore[0]); $i++) {
			if ($userID == $userAndScore[1][$i]) {
				if ($userAndScore[0][0] <= $userAndScore[1][$i])
					$allPercent = 100;
				else 
					$allPercent = floor(($userAndScore[0][$i]*100)/$userAndScore[0][0]);
			}
			if ($userID == $userAndDrinkScore[1][$i]) {
				if ($userAndDrinkScore[0][0] <= $userAndDrinkScore[0][$i]) 
					$drinkPercent = 100;
				else
					$drinkPercent = floor(($userAndDrinkScore[0][$i]*100)/$userAndDrinkScore[0][0]);
			}
			if ($userID == $userAndProgScore[1][$i]) {
				if ($userAndProgScore[0][0] <= $userAndProgScore[0][$i]) 
					$progPercent = 100;
				else 
					$progPercent = floor(($userAndProgScore[0][$i]*100)/$userAndProgScore[0][0]);
			}
		}
		return(strval($allPercent) . "@" . strval($progPercent) . "@" . strval($drinkPercent) . "@");
	}

	function getProblemScore($problemID) {
		$admins = mysql_query("SELECT nick FROM user WHERE isAdmin='1';");
		$accScore = 0;
		$numAdmins = 0;
		while($admin = mysql_fetch_assoc($admins)) {
			$numAdmins = $numAdmins + 1;
			$score = mysql_query("SELECT rating FROM problemRating WHERE adminID='" . $admin["nick"] . "' AND problemID='" . $problemID . "';");
			if (!$score) {
				return (-1);
			} 
			$accScore = $accScore + mysql_fetch_assoc($score)["rating"];
		}
		return (ceil($accScore/$numAdmins));
	}

	function getAlcoholScore($scoreID) {
		$info = mysql_query("SELECT percentageAlcohol, amountAlcohol FROM alcohol WHERE scoreID='" . $scoreID . "';");
		if (!$info) {
			return (-1);
		}
		$row = mysql_fetch_assoc($info);
		return calcAlcoholPoints($row["percentageAlcohol"], $row["amountAlcohol"]);
	}

	function calcAlcoholPoints($perc, $amount) {
		$worthOnePoint = 33*3; //cl*percentage
		$score = ceil(($perc*$amount)/$worthOnePoint);
		return $score;
	}


	/*Returns a string where the each four values are */
	function getScoreInfo($userID) {
		$startTime = "2015-10-21 18:00:00";	
		$endTime = "2015-10-21 24:00:00";

		$getUserScore = mysql_query("SELECT problemID, ID, timestamp FROM score WHERE userID=" . $userID . ";");
		if(!$getUserScore) {
			return "";
		} 
		$string = "";
		while($row = mysql_fetch_assoc($getUserScore)) {
			if (($startTime <= $row["timestamp"]) && ($row["timestamp"] <= $endTime)) {
				$scoreHeading = "";
				$scoreText = "";
				$scorePoints = 0;
				$scoreTimestamp = "";
	
				if ($row["problemID"] == (-1)) { //alcohol
					$info = mysql_query("SELECT percentageAlcohol, amountAlcohol FROM alcohol WHERE scoreID =" . $row["ID"] . ";");
					if (!$info) {
						$scorePoints = 0;
						$scoreText = "NA";
					} else {
						$fetch = mysql_fetch_assoc($info);
						$scorePoints = calcAlcoholPoints($fetch["percentageAlcohol"], $fetch["amountAlcohol"]);
						$scoreText = "Amount: " . $fetch["amountAlcohol"] . "cl </br> Percentage Alcohol: " . $fetch["percentageAlcohol"] . "%";
					}
					$scoreHeading = "Alcohol";
				} else { //problem
					$scorePoints = getProblemScore($row["problemID"]);
					$info = mysql_query("SELECT title, description FROM problem WHERE ID=" . $row["problemID"] . ";");
					if (!$info) {
						$scoreText = "NA";
						$scoreHeading = "Problem";
					} else {
						$fetch = mysql_fetch_assoc($info);
						$scoreHeading = $fetch["title"];
						$scoreText = $fetch["description"];
					}
				}
				$scoreTimestamp = $row["timestamp"];

		
				$string = $string . $scoreHeading . "@" . $scoreText . "@" . strval($scorePoints) . "@" . timeFromStart($scoreTimestamp) . "@";
			}
		}	
		return $string;
	}

	function timeFromStart($scoreTimestamp) {
		$startTime = "2015-10-05 18:00:00";
		$diffMin = intval(substr($scoreTimestamp, -5, 2)) - intval(substr($startTime, -5, 2));
		$diffHours = intval(substr($scoreTimestamp, -8, 2)) - intval(substr($startTime, -8, 2)); 		
		return ($diffHours*60 + $diffMin);
	}

	function tester($variable) {
		$aTime = "2015-10-06 16:00:00";
		$startTime = "2015-10-05 16:00:00";
		if ($aTime < $startTime)
			return ("True");
		else 
			return("False");
		return ($aTime < $startTime);
	}
?>





