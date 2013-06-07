<?php
if(isset($_GET['page']) and isset($_SESSION['level']) and $_SESSION['level'] >= $level_admin)
{
	if(isset($_GET['act']) and $_GET['act'] == 1 and isset($_POST['id']) and is_numeric($_POST['id']) and isset($_POST['prix']) and is_numeric($_POST['prix']))
	{
		$query = 'UPDATE '.$db_static.'.item_template SET boutique = "1", points = "'.$_POST['prix'].'" WHERE id = "'.$_POST['id'].'"';
		mysql_query($query) or die(mysql_error());
		header('location: index.php?page=boutique/admin');
	}
	if (isset($_GET['suppr']) and is_numeric($_GET['suppr']) and existe($db_static.'.item_template', 'id', $_GET['suppr']))
	{
		$query = 'UPDATE '.$db_static.'.item_template SET boutique = "0" WHERE id = "'.$_GET['suppr'].'"';
		mysql_query($query) or die(mysql_error());
		header('location: index.php?page=boutique/admin');
	}
	?>
	<p class="titrePage">Ajouter un Item</p>
	<div class="clean"></div><br>
	<?php 
	if(!isset($_GET['id']))
	{?>
	<div class="cadre_haut"></div><div class="cadre_fond">
	<center><form action="index.php?page=boutique/admin&act=1" method="post">
		Id de l'item :<br> <input type="text" name="id"><br>
		Prix :<br> <input type="text" name="prix"><br><br>
		<input type="image" src="images/bouttons/envoyer.png" align="middle">
	</form></center></div><div class="cadre_bas"></div>
	<br><br>
		<p class="titrePage">Liste des Items</p>
		<div class="clean"></div><br>
		<center><table>
			<tr><td>Nom</td><td>Prix</td><td>Supprimer</td><td>Modifier</td><td>Voir</td></tr>
			<?php 
				$query = 'SELECT * FROM '.$db_static.'.item_template WHERE boutique = "1"';
				$retour = mysql_query($query) or die(mysql_error());
				while ($donnees = mysql_fetch_array($retour))
				{
					echo '<tr><td>'.$donnees['name'].'</td><td>'.$donnees['points'].'</td><td><a href="index.php?page=boutique/admin&suppr='.$donnees['id'].'" title="le retirer de la vente" ><img src="images/admin/suppr.png" align="middle"/></a></td><td><a href="index.php?page=boutique/admin&id='.$donnees['id'].'" title="modifier l\'item" ><img src="images/admin/conf.png" align="middle" /></a></td><td><a href="index.php?page=boutique/admin&id='.$donnees['id'].'"><img src="images/admin/fleche.png" /></a></tr>';
				}
			?>
		</table></center>
	<?php 
	}elseif (isset($_GET['id']) and is_numeric($_GET['id']))
	{
		if(existe($db_static.'.item_template', 'id', $_GET['id']))
		{
			include 'function.php';
			$query = 'SELECT * FROM '.$db_static.'.item_template WHERE id = "'.$_GET['id'].'"';
			$retour = mysql_query($query) or die(mysql_error());
			$array = mysql_fetch_array($retour);
			echo '<div class="cadre_haut"></div><div class="cadre_fond">';
			echo '<img src="images/retour.png" name="retour" onmouseover="retour.src=\'images/retour2.png\'" onmouseout="retour.src=\'images/retour.png\'" onclick="history.go(-1);" style="float: left;" title="retour" />';
				affCarac($array['id'], 0, $array['points']);
			echo '</div><div class="cadre_bas"></div>';
		}else header('location: index.php?page=boutique/admin');
	}
	?>
	
	
<?php }else header('location: ..');