<?php
if(isset($_GET['page']) and isset($_SESSION['level']) and $_SESSION['level'] >= $level_admin)
{
	echo '<center>';
	$dir = opendir('cache');
	while(false !== ($fichier = readdir($dir)))
	{
		$chemin = './cache/'.$fichier;
		if(is_file($chemin))
		{
			$infos = pathinfo($chemin);
			$extension = $infos['extension'];
			if($extension == 'html')
			{
				if(unlink($chemin))
				{
					echo $chemin.' <font color=green>fait</font><br>';
				}else echo $chemin.'<font color=red>erreur !</font><br>';
			}
		}
	}
	closedir($dir);
	$dir = opendir('cache/perso');
	while(false !== ($fichier = readdir($dir)))
	{
		$chemin = './cache/perso/'.$fichier;
		if(is_file($chemin))
		{
			$infos = pathinfo($chemin);
			$extension = $infos['extension'];
			if($extension == 'html')
			{
				if(unlink($chemin))
				{
					echo $chemin.' <font color=green>fait</font><br>';
				}else echo $chemin.'<font color=red>erreur !</font><br>';
			}
		}
	}
	closedir($dir);
	$dir = opendir('cache/boutique');
	while(false !== ($fichier = readdir($dir)))
	{
		$chemin = './cache/boutique/'.$fichier;
		if(is_file($chemin))
		{
			$infos = pathinfo($chemin);
			$extension = $infos['extension'];
			if($extension == 'html')
			{
				if(unlink($chemin))
				{
					echo $chemin.' <font color=green>fait</font><br>';
				}else echo $chemin.'<font color=red>erreur !</font><br>';
			}
		}
	}
	closedir($dir);
	echo '<br><br>Fait !</center>';
}else header('location: index.php?page=news');