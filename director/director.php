<!DOCTYPE html>

<!--
	Start page of the director role-based page in the Heavy Vehicle Application
-->

<?php
	require('../page.php');
	$userID = $_GET['u'];

	 /*user info*/
	echo "<h2>User info</h2>";
    $user = new User($userID);
    $orgID = $user->orgID;
    $roleID = $user->roleID;
    
    echo "User name: ".$user->userName."<br>";
    echo "User ID: ".$userID."<br>";
    echo "email: ".$user->email. "<br>";
    echo "Organization ID: ".$orgID. "<br>";
    echo "Role ID: ".$roleID. "<br>";
	
	$idOrg = $orgID-1;
	directorStock($idOrg);
?>


<html>
<head>
	<meta charset="UTF-8">
	<title>A3 director</title>
	
	<!--load js: d3js library, jQuery/ajax and plot functions -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="directorFkns.js"></script> <!-- plot functions -->
	
	<!--plot styling -->
	<style>
		.axis path, .axis line{
            fill: none;
            stroke: black;
            shape-rendering: crispEdges;
        }
        .axis text{
            font-family: 'Arial';
            font-size: 13px;
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

	<h2>Plot of the latest 8 weeks stock market values</h2>
	
	<svg id="d3svg" width="520" height="340"></svg>

	<br><br><br> <a href='../logout.php'><button style='font-size:16px'>Logout</button></a>
	
	<script>

		var stockN = document.getElementById('stockName').textContent;
		var allData = getCSV(stockN);
		InitChart(allData);
			
	</script>
</body>
</html>

