<?php
	$connect = mysql_connect("localhost", "root", "warthog") or die("Could not connect to database");
	mysql_select_db("dvdr") or die("Could not find database");

	function runQuery($qry, $array)
	{
		$db = new PDO('mysql:dbname=dvdr;host=localhost', 'root', 'warthog');
		$statement = $db->prepare($qry);
		$statement->execute($array);
		return $db->lastInsertId();
		/*
		$mysqli = new mysqli("localhost", "root", "warthog", "dvdr");
		if ($mysqli->connect_errno) 
	    	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		
		if (!($stmt = $mysqli->prepare($qry)))
    		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    	$id = 1;

		if (!$stmt->bind_param("i", $id))
		    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;

		if (!$stmt->execute())
		    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		*/
	}
?>