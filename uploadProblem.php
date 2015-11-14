<?php
session_start();
include "connect.php";
include "login.php";
if(isset($_POST["title"]) && isset($_POST["description"]) 
	&& isset($_POST["input"]) && isset($_POST["output"]) && (validateUser() != -1))
{
	runQuery("INSERT INTO `problem` (`user`, `title`, `description`, `input`, `output`) 
		VALUES ('" . $_SESSION["nick"] . "', '" . $_POST["title"] . "', '" . $_POST["description"] . "', 
			'" . $_POST["input"] . "', '" . $_POST["output"] . "')");
	header("Location:index.php");
}
?>