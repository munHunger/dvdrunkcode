<?php
	session_start();
	include "connect.php";

	function printLeader() {
		echo ("<div class=\"leader\">
			<p id=points1 class=\"leader-points\"></p>				
			<p id=name1 class=\"leader-name\"></p>
		</div> <!-- end leader -->");
	}
	function printCloseBy($id) {
		echo ("<div class=\"close-by\">
			<p id=points" . $id . " class=\"close-by-points\"></p>
			<p id=name" . $id . " class=\"close-by-name\"></p>
		</div> <!-- end close-by -->");
	}
	function printLooser($id) {
		echo ("<div class=\"looser\">
			<p id=points" . $id . " class=\"looser-points\"><p>
			<p id=name" . $id . " class=\"looser-name\"></p>
		</div> <!-- end looser -->");
	}
	function createScoreBoard() {
		echo ("<div class=\"score-board\">");
			printLeader();			
		echo ("<div class=\"close-by-containment\">");
		for ($i = 2; $i <= 4; $i++) {
			printCloseBy($i);		
		}
		echo ("</div> <!-- end close-by-containment -->
			<div class=\"looser-containment-containment\">");
		for ($i = 5; $i <= 24; $i = $i+5) {
			echo ("<div id=baloo" . $i ." class=\"looser-containment\">");
			for ($j = 0; $j < 5; $j++) {
				printLooser($i + $j);
			}
			echo ("	</div> <!-- end looser-containment -->");
		}	
		echo ("	</div> <!-- end looser-containment-containment -->
			</div> <!-- end score-board -->");
	}
?>

<!DOCTYPE html>
<html>
  <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Fylleprogrammering</title>

		<link rel="stylesheet" type="text/css" href="generalStyle.css">
    <link rel="stylesheet" type="text/css" href="scoreStyle.css">

		<script>
			function httpGet(theUrl) {
			    var xmlHttp = new XMLHttpRequest();
			    xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
			    xmlHttp.send( null );
			    return xmlHttp.responseText;
			}
			function updatePlacing(place, name, points) {
				document.getElementById("points" + place).innerHTML = points;
				document.getElementById("name" + place).innerHTML = name;
			}
			function changePointFontSize(place) {
				if (place == 1) {
					document.getElementById("points" + place).style.fontSize = "130px";
					document.getElementById("name" + place).style.marginTop = "-50px";
				} else if (place <= 4) {
					document.getElementById("points" + place).style.fontSize = "65px";
					document.getElementById("name" + place).style.marginTop = "-25px";
				} else {
					document.getElementById("points" + place).style.fontSize = "40px";
					document.getElementById("name" + place).style.marginTop = "-7px";
				}
			}
			function changeBackground(m) {
				var body = document.getElementsByTagName('body')[0];
				if(m < 8) {
					body.style.backgroundImage = 'url("images/bakgrund.jpg")';
				}	else if (m < 17) {
					body.style.backgroundImage = 'url("images/1.png"), url("images/bakgrund.jpg")';
				}	else if (m < 26) {
					body.style.backgroundImage = 'url("images/1.png"), url("images/2.png"), url("images/bakgrund.jpg")';
				}	else if (m < 35) {
					body.style.backgroundImage = 'url("images/1.png"), url("images/3.png"), url("images/2.png"),  url("images/bakgrund.jpg")';
				}	else if (m < 44) {
					body.style.backgroundImage = 'url("images/1.png"), url("images/3.png"), url("images/4.png"), url("images/2.png"),  url("images/bakgrund.jpg")';
				}	else if (m < 53) {
					body.style.backgroundImage = 'url("images/1.png"),  url("images/5.png"), url("images/3.png"), url("images/4.png"), url("images/2.png"),  url("images/bakgrund.jpg")';
				}	else {
					body.style.backgroundImage = 'url("images/1.png"),  url("images/5.png"), url("images/6.png"), url("images/3.png"), url("images/4.png"), url("images/2.png"),  url("images/bakgrund.jpg")';
				}		
			}
			function runScoreBoard() {
				var m = httpGet("counter.php?type=minutes");
				changeBackground(parseInt(m));

				var a = httpGet("getInfo.php?action=getAll").split("@");
				for(var i=0; i < a.length-1 && (i/2)+1 <= 24; i = i+2) {
					if (a[i].length >= 3) {	
						changePointFontSize((i/2)+1);
					} 
					updatePlacing((i/2)+1, a[i+1].substring(0, 12), a[i]);
				} 
			}
		</script>
  </head>

  <body>
		<div class ="background">
    <div class="info-box-containment"> 
			<?php
				createScoreBoard();
			?>
			<script>
				runScoreBoard();
				setInterval(function(){ runScoreBoard(); }, 10000);
			</script>
    </div>  <!-- end info-box-containment -->
		</div> <!-- end background -->
	

  </body>

</html>


