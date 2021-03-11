<?php
session_start();
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root' , '');

if(isset($_SESSION['id'])){
	$requser= $bdd->prepare("SELECT * FROM membres WHERE id = ?");
	$requser->execute(array($_SESSION['id']));
	$user= $requser->fetch();

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
	<div align="center" class="profildiv">
		<a href="index_connect.php"><h1><?php echo $user['pseudo']; ?></h1></a>
		<?php { ?>
		<a href="index_connect.php"><img src="membres/avatar/<?php echo $user['avatar']?>"width="150px" height="150px" class="avatar"></a>
		<?php } ?>		
	</div>
	<a href="deconnexion.php" class="bouttonDECO">Se deconnecter</a>
	<?php if(isset($_SESSION['id']) AND $user['id'] == $_SESSION['id'])
	{
	?>
	<span class="modifPROF1"><a href="modifprofil.php" class="modifPROF">Modifier mon profil</a>
	<?php	
	} 
	?>
</body>
</html>

<?php
}
else
{
	header('Location: login.php');
}			
?>