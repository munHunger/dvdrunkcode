<?php
	session_start();
	include_once "connect.php";
?>
<html>
	<head>
		<meta charset="utf-8">
		<link href='https://fonts.googleapis.com/css?family=Squada+One' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=VT323' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet' type='text/css'>
		<title>DV is Drunk</title>
		<link rel="stylesheet" type="text/css" href="generalStyle.css">
		<script type="text/javascript">
			var username = "";
			var password = "";
			function httpGet(theUrl)
			{
			    var xmlHttp = new XMLHttpRequest();
			    xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
			    xmlHttp.send( null );
			    return xmlHttp.responseText;
			}
		</script>
	</head>
	<body>
		<?php
			include "login.php";
			include "home.php";
			if(validateUser() == -1)
				displayLoginHTML();
			else
				displayHomeHTML();
		?>
		<a href="logout.php">
			<div id="logout">
				Log out
			</div>
		</a>
	</body>
</html>
