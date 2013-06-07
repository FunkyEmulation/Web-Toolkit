<?php
if(isset($_GET['page']) and isset($_GET['id']) and is_numeric($_GET['id']))
{
	if(isset($_GET['suppr']) and is_numeric($_GET['suppr']) and $_SESSION['level'] >= $level_admin)
	{
		supprLigne('commentaires', 'guid', $_GET['suppr']);
		header('location: index.php?page=commentaires&id='.$_GET['id']);
	}
	if(isset($_GET['post']) and $_GET['post'] == 1 and isset($_POST['message']))
	{
		$_POST['message'] = mysql_real_escape_string($_POST['message']);
		$query = 'INSERT INTO commentaires (id, text, auteur, date) VALUES ("'.$_GET['id'].'", "'.$_POST['message'].'", "'.$_SESSION['pseudo'].'", "'.date('d / m / Y').'")';
		mysql_query($query) or die(mysql_error());
		header('location: index.php?page=commentaires&id='.$_GET['id']);
	}
	$query = 'SELECT * FROM news WHERE id = "'.$_GET['id'].'"';
	$result = mysql_query($query) or die(mysql_error());
	$donnees = mysql_fetch_array($result);
	$donnees['text'] = str_replace('\\', '', $donnees['text']);
	$donnees['titre'] = str_replace('\\', '', $donnees['titre']);
	echo '
	<div class="news-titre">
		<p class="titre">'.$donnees['titre'].'</p>
		<p class="auteur">Posté par <span>'.$donnees['auteur'].'</span>, Le : '.$donnees['date'].'</p>
	</div>';	
	echo '
	<div class="news-content">
		<div class="content">
			<p>'.nl2br($donnees['text']).'</p>
		</div>
	</div>';
	?>
		
		<p class="titrePage"> Commentaires : <img src="images/retour.png" name="retour" onmouseover="retour.src='images/retour2.png'" onmouseout="retour.src='images/retour.png'" onclick="history.go(-1);" style="float: left;" title="retour" /></p>
		<div class="clean"></div><br><br>
		<?php 
		$query = 'SELECT * FROM commentaires WHERE id = "'.$_GET['id'].'" ORDER BY guid DESC';
		$result = mysql_query($query) or die(mysql_error());
		while($donnees = mysql_fetch_array($result))
		{
			$donnees['text'] = str_replace('\\', '', $donnees['text']);
			if(isset($_SESSION['level']) and $_SESSION['level'] >= $level_admin)
			{
				?><a href="index.php?page=commentaires&id=<?php echo $_GET['id'];?>&suppr=<?php echo $donnees['guid'];?>"><img src="images/admin/suppr.png" align="middle" /></a><?php 
			} 
			echo '<em> Par '.$donnees['auteur'].', le '.$donnees['date'].' :</em><br><br>';
			echo '<div class="news">'.nl2br(htmlspecialchars($donnees['text'])).'</div>
			<center>--------------</center>
			<br>';
		}
		if(isset($_SESSION['login']))
		{
		?>
		<center><form action="index.php?page=commentaires&id=<?php echo $_GET['id'];?>&post=1" method="post">
			<textarea name="message" rows=4 cols=50 ></textarea><br><br>
			<input src="./images/bouttons/envoyer.png"  type="image" />	
		</form></center>
		<?php 
		}?>
	<?php 	
}