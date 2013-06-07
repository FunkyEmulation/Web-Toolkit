<?php
if(isset($_GET['page']) and isset($_SESSION['level']) and $_SESSION['level'] >= $level_admin)
{ 
	if(isset($_GET['unban']) and is_numeric($_GET['unban']) and $_SESSION['level'] > getLevel($_GET['unban']))
	{
		$query = 'UPDATE accounts SET banned = "0" WHERE guid = "'.$_GET['unban'].'"';
		mysql_query($query) or die(mysql_error());
		header('location: index.php?page=compte_admin&id='.$_GET['unban']);
	}
	if(isset($_GET['ban']) and is_numeric($_GET['ban']) and $_SESSION['level'] > getLevel($_GET['ban']))
	{
		$query = 'UPDATE accounts SET banned = "1" WHERE guid = "'.$_GET['ban'].'"';
		mysql_query($query) or die(mysql_error());
		header('location: index.php?page=compte_admin&id='.$_GET['ban']);
	}
	if(isset($_GET['suppr']) and is_numeric($_GET['suppr']) and $_SESSION['level'] > getLevel($_GET['id']))
	{
		supprLigne('personnages', 'account', $_GET['suppr']);
		supprLigne('accounts', 'guid', $_GET['suppr']);
		header('location: index.php?page=compte_admin');
	}
	if(isset($_GET['banip']))
	{
		$_GET['banip'] = mysql_real_escape_string($_GET['banip']);
		$query = 'INSERT INTO banip(ip) VALUES("'.$_GET['banip'].'")';
		mysql_query($query) or die(mysql_error());
		header('location: index.php?page=compte_admin');
	}
	if(isset($_GET['purge']))
	{
		$query = 'SELECT * FROM accounts';
		$retour = mysql_query($query) or die(mysql_error());
		while ($donnees = mysql_fetch_array($retour))
		{
			if(mysql_num_rows(getPerso($donnees['guid'])) == 0)
			{
				supprLigne('accounts', 'guid', $donnees['guid']);
			}
		}
		header('location: index.php?page=compte_admin');
	}
	if(!isset($_GET['id']))
	{
		?>
		<p class="titrePage">Gestion des comptes : </p>
		<div class="clean"></div><br>
			<form action="index.php?page=compte_admin&act=1" method="post">
				<center>Rechercher un compte :<br> 
				<input type="text" name="search" /><br>
				<input type="image" src="images/bouttons/rechercher.png" /></center><br>
			
				Pour afficher tout les comptes, mettez %. Si vous ne connaissez qu'un bout du nom de compte, faite : %bout_de_nom_connu%. Le signe % remplace un nombre indefinie de caractères indéfinie.<br>
				<br>
				Exemple : pour chercher test <br><br>
				<center><font color=green>
				- tes%<br>
				- t%<br>
				- %est<br>
				- %es%<br></font></center><br>
			</form>
			<center><a href="index.php?page=compte_admin&purge=1" style="text-decoration: none;"><img src="images/admin/att.png" /> Purger les comptes vides <img src="images/admin/att.png" /></a></center><br>
			<?php 
	}
	if(isset($_GET['act']))
	{
		$_POST['search'] = mysql_real_escape_string($_POST['search']);
		echo '<p class="titrePage">Résultats de la Recherche : </p>
		<div class="clean"></div><br>';
		$query = 'SELECT * FROM accounts WHERE account LIKE "'.$_POST['search'].'" AND level < '.$_SESSION['level'].' OR pseudo LIKE "'.$_POST['search'].'" AND level < '.$_SESSION['level'].' ORDER BY account ASC';
		$retour = mysql_query($query);
		if(mysql_num_rows($retour) == 0)
		{
			echo '<font color=red><center>Aucun résultats !</center></font>';
		}else 
		{
			while($donnees = mysql_fetch_array($retour))
			{
				echo '<div class="admin"><a href="index.php?page=compte_admin&id='.$donnees['guid'].'">Compte : '.$donnees['account'].' ('.$donnees['pseudo'].')</a></div><br>';
			}
		}
	}
	if(isset($_GET['id']) and is_numeric($_GET['id']) and $_SESSION['level'] > getLevel($_GET['id']))
	{
		$donnees = getAccount($_GET['id']);?>
			<p class="titrePage"><img src="images/retour.png" name="retour" onmouseover="retour.src='images/retour2.png'" onmouseout="retour.src='images/retour.png'" onclick="history.go(-1);" style="float: left;" title="retour" /> Compte : <?php echo $donnees['account']; if(getInfo($donnees['account'], 'banned') == 1) echo ' <font color=red>Banni !</font>';?></p>
			<div class="clean"></div><br>
			<table>
				<tr><td>- Pseudo : </td><td><font color=red><?php echo $donnees['pseudo'];?></font></td></tr>
				<tr><td>- Mot de passe : </td><td><font color=red><?php echo $donnees['pass'];?></font></td></tr>
				<tr><td>- Question : </td><td><font color=red><?php echo $donnees['question'];?></font></td></tr>
				<tr><td>- Réponse : </td><td><font color=red><?php echo $donnees['reponse'];?></font></td></tr>
				<tr><td>- Level : </td><td><font color=red><?php echo $donnees['level'];?></font></td></tr>
				<tr><td>- Points boutique : </td><td><font color=red><?php echo $donnees['points'];?></font></td></tr>
				<tr><td>- Nombre de perso : </td><td><font color=red><?php echo mysql_num_rows(getPerso($_GET['id']));?></font></td></tr>
			</table><br><br><br>
			<p class="titrePage">Ces personnages</p>
			<div class="clean"></div><br>
			<?php 
			$result = getPerso($donnees['guid']);
			while($array = mysql_fetch_array($result))
			{
				echo '<div class="admin"><a href="index.php?page=perso_admin&id='.$array['guid'].'" title="Informations" >- '.$array['name'].' ('.$array['level'].')</a></div> <a href="index.php?page=perso_admin&id='.$array['guid'].'&suppr=1" title="supprimer" ><img src="images/admin/suppr.png" align="middle" /></a><br><br>';
			}
	
			?>
			<br><h2 align="center">Outils :</h2><br>
			<center>
				<a href="index.php?page=compte_admin&suppr=<?php echo $_GET['id'];?>" title="supprimer" ><img src="images/admin/suppr.png" /></a> 
				<?php if(getInfo($donnees['account'], 'banned') != 1){?><a href="index.php?page=compte_admin&ban=<?php echo $_GET['id'];?>" title="bannir"><img src="images/admin/ban.png" /></a><?php }else{ ?>
				<a href="index.php?page=compte_admin&unban=<?php echo $_GET['id'];?>" title="debannir"><img src="images/admin/unban.png" /></a><?php }?>
				<a href="index.php?page=compte_admin&banip=<?php echo $donnees['lastIP'];?>" title="banip"><img src="images/admin/ip.png" /></a> 
				<a href="index.php?page=mod_compte_admin&id=<?php echo $_GET['id'];?>" title="Modifier"><img src="images/admin/modif.png" /></a>
			</center>
		<?php
	}
	?>
	
<?php 
}else header("location: index.php?page=news");?>