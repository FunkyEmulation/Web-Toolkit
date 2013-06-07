<?php
if(isset($_SESSION['login']) and isset($_GET['page']) and isset($_GET['id']) and is_numeric($_GET['id']) and $_SESSION['level'] >= $level_admin)
{ 
		if(isset($_GET['suppr']) and is_numeric($_GET['suppr']) and $_GET['suppr'] == 1)
		{
			supprLigne('personnages', 'guid', $_GET['id']);
			header('location: index.php?page=compte_admin');
		}
		include('compte/functions.php');
		if($cache)
		{
			if(!file_exists('cache/perso/'.$_GET['id'].'.html') or filemtime('cache/perso/'.$_GET['id'].'.html') < (time() - $perso))
			{
				ob_start();
				displayStats($_GET['id']);
				//partie cache
				$tampon = ob_get_contents();
				file_put_contents('cache/perso/'.$_GET['id'].'.html', $tampon);
				ob_end_clean();
				header('location: index.php?page=perso_admin&id='.$_GET['id']); 
			}elseif(file_exists('cache/perso/'.$_GET['id'].'.html'))
			{
				readfile('cache/perso/'.$_GET['id'].'.html');
			}
		}else 
		{
			displayStats($_GET['id']);
		}
}else header('location: index.php?page=news'); ?>
