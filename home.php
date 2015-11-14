<?php
	//session_destroy();
	function displayHomeHTML()
	{
		include_once "login.php";
		$auth = validateUser();
		if($auth == 0)
		{
			echo "<div id=\"home\">";
			echo "<div class=\"everything-containment\">";
					
			generateUploadForm();
			echo "</div> <!-- end everything-containment -->";
			echo "</div>";
		}
		else if($auth == 1)
		{
			?>
			<link rel="stylesheet" type="text/css" href="generalStyle.css">
			<link rel="stylesheet" type="text/css" href="home.css">
			<div class="middle-containment">
					<!--<div class="navigation">
						<div id="tab" onclick="editTab('problemList')"><div id="tabText">list</div></div>
						<div id="tab"><div id="tabText">users</div></div>
						<div id="tab" onclick="editTab('uploadForm')"><div id="tabText">upload</div></div>
						<div id="tab"><div id="tabText">alcohol</div></div>
					</div>-->
				<div class=ul-containment id="four"> 
					<ul id="menu">
						<li><a id="left" href="?page=problemList">Problems</a></li>
						<li><a id="middle" href="?page=users">Users</a></li>	
						<li><a id="middle" href="?page=uploadProblem">Upload</a></li>				
						<li><a id="right" href="/dvDrunkCode/inputalcohol.html">Förra året</a></li>
					</ul>  
				</div> <!-- end ul-containment -->
				<div class="everything-containment">
					
					<?php 
						if(!isset($_GET["page"]) || $_GET["page"] == "problemList")
							include "problemList.html";
						else if($_GET["page"] == "uploadProblem")
							echo "<div id=\"uploadForm\">" . generateUploadForm() . "</div>";
						else if($_GET["page"] == "users")
							include "users.php";
					?>
				</div> <!-- end everything-containment -->
			</div> <!-- end middle-containment -->
			
				
			<?php
		}
	}
	function generateUploadForm()
	{
		?>
		<link rel="stylesheet" type="text/css" href="generalStyle.css">
		<link rel="stylesheet" type="text/css" href="home.css">
		<link rel="stylesheet" type="text/css" href="form.css">

		<div id="description">
			<text1 id="heading"> 
				Upload problem 
			</text1>
			<text2>You can upload your problems here!<br /></br>
			You will <b>NOT</b> be allowed to complete your own problems once the competition starts<br /></br>
					The description should be enough to complete the problem for any given input. You are allowed to use html tags to style your description to better get your point across(note that we might make some changes to it).<br /></br>
					One non-trivial input string should be provided, and the corresponding output. For example if the problem is getting the n-th fibbonaci number the input should be hard enough not to calculate in you head, so the 47:nd fibbonaci number would be a reasonable input with the output 2971215073.<br /> </br>
			</text2>
		</div> <!-- end #description-->
		<form method="POST" action="uploadProblem.php" class="ourForm">
			<label>
				<span id="heading">Title</span>
				<span> Input the title of the problem you have created. </span>
				<input class="i" type="text" name="title" />
			</label>
			<label>
				<span id="heading">Description</span>
				<span> Your problem description. Try to be as clear as possible. </span>
				<textarea class="i" id="desc" name="description"></textarea>
			</label>
			<label>
				<span id="heading">Input</span>
				<span> The input which will give the desired output. </span>
				<input class="i"  type="text" name="input" />
			</label>
			<label>
				<span id="heading">Output</span>
				<span> The output given the input above. </span>
				<input class="i" type="text" name="output" />
			</label>
			<label>
			<input type="submit" />
			</label>
		</form>
		<?php
	}
?>
