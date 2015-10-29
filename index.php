<!DOCTYPE html>

<!--
	Linnea Strågefors, Oct 2015
	Start page of the Heavy Vehicle Application
-->

<html>
<head>
	<meta charset="UTF-8">
	<title>A3 login</title>
	<style>
		#g {height: 35px; width: 135px}
	</style>
</head>

<body align="center">
	
	<div align="center">
		<br><br><br><br><br>
	<h3>Login to A3</h1><br><br>
	
	<!-- HybridAuth library is used when signing-in with Twitter, Google and Facebook -->
	<a href="loggedin.php?provider=Twitter"><img src='images/tSignin.png'></img></a> 
	<a href="loggedin.php?provider=Google"><img id='g' src='images/GSignin.png'></img></a>
	<a href="loggedin.php?provider=Facebook"><img src='images/fbLogin.png'></img></a> 
	
	</div>

</body>
</html>
