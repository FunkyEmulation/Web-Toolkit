<?php if(isset($_GET['page']))
{
	if(isset($_GET['p']) and is_numeric($_GET['p']))
	{
		$query = 'SELECT * FROM personnages ORDER BY '.($_GET['p'] == 1 ? 'xp' : 'honor').' DESC LIMIT 0, 40'; //40, en espérant que les admin n'ont pas plus de 20 perso^^
		$var = $_GET['p'] == 1 ? 'Expérience :' : 'Honneur :';
		?>
			<p class="titrePage">Classement</p>
			<div class="clean"></div><br>
			<center><a href="index.php?page=ladder&p=1">Ladder Expérience</a> | <a href="index.php?page=ladder&p=2">Ladder Honneur</a><br><br></center>
			<?php
		if($cache)
		{
			if(!file_exists('cache/ladder-'.$_GET['p'].'.html') or filemtime('cache/ladder-'.$_GET['p'].'.html') < (time() - $ladder))
			{
				ob_start();
				?>
				<center><table>
				<tr><td><b>Place :</b></td><td><img src="images/classe/SmallHead_0.png" /></td><td><b>Nom :</b></td><td><b>Level :</b></td><td><b><?php echo $var;?></b></td></tr>
				<?php
				//$query = 'SELECT * FROM personnages ORDER BY '.($_GET['p'] == 1 ? 'xp' : 'honor').' DESC LIMIT 0, 40'; //40, en espérant que les admin n'ont pas plus de 20 perso^^
				$result = mysql_query($query) or die(mysql_error());
				$i = 1;
				while($donnees = mysql_fetch_array($result) and $i <= 20)
				{
					if(!siAdmin($donnees['account']))
					{	
						echo '<tr><td>'.$i.'</td><td>'.imgPerso($donnees['guid'], 0).'</td><td>'.$donnees['name'].'</td><td>'.$donnees['level'].'</td><td>'.($_GET['p'] == 1 ? $donnees['xp'] : $donnees['honor']).'</td></tr>';
						$i++;
					}
				}
				?>
				</table></center>
				<?php 
				//partie cache
				$tampon = ob_get_contents();
				file_put_contents('cache/ladder-'.$_GET['p'].'.html', $tampon);
				ob_end_clean();
				header('location: index.php?page=ladder&p='.$_GET['p']);
			}elseif(file_exists('cache/ladder-'.$_GET['p'].'.html'))
			{
				readfile('cache/ladder-'.$_GET['p'].'.html');
			}
		}else 
		{
			?>
			<center><table>
			<tr><td><b>Place :</b></td><td><img src="images/classe/SmallHead_0.png" /></td><td><b>Nom :</b></td><td><b>Level :</b></td><td><b><?php echo $var;?></b></td></tr>
			<?php
			//$query = 'SELECT * FROM personnages ORDER BY xp DESC LIMIT 0, 40'; //40, en espérant que les admin n'ont pas plus de 20 perso^^
			$result = mysql_query($query) or die(mysql_error());
			$i = 1;
			while($donnees = mysql_fetch_array($result) and $i <= 20)
			{
				if(!siAdmin($donnees['account']))
				{	
					echo '<tr><td>'.$i.'</td><td>'.imgPerso($donnees['guid'], 0).'</td><td>'.$donnees['name'].'</td><td>'.$donnees['level'].'</td><td>'.($_GET['p'] == 1 ? $donnees['xp'] : $donnees['honor']).'</td></tr>';
					$i++;
				}
			}
			?>
			</table></center>
			<?php 
		}
	}else header('location: index.php?page=ladder&p=1');
}?>