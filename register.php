<?php

$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root' , '');

if(isset($_POST['confirmer']) ){
	$pseudo = htmlspecialchars($_POST['pseudo']);
	$email = htmlspecialchars($_POST['email']);
	$password1 = sha1($_POST['password1']);
	$password2 = sha1($_POST['password2']);


	if(!empty($_POST['email']) AND !empty($_POST['pseudo']) AND !empty($_POST['password1']) AND !empty($_POST['password2']) ){

		$pseudolength = strlen($pseudo);

		if ($pseudolength <= 20) 
		{
			$reqpseudo = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ?");
			$reqpseudo->execute(array($pseudo));
			$pseudoexist = $reqpseudo->rowcount();

			if($pseudoexist == 0)
			{

				if(filter_var($email, FILTER_VALIDATE_EMAIL) )
				{
					$reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
					$reqmail->execute(array($email));
					$mailexist = $reqmail->rowcount();

					if($mailexist == 0)
					{ 

						 if($password1 == $password2)
						{

					 	 	$insertmbr = $bdd->prepare("INSERT INTO membres(avatar, pseudo, mail, Mdp, confirmkey, confirme) VALUES(?, ?, ?, ?, ?, ?)");
						 	$insertmbr->execute(array("default.jpg", $pseudo, $email, $password1, $key, "0"));
						 	header('Location: login.php');
					 	
					 	}else $erreur3 = "Vos mot de passes ne correspondent pas !";

				     }else $erreur2 = "Adresse mail déjà utilisée !";
				
				}else $erreur2 = "Votre adresse mail n'est pas valide";	 
			
			}else $erreur1 = "Pseudo déjà existant !";
				
		}else $erreur1 = "Votre pseudo ne doit pas dépasser 20 caractères";

	}else $erreur= "Les cases sont vides !";
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>s'enregistrer</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="shortcut icon" type="images/png" href="logo.png">
</head>
<body class="wallpaper1">
	<form class="box"  method="post">
		<h1 class="login">S'enregistrer</h1>
		<input type="email" name="email" placeholder="email" required=""/>
		<?php if(isset($erreur2)) {echo '<font color="#ff0000">'.$erreur2 ."</font>";} ?>
		<input type="text" name="pseudo" placeholder="Pseudo" required="" minlength="4" maxlength="20"/>
		<?php if(isset($erreur1)) {echo '<font color="#ff0000">'.$erreur1 ."</font>";} ?>
		<input type="password" name="password1" placeholder="Mot de passe" required=""/>
		<input type="password" name="password2" placeholder="confirmer mot de passe" required=""/>
		<?php if(isset($erreur3)) {echo '<font color="#ff0000">'.$erreur3 ."</font>";} ?>
		<input type="submit" name="confirmer" value="confirmer"/>
		<a href="login.php" class="register">login</a>
		<?php if(isset($erreur)) {echo '<font color="#ff0000">'.$erreur ."</font>";} ?>
	</form>
</body>
</html>