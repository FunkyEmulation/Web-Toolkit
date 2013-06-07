<?php
if(isset($_GET['page']) and isset($_GET['id']) and is_numeric($_GET['id']) and isset($_SESSION['login']))
{
	include("pages/boutique/function.php");
	$points = getInfo($_SESSION['login'], 'points');
	$query = 'SELECT id, points, boutique FROM '.$db_static.'.item_template WHERE id = "'.$_GET['id'].'"';
	$retour = mysql_query($query) or die(mysql_error());
	$donnees = mysql_fetch_array($retour);
	if($points >= $donnees['points'] and $donnees['boutique'] == 1)
	{  
		if(!isset($_GET['perso']))
		{?>
			<div class="cadre_haut"></div><div class="cadre_fond">
				<?php affCarac($donnees['id'], 0, $donnees['points']);?>
			</div><div class="cadre_bas"></div><br>
		<?php 
		}
		if(!isset($_GET['perso']))
		{?>
				<p class="titrePage"><img src="images/retour.png" name="retour" onmouseover="retour.src='images/retour2.png'" onmouseout="retour.src='images/retour.png'" onclick="history.go(-1);" style="float: left;" title="retour" /> Selection du personnage</p>
				<div class="clean"></div><br>
				<?php 
				$retour = getPerso($_SESSION['id']);
					while($array = mysql_fetch_array($retour))
					{
						echo '<div class="admin"><a href="index.php?page=boutique/buy&id='.$_GET['id'].'&perso='.$array['guid'].'" >- '.$array['name'].' ('.$array['level'].')</a></div><br>';
					}
		}elseif(is_numeric($_GET['perso']))
		{
			$query = 'SELECT * FROM personnages WHERE guid = "'.$_GET['perso'].'"';
			$array = mysql_fetch_array(mysql_query($query)) or die(mysql_error());
			if($array['account'] != $_SESSION['id'])
			{
				echo '<font color=red><center>Problème lors de la selection du personnages !<br>
						Veuillez recommencer.</center></font>
						<meta http-equiv="refresh" content="2 url=index.php?page=boutique/buy&id='.$_GET['id'].'" />';
			}else 
			{
				if(!isset($_GET['max']))
				{
					echo '<font color=green><center>Cliquez <a href="index.php?page=boutique/buy&id='.$_GET['id'].'&perso='.$_GET['perso'].'&max=1">ici</a> pour avoir l\'item en jet max (cout : <font color=red>'.($donnees['points'] * ( 1 + $pts_max / 100)).'</font>)<br>
							Ou <a href="index.php?page=boutique/buy&id='.$_GET['id'].'&perso='.$_GET['perso'].'&max=0">ici</a> pour des jets alléatoires.</font>';
				}elseif(isset($_GET['max']) and is_numeric($_GET['max']))
				{
					if(intval($_GET['max']) == 1)
					{
						$pointsF = $donnees['points'] * (1 + $pts_max / 100);
					}else 
					{
						$pointsF = $donnees['points'];
					}
					$points = getInfo($_SESSION['login'], 'points');
					if($pointsF <= $points)
					{
						$points -= $pointsF;
						$query = 'UPDATE accounts SET points = "'.$points.'" WHERE guid = "'.$_SESSION['id'].'"';
						mysql_query($query) or die(mysql_error());
						$query = 'INSERT INTO live_action (ID, PlayerID, Action, Nombre) VALUES ("'.mysql_insert_id().'", "'.$_GET['perso'].'", "'.(intval($_GET['max']) == 1 ? 21 : 20).'", "'.$_GET['id'].'")';
						mysql_query($query);
						echo '<font color=green><center>L\'achat c\'est déroulé avec succès !!<br>
							Vous allez être redirigé vers la page de boutique.</center></font>
							<meta http-equiv="refresh" content="2 url=index.php?page=boutique" />';
					}else echo '<center><font color=red>Vous n\'avez pas assez de points ou l\'objet est indisponible !</font></center>';
				}
			}
		}
	}else 
	{
		echo '<center><font color=red>Vous n\'avez pas assez de points ou l\'objet est indisponible !</font></center>';
	}
}else header('location: index.php?page=boutique');
?>