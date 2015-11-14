<?php
session_start();
include "connect.php";
include "login.php";
if(isset($_GET["ID"]) && isset($_GET["rating"]) && validateUser() == 1)
{
	if(mysql_num_rows(mysql_query("SELECT * FROM `problemRating` WHERE `adminID`='" . $_SESSION["nick"] . "' AND `problemID`='" . $_GET["ID"] . "'")) == 0)
		runQuery("INSERT INTO `problemRating` (`adminID`, `problemID`, `rating`) 
			VALUES ('" . $_SESSION["nick"] . "', '" . $_GET["ID"] . "', '" . $_GET["rating"] . "')");
}
?>