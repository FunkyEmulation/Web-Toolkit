<?php
if(isset($_GET['page']))
{ 

	if(isset($_GET['act']) and $_GET['act'] == 1 and !is_numeric($att = timeVote($_SESSION['id'], $temps_vote)))
	{
		$points = getInfo($_SESSION['login'], 'points');
		$points += $pts_vote;
		$vote = getInfo($_SESSION['login'], 'vote') + 1;
		$query = 'UPDATE accounts SET points = "'.$points.'", heurevote = "'.time().'", vote = "'.$vote.'" WHERE guid = "'.$_SESSION['id'].'"';
		mysql_query($query) or die(mysql_error());
		header('location: '.$lien_vote);
	}else header('loaction: index.php?page=vote');
	?>

<p class="titrePage">Votez pour Nous</p>
<div class="clean"></div><br>
	
	
<?php
if(!is_numeric($att = timeVote($_SESSION['id'], $temps_vote)))
{
	echo '<center><font color=green>Cliquez <a href="index.php?page=vote&act=1">ICI</a> et recevez '.$pts_vote.' Points !</font></center><br><br><br><br><br><br><br><br><br><br><br><br>';
}else 
{
	echo '<center><font color=red>Vous avez déjà voté il y a moins de '.$temps_vote.' Minutes !<br>Il vous reste <font color=green>'.secToHuman($att).'</font> à attendre !</font></center><br><br><br><br><br><br><br><br><br><br><br><br>';
}

} ?>