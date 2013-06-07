<?php
if(isset($_GET['page']) and isset($boutiqueOn) and $boutiqueOn)
{
	include("pages/boutique/function.php");
	$points = 0;
	if(isset($_SESSION['login']))
	{
		$points = getInfo($_SESSION['login'], 'points');
	}
	 ?>

	<p class="titrePage">Boutique</p>
	<div class="clean"></div><br>
	<center><a href="index.php?page=boutique&type=-1"> Tout</a> | <a href="index.php?page=boutique&type=2">Armes</a> | <a href="index.php?page=boutique&type=1">Amulettes</a> | <a href="index.php?page=boutique&type=9">Anneaux</a> | <a href="index.php?page=boutique&type=10">Ceintures</a> | <a href="index.php?page=boutique&type=11">Bottes</a> | <a href="index.php?page=boutique&type=16">Coiffes</a> | <a href="index.php?page=boutique&type=17">Capes</a> | <a href="index.php?page=boutique&type=23">Dofus</a></center><br><br>
	<?php if(isset($_SESSION['login'])){?>
	Vous avez <font color=green><?php echo $points;?></font> Points. Si vous n'en avez pas assez, merci d'aller voter ;). <br>Pour avoir un jet max, il faut payer <font color=red><?php echo $pts_max;?> %</font> du prix en plus !<br><br>
	<?php }?>
	
	
		<form action="index.php?page=boutique&search=1" method="post">
			<center>
				Rechercher : <input type="text" name="search" />
				<input type="image" src="images/bouttons/rechercher.png" align="middle" /><br><br>
				<font color=red>/!\ Sensible à la case ! ("test" n'est pas égual à "TeSt") /!\</font>
			</center><br>				
		</form><br>
	
	<?php 
	if(isset($_GET['type']) and is_numeric($_GET['type']))
	{
			$query = 'SELECT id, points FROM '.$db_static.'.item_template WHERE boutique = 1 AND statsTemplate != "" '.($_GET['type'] == 2 ? 'AND type >= "2" AND type <= "8"' :($_GET['type'] == '-1' ? '' : 'AND type = "'.$_GET['type'].'"'));
			$retour = mysql_query($query) or die(mysql_error());
			while($array = mysql_fetch_array($retour))
			{
				affCarac($array['id'], $points, $array['points']);
			}
	}
	if(isset($_POST['search']) and isset($_GET['search']) and $_GET['search'] == 1)
	{
		$_POST['search'] = mysql_real_escape_string($_POST['search']);
		$query = 'SELECT id, points FROM '.$db_static.'.item_template WHERE boutique = 1 AND name LIKE "%'.$_POST['search'].'%" AND statsTemplate != ""';
		$retour = mysql_query($query);
		while($array = mysql_fetch_array($retour))
		{
			affCarac($array['id'], $points, $array['points']);
		}
	}
}
?>