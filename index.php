<?php
session_start();
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root' , '');

if(isset($_SESSION['id'])){
	$requser= $bdd->prepare("SELECT * FROM membres WHERE id = ?");
	$requser->execute(array($_SESSION['id']));
	$user= $requser->fetch();
	header('Location: index_connect.php');
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>test</title>
	<link rel="stylesheet" type="text/css" href="style1.css">
</head>
<body>
	<header>
		<nav>
			<h1 class="titre_header">Let's Play</h1>
			<ul>
				<a href="stream.html"><li>Streamers</li></a>
				<a href="chat.html"><li>Chat</li></a>
				<a href="contact.html"><li>Contact</li></a>
			</ul>
			<a href="login.php" class="login_header">Login</a>
		</nav>
		<br>
		<h1 class="Bienvenue_header">Bienvenue dans notre SiteWeb</h1>
		<h2 class="Bienvenue2_header">On est une communauté multigaming française</h2>
	</header>

	<div class="Bienvenue_div1">
		<div class="assemblage_div1">
			<h1>Notre Discord</h1>
			<a href="https://discord.gg/chEHS6s" target="_blank"><img src="images/discord.gif"></a>
			<h3>clique sur le logo pour nous rejoindre<br> on est une communauté chill</h3>	
		</div>	
	</div>

	<div class="Bienvenue_div2">
		<div class="assemblage_div2">
			<h1>Twitch</h1>	
			<img src="images/twitch.png">
			<h3>Venez vous follow à nous sur twitch on live de tout...</h3>
		</div>	
	</div>	
</body>
</html>