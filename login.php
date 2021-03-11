<?php
session_start();

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root' , '');

if(isset($_POST['connect']))
{
	$emailconnect = htmlspecialchars($_POST['emailconnect']) ;
	$mdpconnect = sha1($_POST['passwordconnect']);
	if(!empty($emailconnect) AND !empty($mdpconnect) )
	{ 
		$requser = $bdd->prepare("SELECT * FROM membres WHERE mail = ? AND Mdp = ? ");
		$requser->execute(array($emailconnect, $mdpconnect));
		$userexist = $requser->rowcount(); 

		if($userexist == 1)
		{
			$userinfo = $requser->fetch();
			$_SESSION['id'] = $userinfo['id'];
			$_SESSION['pseudo'] = $userinfo['pseudo'];
			$_SESSION['mail'] = $userinfo['mail'];
			header('Location: index_connect.php'); 	
		}
		else
		{
			$erreur = "Mauvais email ou mot de passe !";
		}	
	}
	else
	{
		$erreur = "Tous les champs doivent être complétés !";
	}	
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="shortcut icon" type="images/png" href="logo.png">
</head>
<body class="wallpaper1">
	<form class="box" method="post">
		<h1 class="login">Identifiant</h1>
		<input type="email" name="emailconnect" placeholder="email">
		<input type="password" name="passwordconnect" placeholder="Mot de passe">
		<?php if(isset($erreur)) {echo '<font color="yellow">'.$erreur ."</font>";} ?>
		<input type="submit" name="connect" value="Connecter">
		<a href="register.php" class="register">s'enregistrer</a>
	</form>
</body>
</html>