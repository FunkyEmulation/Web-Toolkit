<?php
if(isset($_GET['page']))
{
	echo '<p class="titrePage"><b>Accueil</b></p>
          <div class="clean"></div><br><br>';
	if($cache)
	{
		if(!file_exists('cache/news.html') or reloadCache(1))
		{
			ob_start();
			$query = 'SELECT * FROM news ORDER BY id DESC LIMIT 0, 10';
			$donnes1 = mysql_query($query) or die(mysql_error());
			while($donnees = mysql_fetch_array($donnes1))
			{
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
				echo '<a href="index.php?page=commentaires&id='.$donnees['id'].'">Commentaires</a>';
			}
			//partie cache
			$tampon = ob_get_contents();
			file_put_contents('cache/news.html', $tampon); //crée un sytem de cache
			ob_end_clean();
			$query = 'UPDATE cache SET reload = 0 WHERE type = 1';
			mysql_query($query) or die(mysql_error());
			header('location: index.php?page=news');
		}elseif(file_exists('cache/news.html'))
		{
			readfile('cache/news.html');
		}
	}else 
	{
		$query = 'SELECT * FROM news ORDER BY id DESC LIMIT 0, 10';
		$donnes1 = mysql_query($query) or die(mysql_error());
		while($donnees = mysql_fetch_array($donnes1))
		{
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
					'.nl2br($donnees['text']).'
				</div>
			</div>';
			echo '<a href="index.php?page=commentaires&id='.$donnees['id'].'">Commentaires</a>';
		}
	}
}
?>