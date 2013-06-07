<?php
include '../inc/config.php';
include '../inc/function.php';
$con = mysql_connect($ip_db, $user_db, $pass_db) or die(mysql_error());
mysql_select_db($db_name, $con) or die(mysql_error());

if(isset($_GET['act']))
{
	$_POST['ndc'] = mysql_real_escape_string($_POST['ndc']);
	$_POST['mdp1'] = mysql_real_escape_string($_POST['mdp1']);
	$_POST['mail'] = mysql_real_escape_string($_POST['mail']);
	$_POST['pseudo'] = mysql_real_escape_string($_POST['pseudo']);
	$_POST['quest'] = mysql_real_escape_string($_POST['quest']);
	$_POST['rep'] = mysql_real_escape_string($_POST['rep']);
	if(isset($_POST['ndc']) and isset($_POST['mdp1']) and isset($_POST['mdp2']) and isset($_POST['pseudo']) and isset($_POST['quest']) and isset($_POST['rep']) and isset($_POST['mail']) and $_POST['ndc'] != "" and $_POST['mdp1'] != "" and $_POST['quest'] != "" and $_POST['rep'] != "" and $_POST['mail'] != "" and $_POST['pseudo'] != "")
	{
		if($_POST['mdp1'] == $_POST['mdp2'])
		{
			if(verifName($_POST['mdp1']) and verifName($_POST['ndc']) and verifName($_POST['pseudo']) and isMail($_POST['mail']))
			{
				if(!existe('accounts', 'account', $_POST['ndc']) and !existe('accounts', 'pseudo', $_POST['pseudo']))
				{
					$query = 'INSERT INTO accounts (account,pass,email,pseudo,question,reponse) VALUES("'.$_POST['ndc'].'","'.$_POST['mdp1'].'","'.$_POST['mail'].'","'.$_POST['pseudo'].'","'.$_POST['quest'].'","'.$_POST['rep'].'")';
					mysql_query($query) or die(mysql_error());
					echo '<center><font color=green><b>Compte créé avec succès !</b></font></center>';
					echo '<meta http-equiv="refresh" content="2 url=register.php" />';
				}else header("location: register.php?err=3");
			}else header("location: register.php?err=4");
		}else header("location: register.php?err=1");
	}else header("location: register.php?err=2");
}

?>
<?php 
			if(isset($_GET['err']) and is_numeric($_GET['err']))
			{
				switch ($_GET['err'])
				{
					case 1 : $erreur = 'Mots de passe différents !';
					break;
					case 2 : $erreur = 'Veuillez remplir tout les champs !';
					break;
					case 3 : $erreur = 'Nom de compte ou pseudo déjà existant !';
					break;
					case 4 : $erreur = 'Veuillez verifier si vos informations sont valide ou n\'utilisent pas de caractères spéciaux';
					break;
					default: $erreur = 'Indefinie';
					break;
				}
				echo '<br><center><font color=red>Erreur : '.$erreur.'</font></color><br><br>';
			}
		?>
<form action="register.php?act=1" method="post">
	<div style="float: left">Nom de compte* :<br> <input type="text" name="ndc" /></div>
	<div style="float: right">Pseudo* :<br> <input type="text" name="pseudo" /></div>
	<br>
	<div style="float: left">Mot de passe* :<br> <input type="password" name="mdp1" /></div>
	<div style="float: right">Retapez-le :<br> <input type="password" name="mdp2" /></div>
	<br>
	<div style="float: left">Question secrète :<br> <input type="text" name="quest" /></div>
	<div style="float: right">Réponse secrète :<br> <input type="text" name="rep" /></div>
	<br><br>
	<center>email** :<br> <input type="text" name="mail" />
	<br>
	<input type="submit" value="Envoyer" /><br><br></center>
				
	*Seul les chiffres, les lettres, et ".", "_", "-" sont autorisés, et de 4 à 32 caractères.<br>
	**L'adresse e-mail doit suivre ce schéma : xxx@yyy.zz
</form>