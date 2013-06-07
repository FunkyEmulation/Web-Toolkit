<?php
if(isset($_GET['page']) and isset($_SESSION['level']) and $_SESSION['level'] >= $level_admin and isset($_GET['id']) and is_numeric($_GET['id']))
{
	if(isset($_GET['act']))
	{
		if(isset($_POST['type']) and isset($_POST['titre']) and isset($_POST['new']))
		{
			$query = 'UPDATE news SET titre = "'.$_POST['titre'].'", text = "'.$_POST['new'].'", type = "'.$_POST['type'].'", date = "'.date('d / m / Y').'" WHERE id = "'.$_GET['id'].'"';
			mysql_query($query) or die(mysql_error());
			$query = 'UPDATE cache SET reload = 1 WHERE type = 1';
			mysql_query($query) or die(mysql_error());
			header('location: index.php?page=news_admin');
		}else header('location: index.php?page=mod_news&id='.$_GET['id'].'&err=1');
	} 
	$query = 'SELECT * FROM news WHERE id = "'.$_GET['id'].'"';
	$retour = mysql_query($query) or die(mysql_error());
	$array = mysql_fetch_array($retour);
	$array['text'] = str_replace('\\', '', $array['text']);
	$array['titre'] = str_replace('\\', '', $array['titre']);
	?>

	<p class="titrePage">Modifier une New</p>
	<div class="clean"></div><br>
	<center>
		<?php if(isset($_GET['err'])) echo '<font color=red>Veuillez remplir tout les champs !</font><br><br>';?>
		<img src="images/retour.png" name="retour" onmouseover="retour.src='images/retour2.png'" onmouseout="retour.src='images/retour.png'" onclick="history.go(-1);" style="float: left;" title="retour" />
		<form action="index.php?page=mod_news&act=1&id=<?php echo $_GET['id'];?>" method="post">
			Titre : <input type="text" name="titre" value="<?php echo $array['titre'];?>" /><br><br>
			Message : <br>
			<textarea name="new" rows=10 cols=50 ><?php echo $array['text'];?></textarea><br>
			<input type="image" src="images/bouttons/envoyer.png" />
		</form>
	</center><br><br> 
<?php 	
}?>