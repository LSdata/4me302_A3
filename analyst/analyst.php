<!DOCTYPE html>

<!--
	Start page of the analyst role-based website
-->

<?php
	//display user info on the analyst page
	require('analystXML.php');
	require('../datas/dbDials.php');

	$userID = $_GET['u'];

	echo "<h2>User info</h2>";
    $user = new User($userID);
    $orgID = $user->orgID;
    $roleID = $user->roleID;
    
    echo "User name: ".$user->userName."<br>";
    echo "User ID: <p id='userID' style='display:inline'>".$userID."</p><br>";
    echo "email: ".$user->email. "<br>";
    echo "Organization ID: ".$orgID. "<br>";
    echo "Role ID: ".$roleID. "<br>";
	
	//display vehicle info 
	echo "<br><h2>Analyst role</h2>";
	AnalystVehicles($orgID);
	
	//annotation feature
	echo '<br><h3>Annotation</h3>
		<textarea id="textNote" rows="8" cols="60">'.getAnnotation($userID).'</textarea>';		
?>

<html>
<head>
	<meta charset="UTF-8">
	<title>A3 analyst</title>
	
	<!--load js: d3js, jQuery and plot functions -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="analystPlots.js"></script>
	
	<!--plot styling -->
	<style>
		.axis path, .axis line{
            fill: none;
            stroke: black;
            shape-rendering: crispEdges;
        }
        .axis text{
            font-family: 'Arial';
            font-size: 12px;
        }
        .tick{
            stroke-dasharray: 1, 2;
        }
		.text-label {
			font-size: 13px;
			font-family: 'Arial';
		}
		
	</style>
</head>

<body>
	
	<!--Annotation -->
	<br><button onclick="newNote()">Save</button>
	<p id="saved" style='display: inline; color: green;'></p>

<!--logout -->
	<br><br><br> <a href='../logout.php'><button style='font-size:16px'>Logout</button></a>
	
	<script>

	//show more: plot diagrams
	function showDivRunInfo(){
		document.getElementById('runInfo').style.display = "block";
		document.getElementById('moreInfo').style.display = "none";		
	}
	
	//show less: hide plot diagrams
	function showLess(){
		document.getElementById('runInfo').style.display = "none";
		document.getElementById('moreInfo').style.display = "block";
	}
		var logName1 = document.getElementById('logname1').textContent;
		var logName2 = document.getElementById('logname2').textContent;
		var logData1 = getLogfile(logName1);
		var logData2 = getLogfile(logName2);

		InitChart(logData1,"d3svg", logName1);
		InitChart(logData2,"d3svg2", logName2);
		
		function newNote() {
			var note = document.getElementById('textNote').value;    
			saveNote(note);
		}

	//save annotation when 'save' button is clicked
	function saveNote(note){
		var temp="no return value";
		var userID = parseInt(document.getElementById('userID').innerHTML);    
		
		//call to my web server backend to save the annotation in the database table 'users'
        $.ajax({
            type: "GET", 
            url: 'backend/index.php',
            data: {key: '4me302A3', method:'newnote', value1:userID, value2:note},
            async: false, 
            dataType: "json"
		})
        .done(function(data){
			temp="ajax newnote ok";
		})
		.fail(function() {
			temp= "ajax new note error";
		});
			var savedOk = document.getElementById('saved');    
			savedOk.innerHTML = "<i>Saved<i>";
	}
			
	</script>
</body>
</html>