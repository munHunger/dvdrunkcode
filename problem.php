<?php
	session_start();
	include_once "connect.php";
	include_once "login.php";
	$auth = validateUser();
	if($auth == 1)
	{
		if(!isset($_GET["ID"]))
		{
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"problem.css\" />";
			$problemIDs = mysql_query("SELECT `ID` FROM `problem`");
			while($row = mysql_fetch_assoc($problemIDs))
			{
				echo "<div id=\"" . $row["ID"] . "\" >";
				displayProblem($row["ID"]);
				echo "</div>";
			}
		}
		else
		{
			displayProblem($_GET["ID"]);
		}
	}
	function displayProblem($id)
	{
		$adminCount = mysql_num_rows(mysql_query("SELECT * FROM `user` WHERE `isAdmin`='1'"));
		$problemQry = mysql_query("SELECT * FROM `problem` WHERE `ID`='" . $id . "'");
		while($problemRow = mysql_fetch_assoc($problemQry))
		{
			echo "<hr />";
			$ratingQry = mysql_query("SELECT * FROM `problemRating` WHERE `problemID`='" . $problemRow["ID"] . "'");
			if(mysql_num_rows($ratingQry) >= $adminCount)
			{
				$rating = round(mysql_fetch_assoc(mysql_query("SELECT AVG(`rating`) AS `rating` FROM `problemRating` WHERE `problemID`='" . $problemRow["ID"] . "'"))["rating"]);
				
				?>
					<div class="problem" onclick="toggleFold(<?php echo $problemRow["ID"]; ?>);">
						<div id="title">
							<?php echo $problemRow["title"]; ?>
						</div>
						<div id="rating">
							<div style="margin:5px;">
								<?php echo $rating; ?>
							</div>
						</div>
					</div>
					<div class="rollout">
						<div id="description">
							<text2> <?php echo $problemRow["description"]; ?> </text2>
						</div>
						<div id="input">
							<h3>input</h3>
							<?php echo $problemRow["input"]; ?>
							<form>
								<input type="text" placeholder="output" />
								<br />
								<input type="submit">
							</form>
						</div>
					</div>
				<?php
			}
			else
			{

				?>
					<div class="problem" onclick="toggleFold(<?php echo $problemRow["ID"]; ?>);">
						<div id="title">
							<?php echo $problemRow["title"]; ?>
						</div>
						<div id="rating">
							<div style="margin:5px;" onclick="updateRating(prompt('rating?'), <?php echo $problemRow["ID"]; ?>);">
							set
							</div>
						</div>
					</div>
					<div class="rollout">
							<text2>	<?php echo $problemRow["description"]; ?> </text2>
						<div id="input">
							<h3>input</h3>
							<?php echo $problemRow["input"]; ?>
							<form>
								<input type="text" placeholder="output" />
								<br />
								<input type="submit">
							</form>
						</div>
					</div>
				<?php
			}
		}
	}
?>
