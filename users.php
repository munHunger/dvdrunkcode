<?php
	if(isset($_GET["action"]) && $_GET["action"] == "updateUser")
	{
		include_once "connect.php";
		$id = runQuery("INSERT INTO `score` (`problemID`, `userID`) VALUES(:problemID, :userID)", array(':problemID' => '-2', ':userID' => $_GET["nick"]));
		runQuery("INSERT INTO `adminScore` (`amount`, `ID`) VALUES(:amount, :ID)", array(':amount' => $_GET["value"], ':ID' => $id));
		die("");
	}
	include_once "getInfo.php";
	$qry = mysql_query("SELECT * FROM `user`");
	echo "<hr />";
	while(($row = mysql_fetch_assoc($qry)) != null)
	{
		echo $row["nick"] . ":" . array_sum(getUserPoints($row["ID"])) . "<span style=\"position:absolute; right:50px;\"><input style=\"width:50px;\" type=\"number\" value=\"" . "0" /*array_sum(getUserPoints($row["ID"]))*/ . "\" onchange=\"changeScore('" . $row["ID"] . "', this);\"></span><hr />";
	}
?>
<script type="text/javascript">
	function changeScore(nick, input)
	{
		httpGet("users.php?action=updateUser&nick=" + nick + "&value=" + input.value);
		input.value = 0;
	}
</script>