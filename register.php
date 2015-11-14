<?php
	include "connect.php";
	if(isset($_POST["name"]) && isset($_POST["nick"]) && isset($_POST["password"]) && isset($_POST["location"]))
	{
		$exists = mysql_query("SELECT nick FROM user WHERE nick=" . $_POST["nick"] . ";");
		if (!$exists) {
		runQuery("INSERT INTO `user`(`name`, `nick`, `pass`, `isAdmin`) VALUES('" . $_POST["name"] . "', '" . $_POST["nick"] . "', '" . hash("SHA512", $_POST["password"]) . "', '0')");
		header("Location:" . $_POST["location"]);
		}
	}
?>
