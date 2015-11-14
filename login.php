<?php
	function validateUser()
	{
		if(isset($_POST["nick"]) && isset($_POST["password"]) && isset($_GET["action"]) && $_GET["action"] == "login")
		{
			$_SESSION["nick"] = $_POST["nick"];
			$_SESSION["password"] = $_POST["password"];
			$qry = mysql_query("SELECT * FROM `user` WHERE `nick`='" . $_POST['nick'] . "' AND `pass`='" . hash("SHA512", $_POST['password']) . "'");
			$userrows = mysql_num_rows($qry);
			if($userrows > 0)
			{
				$row = mysql_fetch_assoc($qry);
				$_SESSION["userID"] = $row["ID"];
				$isAdmin = $row["isAdmin"];
				$_SESSION["admin"] = $isAdmin;
				return $isAdmin;
			}
		}
		else if(isset($_SESSION["admin"]))
		{
			$qry = mysql_query("SELECT * FROM `user` WHERE `nick`='" . $_SESSION['nick'] . "' AND `pass`='" . hash("SHA512", $_SESSION['password']) . "'");
			$userrows = mysql_num_rows($qry);
			if($userrows > 0)
				return mysql_fetch_assoc($qry)["isAdmin"];
			else
				return -1;
		}
		else
		{
			return -1;
		}
	}	
	function displayLoginHTML()
	{
		?>
		<link rel="stylesheet" type="text/css" href="generalStyle.css" />
		<link rel="stylesheet" type="text/css" href="login.css" />
		<link rel="stylesheet" type="text/css" href="form.css" />

		<div class="everything-containment" id="main">
			<!--<div style="width: 200px;">-->
				<img src="images/consbox.svg" width="300" style="transform:rotate(-10deg); margin-left:40px; margin-top:40px;" />


				<!-- login form -->
				<form style="margin-left:75px; margin-top:15px; margin-bottom:15px;" method="POST" action="index.php?action=login">
					<input type="text" placeholder="nick" style="margin-bottom:10px;" name="nick" id="nickText" />
					<br />
					<input type="password" placeholder="password" style="margin-bottom:10px;" name="password" id="passwordText" />
					<br />
					<input type="submit" style="margin-bottom:10px;" value="login" />
					<input type="button" value="Register" onclick="document.getElementById('register').style.display='block';" style="margin-bottom:10px;" />
				</form>



			<!--</div>-->
			<div class="counter" id="countdown">
				<div id="title">Fyllekod</div>
				<div id="day"></div>
				<div id="hour"></div>
				<div id="minute"></div>
				<script type="text/javascript">
					function counter () {
						var days = httpGet("counter.php?type=days");
						var hours = httpGet("counter.php?type=hours");
						var minutes = httpGet("counter.php?type=minutes");
						var seconds = httpGet("counter.php?type=seconds");
						document.getElementById("day").innerHTML = days;
						document.getElementById("hour").innerHTML = hours;
						document.getElementById("minute").innerHTML = minutes + ":" + seconds;

						setTimeout(function() { counter(); }, 1000);
					}
					counter();
				</script>
			</div> <!-- end counter -->
			<div class="register" style="display:none" id="register">
				<div id="main">
					<form method="POST", action="register.php" class=ourForm">
						<label>
							<span> The name people know you as: </span>
							<input type="text" style="width:100%" placeholder="Name" name="name"/><br />
						<label>				
						</label>
							<span> Your username/nickname: </span>
							<input type="text" style="width:100%" placeholder="Nick" name="nick"/><br />
						<label>					
						</label>
							<span> Password </span>
							<input type="password" style="width:100%" placeholder="password" name="password"/><br />		
						<label>
							<input type="hidden" value="index.php" name="location"/>
							<input type="submit" style="width:100%" />
						</label>
					</form>
				</div> 
	 		</div> <!-- end register -->
		</div> <!-- end everything-container -->
		<?php
	}
?>
