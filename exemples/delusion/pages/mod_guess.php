<?php
if(isset($_GET['page']) and isset($_SESSION['level']) and $_SESSION['level'] >= $level_admin and isset($_GET['id']) and is_numeric($_GET['id']))
{
	if(isset($_GET['act']) and isset($_POST['text']))
	{
		$_POST['text'] = mysql_real_escape_string($_POST['text']);
		$query = 'UPDATE guess_book SET text = "'.$_POST['text'].'" WHERE id = "'.$_GET['id'].'"';
		mysql_query($query) or die(mysql_error());
		$query = 'UPDATE cache SET reload = 1 WHERE type = 2';
		mysql_query($query) or die(mysql_error());
		header('location: index.php?page=guessbook');
	}
	$query = 'SELECT * FROM guess_book WHERE id = "'.$_GET['id'].'"';
	$retour = mysql_query($query) or die(mysql_error());
	$array = mysql_fetch_array($retour);
	$array['text'] = htmlspecialchars($array['text']);
	$array['text'] = str_replace('\\', '', $array['text']);
	?>

	<p class="titrePage">Modérer un Message :</p>
	<div class="clean"></div><br>
	<center>
		<img src="images/retour.png" name="retour" onmouseover="retour.src='images/retour2.png'" onmouseout="retour.src='images/retour.png'" onclick="history.go(-1);" style="float: left;" title="retour" />
			<form action="index.php?page=mod_guess&act=1&id=<?php echo $_GET['id'];?>" method="post">
				Message : <br>
				<textarea name="text" rows=10 cols=50 ><?php echo $array['text'];?></textarea><br><br>
				<input type="image" src="images/bouttons/envoyer.png" />
			</form>
	</center><br><br>
<?php 	
}?>