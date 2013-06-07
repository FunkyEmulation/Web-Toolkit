<?php
if(isset($_SESSION['login']) and isset($_GET['page']) and isset($_GET['id']) and is_numeric($_GET['id']))
{ 
	if(persoEaccount($_SESSION['id'], $_GET['id']))
	{
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
				header('location: index.php?page=perso&id='.$_GET['id']);
			}elseif (file_exists('cache/perso/'.$_GET['id'].'.html'))
			{
				readfile('cache/perso/'.$_GET['id'].'.html');
			}
		}else 
		{	
			displayStats($_GET['id']);
		}
	}else echo '<center><font color=red>Ce personnage ne vous appartient pas !</font></center>';
}else header('location: index.php?page=compte'); ?>