<?php
if(isset($_GET['page']))
{
if(isset($_POST['login']) and isset($_POST['pass']))
{
	$_POST['login'] = mysql_real_escape_string($_POST['login']);
	$_POST['pass'] = mysql_real_escape_string($_POST['pass']);
	if(existe('accounts', 'account', $_POST['login']))
	{
		$pass = getInfo($_POST['login'], 'pass');
		if($_POST['pass'] == $pass)
		{
			$_SESSION['login'] = $_POST['login'];
			$_SESSION['level'] = getInfo($_SESSION['login'], 'level');
			$_SESSION['id'] = getInfo($_SESSION['login'], 'guid');
			$_SESSION['pseudo'] = getInfo($_SESSION['login'], 'pseudo');
			$_SESSION['banned'] = getInfo($_SESSION['login'], 'banned');
			echo '<center><font color=green>Vous etes connecte !<br>';
			echo 'Vous allez etre redirige vers la page d\'accueil</font></center><meta http-equiv="refresh" content="2 url=index.php?page=news" />';
		}else
		{
			echo '<center><font color=red>Mauvais mot de passe !<br>';
			echo 'Vous allez etre redirige vers la page d\'accueil</font></center><meta http-equiv="refresh" content="2 url=index.php?page=news" />';
		}
		}else 
		{
			echo '<center><font color=red>Nom de compte inexistant !<br>';
			echo 'Vous allez etre redirige vers la page d\'accueil</font></center><meta http-equiv="refresh" content="2 url=index.php?page=news" />';
		}
}
}?>