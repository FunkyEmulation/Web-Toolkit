<?php
if(isset($_GET['page']) and isset($_SESSION['level']) and $_SESSION['level'] >= $level_admin)
{
	
	if(isset($_GET['suppr']) and $_SESSION['level'] >= $level_admin)
	{
		supprLigne('news', 'id', $_GET['suppr']);
		$query = 'UPDATE cache SET reload = 1 WHERE type = 1';
		mysql_query($query) or die(mysql_error());
		header('location: index.php?page=news_admin');
	}
	if(isset($_POST['new']) and isset($_GET['act']) and isset($_POST['titre']) and $_POST['titre'] != null)
	{
		$query = 'INSERT INTO news(titre,text,auteur,type, date) VALUES("'.$_POST['titre'].'", "'.$_POST['new'].'", "'.$_SESSION['pseudo'].'", "'.$_POST['type'].'", "'.date('d / m / Y').'")';
		mysql_query($query) or die(mysql_error());
		$query = 'UPDATE cache SET reload = 1 WHERE type = 1';
		mysql_query($query) or die(mysql_error());
		header('location: index.php?page=news_admin');
	}
?>

	<p class="titrePage">Ajouter une New :</p>
	<div class="clean"></div><br>
	<center><div class="cadre_haut"></div><div class="cadre_fond">
		<form action="index.php?page=news_admin&act=1" method="post">
			Titre : <input type="text" name="titre" /><br><br>
			Message : <br>
			<textarea name="new" rows=10 cols=50 ></textarea><br>
			<input type="image" src="images/bouttons/envoyer.png" />
		</form>
	</div><div class="cadre_bas"></div></center><br><br>
	
	
	
	<p class="titrePage">News :</p>
	<div class="clean"></div><br>

	<?php
	$query = 'SELECT * FROM news ORDER BY id DESC';
	$donnes1 = mysql_query($query) or die(mysql_error());
	while($donnees = mysql_fetch_array($donnes1))
	{
		$donnees['text'] = str_replace('\\', '', $donnees['text']);
		$donnees['titre'] = str_replace('\\', '', $donnees['titre']);
		?><a href="index.php?page=news_admin&suppr=<?php echo $donnees['id'];?>"><img src="images/admin/suppr.png" /></a> <a href="index.php?page=mod_news&id=<?php echo $donnees['id'];?>"><img src="images/admin/modif.png" /></a><br><?php
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
		echo '<a href="index.php?page=commentaires&id='.$donnees['id'].'">Commentaires</a><br><br>';
	} 
}else header("location: index.php?page=news");
?>
