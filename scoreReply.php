<?php
	include "connect.php";
	if(isset($_GET["action"]))
	{
		if($_GET["action"] == "query")
		{
			$qry = "SELECT * FROM `score`";
			$reply = mysql_query($qry);
			while($row = mysql_fetch_assoc($reply))
			{
				echo $row["score"] . "<br />";
			}
		}
		else if($_GET["action"] == "insert")
		{
			$qry = "INSERT INTO `score`(`score`, `isProgramScore`) VALUES('" . $_GET["value"] . "', '" . $_GET["code"] . "')";
			mysql_query($qry);
		}
	}

?>