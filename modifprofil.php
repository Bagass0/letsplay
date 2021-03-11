<?php
session_start();
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root' , '');

if(isset($_SESSION['id']))
{
	$requser= $bdd->prepare("SELECT * FROM membres WHERE id = ?");
	$requser->execute(array($_SESSION['id']));
	$user= $requser->fetch();

	if(isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudo'])
	{
		$newpseudo= htmlspecialchars($_POST['newpseudo']);
		$reqpseudo= $bdd->prepare("SELECT * FROM membres WHERE pseudo = ?");
		$reqpseudo->execute(array($newpseudo));
		$pseudoexist= $reqpseudo->rowcount();

		if($pseudoexist ==0)
		{
			$insertpseudo= $bdd->prepare("UPDATE membres SET pseudo = ? WHERE id = ?");
			$insertpseudo->execute(array($newpseudo, $_SESSION['id']));
			$ok1= "Votre pseudo a bien été changé !";

		}else $erreur1 = "Pseudo déjà existant !";
	}		

			
	if(isset($_POST['newmail']) AND !empty($_POST['newmail']) AND $_POST['newmail'] != $user['mail'])
	{
		$newmail= htmlspecialchars($_POST['newmail']);
		$reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
		$reqmail->execute(array($newmail));
		$mailexist = $reqmail->rowcount();

		if(filter_var($newmail, FILTER_VALIDATE_EMAIL) )
		{

			if($mailexist ==0)
			{
				$insertmail= $bdd->prepare("UPDATE membres SET mail = ? WHERE id = ?");
				$insertmail->execute(array($newmail, $_SESSION['id']));
				$ok2= "Votre mail a bien été changé !";

			}else $erreur2 = "Email déjà existant !";
		}else $erreur2 = "Email invalide !";
	}

    if(isset($_POST['newMdp1']) AND !empty($_POST['newMdp1']) AND isset($_POST['newMdp2']) AND !empty($_POST['newMdp2']))
    {
    	$newMdp1= sha1($_POST['newMdp1']);
		$newMdp2= sha1($_POST['newMdp2']);

		if($newMdp1 == $newMdp2)
		{
			$insertmdp= $bdd->prepare("UPDATE membres SET Mdp = ? WHERE id = ?");
			$insertmdp->execute(array($newMdp1, $_SESSION['id']));
			$ok3= "Votre mot de passe a bien été changé !";

		}else $erreur3 = "Vos mot de passe ne sont pas indentiques !";	

    }

    if(isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name']))
	{
		$tailleMax= 2097152;
		$extensionValide= array('jpg', 'jpeg', 'gif', 'png');

		if($_FILES['avatar']['size'] <= $tailleMax)
		{
			$extensionUpload=  strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));

			if(in_array($extensionUpload, $extensionValide))
			{
				$chemin= "membres/avatar/" .$_SESSION['id'] ."." .$extensionUpload;
				$resultat= move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);

				if($resultat)
				{
					$updateAvatar = $bdd->prepare('UPDATE membres SET avatar = :avatar WHERE id= :id');
					$updateAvatar->execute(array('avatar' => $_SESSION['id'] ."." .$extensionUpload, 'id' =>$_SESSION['id']));
					header('Location: profil.php');


				}else $erreur= "Erreur pendant importation du fichier !";	

			}else $erreur= "Votre photo de profil na pas le bon format !";	

		}else $erreur= "votre photo de profil ne doit pas dépasser 2Mo"; 		

	}			

?>
<!DOCTYPE html>
<html>
<head>
	<title>Profil</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="shortcut icon" type="images/png" href="logo.png">
</head>
<body class="wallpaper2">

	<form method="POST" action="" enctype="multipart/form-data">
		<?php { ?>
		<img src="membres/avatar/<?php echo $user['avatar']?>" class="avatar" width="150px" height="150px">
		<?php } ?>
			
		<div class="bouttonFileDiv">	
			<input type="file" id="chooseAfile" name="avatar" style="display: none;">
			<label for="chooseAfile" class="bouttonFile">Upload</label>
			<input type="submit" name="" class="bouttonFile1" value="Enregistrer">
			<?php if(isset($erreur)) {echo '<font color="#ff0000">'.$erreur ."</font><br>";} ?>
		</div>	
	</form>

	<form method="POST" action="" class="box" >
		<h2 class="editProfil">Modifier mon profil</h2>
		<label class="label1">Pseudo :</label>
		<input type="text" name="newpseudo" placeholder="Pseudo" minlength="4" maxlength="20" value="<?php echo $user['pseudo']; ?>">
		<?php if(isset($erreur1)) {echo '<font color="#ff0000">'.$erreur1 ."</font><br><br>";} ?>
		<?php if(isset($ok1)) {echo '<font color="##04ff00">'.$ok1 ."</font><br><br>";} ?>
		<label class="label1">Email :</label>
		<input type="email" name="newmail" placeholder="email" value="<?php echo $user['mail']; ?>">
		<?php if(isset($erreur2)) {echo '<font color="#ff0000">'.$erreur2 ."</font><br><br>";} ?>
		<?php if(isset($ok2)) {echo '<font color="##04ff00">'.$ok2 ."</font><br><br>";} ?>
		<label class="label1">mot de passe :</label>
		<input type="password" name="newMdp1" placeholder="nouveau mot de passe" >
		<input type="password" name="newMdp2" placeholder="confirmer mot de passe">
		<?php if(isset($erreur3)) {echo '<font color="#ff0000">'.$erreur3 ."</font><br><br>";} ?>
		<?php if(isset($ok3)) {echo '<font color="##04ff00">'.$ok3 ."</font><br><br>";} ?>
		<input type="submit" name="majprofil" value="Mettre à jour">
		<a href="profil.php"class="register">Back</a>
	</form>
</html>

<?php
}
else
{
	header('Location: index.php');
}


?>