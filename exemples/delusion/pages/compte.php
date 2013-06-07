<?php
if(isset($_GET['page']) and isset($_SESSION['login']))
{
	if(isset($_GET['quit']) and $_GET['quit'] == 1)
	{
		if(isset($_POST['pass']) and isset($_POST['rep']))
		{
			$_POST['pass'] = mysql_real_escape_string($_POST['pass']);
			$_POST['rep'] = mysql_real_escape_string($_POST['rep']);
			if($_POST['pass'] == getInfo($_SESSION['login'], 'pass'))
			{
				if($_POST['rep'] == getInfo($_SESSION['login'], 'reponse'))
				{
					supprLigne('personnages', 'account', $_SESSION['id']);
					supprLigne('accounts', 'guid', $_SESSION['id']);
					header('location: index.php?page=logout');
				}else header('location: index.php?page=compte&act=3&error=2');
			}else header('location: index.php?page=compte&act=3&error=3');
		}else header('location: index.php?page=compte&act=3&error=1');
	}
	if(isset($_GET['debug']) and is_numeric($_GET['debug']) and persoEaccount($_SESSION['id'], $_GET['debug']))
	{
		$query = 'UPDATE personnages SET cell = "'.$debug_cell.'", map = "'.$debug_map.'" WHERE guid = "'.$_GET['debug'].'"';
		mysql_query($query) or die(mysql_error());
		header('location: index.php?page=compte');
	}
	if(isset($_GET['suppr']) and is_numeric($_GET['suppr']) and persoEaccount($_SESSION['id'], $_GET['suppr']))
	{
		supprLigne('personnages', 'guid', $_GET['suppr']);
		header('location: index.php?page=compte');
	}
	if(isset($_GET['mod']) and $_GET['mod'] == 1)
	{
		if(isset($_POST['oldpass']) and isset($_POST['newpass1']) and isset($_POST['newpass2']) and isset($_POST['rep']))
		{
			$_POST['oldpass'] = mysql_real_escape_string($_POST['oldpass']);
			$_POST['newpass1'] = mysql_real_escape_string($_POST['newpass1']);
			$_POST['newpass2'] = mysql_real_escape_string($_POST['newpass2']);
			$_POST['rep'] = mysql_real_escape_string($_POST['rep']);
			if(verifName($_POST['newpass1']))
			{
				if($_POST['oldpass'] == getInfo($_SESSION['login'], 'pass'))
				{
					if($_POST['rep'] == getInfo($_SESSION['login'], 'reponse'))
					{
						if($_POST['newpass1'] == $_POST['newpass2'])
						{
							$query = 'UPDATE accounts SET pass = "'.$_POST['newpass1'].'" WHERE guid = "'.$_SESSION['id'].'"';
							mysql_query($query) or die(mysql_error());
							header('location: index.php?page=logout');
						}else header('location: index.php?page=compte&act=1&error=4');
					}else header('location: index.php?page=compte&act=1&error=3');
				}else header('location: index.php?page=compte&act=1&error=2');
			}else header('location: index.php?page=compte&act=1&error=5');
		}else header('location: index.php?page=compte&act=1&error=1');
	}elseif(isset($_GET['mod']) and $_GET['mod'] == 2)
	{
		if(isset($_POST['rep']) and isset($_POST['email']))
		{
			$_POST['rep'] = mysql_real_escape_string($_POST['rep']);
			$_POST['email'] = mysql_real_escape_string($_POST['email']);
			if(isMail($_POST['email']))
			{
				if($_POST['rep'] == getInfo($_SESSION['login'], 'reponse'))
				{
					$query = 'UPDATE accounts SET email = "'.$_POST['email'].'" WHERE guid = "'.$_SESSION['id'].'"';
					mysql_query($query) or die(mysql_error());
					header('location: index.php?page=compte');
				}else header('location: index.php?page=compte&act=2&error=2');
			}else header('location: index.php?page=compte&act=2&error=3');
		}else header('location: index.php?page=compte&act=2&error=1');
	}elseif(isset($_GET['mod']) and $_GET['mod'] == 4)
	{
		if(isset($_POST['rep']) and isset($_POST['pseudo']))
		{
			$_POST['rep'] = mysql_real_escape_string($_POST['rep']);
			$_POST['pseudo'] = mysql_real_escape_string($_POST['pseudo']);
			if(verifName($_POST['pseudo']))
			{
				if($_POST['rep'] == getInfo($_SESSION['login'], 'reponse'))
				{
					$query = 'UPDATE accounts SET pseudo = "'.$_POST['pseudo'].'" WHERE guid = "'.$_SESSION['id'].'"';
					mysql_query($query) or die(mysql_error());
					header('location: index.php?page=compte');
				}else header('location: index.php?page=compte&act=4&error=2');
			}else header('location: index.php?page=compte&act=4&error=3');
		}else header('location: index.php?page=compte&act=4&error=1');
	}
	$donnees = getAccount($_SESSION['id']);
	?>
	<p class="titrePage">Informations du Compte :</p>
	<div class="clean"></div><br>
	<table>
		<tr><td>- Nom de compte : </td><td><font color=red><?php echo $donnees['account'];?></font></td><td></td></tr>
		<tr><td>- Pseudo : </td><td><font color=red><?php echo $donnees['pseudo'];?></font></td><td><a href="index.php?page=compte&act=4"><img src="images/admin/conf.png" align="middle"/></a></td></tr>
		<tr><td>- Mot de passe : </td><td><font color=red>*****</font></td><td><a href="index.php?page=compte&act=1"><img src="images/admin/conf.png" align="middle"/></a></td></tr>
		<tr><td>- E-mail : </td><td><font color=red><?php echo $donnees['email'];?></font></td><td><a href="index.php?page=compte&act=2"><img src="images/admin/conf.png" align="middle"/></a></td></tr>
		<tr><td>- Question : </td><td><font color=red><?php echo $donnees['question'];?></font></td><td></td></tr>
		<tr><td>- Nombre de perso : </td><td><font color=red><?php echo $nbP = mysql_num_rows($pers = getPerso($_SESSION['id']));?></font></td><td></td></tr>
		<tr><td>- Level moyen : </td><td><font color=red><?php echo (int)mysql_result(mysql_query('SELECT AVG(level) FROM personnages WHERE account = "'.$_SESSION['id'].'"'), 0);?></font></td><td></td></tr>	
	</table>
	<a href="index.php?page=compte&act=3" style="text-decoration: none;"><img src="images/admin/att.png" /> Supprimer le compte <img src="images/admin/att.png" /></a>
	

		<?php
		if(!isset($_GET['act']))
		{ 
			?>
			<p class="titrePage">Ces personnages</p>
			<div class="clean"></div><br>
			<?php 
			$result = getPerso($_SESSION['id']);
			if(mysql_num_rows($result) > 0)
			{
				while($donnees = mysql_fetch_array($result))
				{
					echo '<div class="admin"><a href="index.php?page=perso&id='.$donnees['guid'].'" title="Informations" >- '.$donnees['name'].' ('.$donnees['level'].')</a></div> <a href="index.php?page=compte&suppr='.$donnees['guid'].'" title="supprimer" ><img src="images/admin/suppr.png" align="middle" /></a> <a href="index.php?page=compte&debug='.$donnees['guid'].'" title="Débug le perso" ><img src="images/admin/fleche.png" align="middle"/></a><br><br>';
				}
			}else echo 'Pas de personages trouvés !';
		}elseif (isset($_GET['act']) and $_GET['act'] == 1)
		{?>
			<?php 
			if(isset($_GET['error']) and is_numeric($_GET['error']))
			{
				switch ($_GET['error'])
				{
					case 1 : $error = 'Veuillez remplir tout les champs !';
					break;
					case 2 : $error = 'Mot de passe incorrect !';
					break;
					case 3 : $error = 'Mauvaise réponse secrète !';
					break;
					case 4 : $error = 'Les deux mots de passe sont différents !';
					break;
					case 5 : $error = 'Le mot de passe ne respecte pas les éxigences !';
					break;
					default:$error = 'Indéfinie !';
					break;
				}
				echo '<center><font color=red>Erreur : '.$error.'</font></center><br>';	
			}
			?>
			<img src="images/retour.png" name="retour" onmouseover="retour.src='images/retour2.png'" onmouseout="retour.src='images/retour.png'" onclick="history.go(-1);" style="float: left;" title="retour" /><h2 align="center">Modifier le mot de passe : </h2><br><br>
			<center><form action="index.php?page=compte&mod=1" method="post" >
				Ancien Mot de passe : <br>
				<input type="password" name="oldpass" /><br>
				Nouveau Mot de passe* : <br>
				<input type="password" name="newpass1" /><br>
				Retapez-le : <br>
				<input type="password" name="newpass2" /><br><br>
				Question : <font color=red><?php echo $donnees['question'];?></font><br>
				<input type="text" name="rep" /><br><br>
				<input type="image" src="images/bouttons/envoyer.png" /><br><br>
				
				*Seul les chiffres, les lettres, et ".", "_", "-" sont autorisés, et de 4 à 32 caractères.<br>
			</form></center>
		<?php 	
		}elseif(isset($_GET['act']) and $_GET['act'] == 2)
		{?>
		<img src="images/retour.png" name="retour" onmouseover="retour.src='images/retour2.png'" onmouseout="retour.src='images/retour.png'" onclick="history.go(-1);" style="float: left;" title="retour" /><h2 align="center">Modifier l'adresse e-mail :</h2><br><br>
		<?php 
			if(isset($_GET['error']) and is_numeric($_GET['error']))
			{
				switch ($_GET['error'])
				{
					case 1 : $error = 'Veuillez remplir tout les champs !';
					break;
					case 2 : $error = 'Mauvaise réponse secrète !';
					break;
					case 3 : $error = 'L\'adresse e-mail ne respecte pas les éxigences !';
					break;
					default:$error = 'Indéfinie !';
					break;
				}
				echo '<center><font color=red>Erreur : '.$error.'</font></center><br>';	
			}
		?>
		<center><form action="index.php?page=compte&mod=2" method="post">
			Nouvelle e-mail* : <br>
			<input type="text" name="email" /><br>
			Question : <font color=red><?php echo $donnees['question'];?></font><br>
			<input type="text" name="rep" /><br><br>
			<input type="image" src="images/bouttons/envoyer.png" /><br><br>
			
			*L'adresse e-mail doit suivre ce schéma : xxx@yyy.zz
		</form></center>
		<?php 	
		}elseif (isset($_GET['act']) and $_GET['act'] == 3)
		{
			if(isset($_GET['error']) and is_numeric($_GET['error']))
			{
				switch ($_GET['error'])
				{
					case 1 : $error = 'Veuillez remplir tout les champs !';
					break;
					case 2 : $error = 'Mauvaise réponse secrète !';
					break;
					case 3 : $error = 'Mauvais mot de passe !';
					break;
					default:$error = 'Indéfinie !';
					break;
				}
				echo '<center><font color=red>Erreur : '.$error.'</font></center><br>';	
			}
			?>
			<img src="images/retour.png" name="retour" onmouseover="retour.src='images/retour2.png'" onmouseout="retour.src='images/retour.png'" onclick="history.go(-1);" style="float: left;" title="retour" /><h2 align="center">Supprimer le compte</h2><br><br>
			<center><form action="index.php?page=compte&quit=1" method="post">
				<font color=red>/!\ attention cette action est irréversible, vous n'aurez pas la possibliter de récupérer vos personnages ou vos points boutiques. /!\</font><br><br>
				Mot de passe : <br>
				<input type="text" name="pass" /><br>
				Question : <font color=red><?php echo $donnees['question'];?></font><br>
				<input type="text" name="rep" /><br><br>
				<input type="image" src="images/bouttons/envoyer.png" /><br><br>
			</form></center>
			<?php 
		}elseif(isset($_GET['act']) and $_GET['act'] == 4)
		{?>
			<img src="images/retour.png" name="retour" onmouseover="retour.src='images/retour2.png'" onmouseout="retour.src='images/retour.png'" onclick="history.go(-1);" style="float: left;" title="retour" /><h2 align="center">Modifier le Pseudo :</h2><br><br>
			<?php 
				if(isset($_GET['error']) and is_numeric($_GET['error']))
				{
					switch ($_GET['error'])
					{
						case 1 : $error = 'Veuillez remplir tout les champs !';
						break;
						case 2 : $error = 'Mauvaise réponse secrète !';
						break;
						case 3 : $error = 'Le pseudo ne respecte pas les exigences !';
						break;
						default:$error = 'Indéfinie !';
						break;
					}
					echo '<center><font color=red>Erreur : '.$error.'</font></center><br>';	
				}
			?>
			<center><form action="index.php?page=compte&mod=4" method="post">
				Nouveau Pseudo* : <br>
				<input type="text" name="pseudo" /><br>
				Question : <font color=red><?php echo $donnees['question'];?></font><br>
				<input type="text" name="rep" /><br><br>
				<input type="image" src="images/bouttons/envoyer.png" /><br><br>
			
				*Le pseudo ne doit contenir que ces caractères : a-z A-Z 0-9 ._-
			</form></center>
			<?php 	
		}
} ?>