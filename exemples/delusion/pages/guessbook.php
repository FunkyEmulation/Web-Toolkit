<?php if(isset($_GET['page']))
{
	if(isset($_POST['message']) and isset($_GET['act']))
	{
		$_POST['message'] = mysql_real_escape_string($_POST['message']);
		$query = 'INSERT INTO guess_book(text, pseudo, id, date) VALUES("'.$_POST['message'].'", "'.$_SESSION['pseudo'].'", "", "'.date('d / m / Y').'")';
		mysql_query($query) or die(mysql_error());
		$query = 'UPDATE cache SET reload = 1 WHERE type = 2';
		mysql_query($query) or die(mysql_error());
		header('loction: index.php?page=guessbook');
	}
	if(isset($_GET['suppr']) and is_numeric($_GET['suppr']) and $_SESSION['level'] >= $level_admin)
	{
		supprLigne('guess_book', 'id', $_GET['suppr']);
		$query = 'UPDATE cache SET reload = 1 WHERE type = 2';
		mysql_query($query) or die(mysql_error());
		header('loction: index.php?page=guessbook');
	} ?>
	<p class="titrePage">Livre D'or</p>
	<div class="clean"></div><br><br>
	<?php
	if($cache)
	{
		?>
		
		<?php
		if(!file_exists('cache/guessbook.html') or reloadCache(2))
		{
			ob_start();
			$query = 'SELECT * FROM guess_book ORDER BY id DESC LIMIT 0, 10';
			$donnes1 = mysql_query($query) or die(mysql_error());
			while($donnees = mysql_fetch_array($donnes1))
			{
				$donnees['text'] = str_replace('\\', '', $donnees['text']);
				if(isset($_SESSION['level']) and $_SESSION['level'] >= $level_admin)
				{
					?><a href="index.php?page=guessbook&suppr=<?php echo $donnees['id'];?>"><img src="images/admin/suppr.png" /></a> 
					<a href="index.php?page=mod_guess&id=<?php echo $donnees['id'];?>"><img src="images/admin/modif.png" /></a>
				<?php 
				} 
				echo '<em>Par '.$donnees['pseudo'].', le '.$donnees['date'].' :</em><br><br>';
				echo '<div class="news">'.nl2br(htmlspecialchars($donnees['text'])).'</div>
				<center>--------------</center>
				<br>';
			}
			//partie cache
			$tampon = ob_get_contents();
			file_put_contents('cache/guessbook.html', $tampon);
			ob_end_clean();
			$query = 'UPDATE cache SET reload = 0 WHERE type = 2';
			mysql_query($query) or die(mysql_error());
			header('location: index.php?page=guessbook');
		}elseif (file_exists('cache/guessbook.html'))
		{
			readfile('cache/guessbook.html');
		}
	}else 
	{
		$query = 'SELECT * FROM guess_book ORDER BY id DESC LIMIT 0, 10';
		$donnes1 = mysql_query($query) or die(mysql_error());
		while($donnees = mysql_fetch_array($donnes1))
		{
			$donnees['text'] = str_replace('\\', '', $donnees['text']);
			if(isset($_SESSION['level']) and $_SESSION['level'] >= $level_admin)
			{
				?><a href="index.php?page=guessbook&suppr=<?php echo $donnees['id'];?>"><img src="images/admin/suppr.png" /></a> 
				<a href="index.php?page=mod_guess&id=<?php echo $donnees['id'];?>"><img src="images/admin/modif.png" /></a>
			<?php 
			} 
			echo '<em>Par '.$donnees['pseudo'].', le '.$donnees['date'].' :</em><br><br>';
			echo '<div class="news">'.nl2br(htmlspecialchars($donnees['text'])).'</div>
			<center>--------------</center>
			<br>';
		}
	}

	if(isset($_SESSION['pseudo']))
	{
	
	?>
	<p class="titrePage">Signer Le Livre D'or</p>
	<div class="clean"></div><br>
		<center><form action="index.php?page=guessbook&act=1" method="post">
			<textarea name="message" rows=10 cols=50 ></textarea><br><br>
			<input src="./images/bouttons/envoyer.png"  type="image" />
		</form></center>
	<?php	
	}
}
?>