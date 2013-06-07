<?php
if(isset($_GET['page']))
{ ?>
	<p class="titrePage">Classement des Guilds</p>
	<div class="clean"></div><br>
	<?php
	if($cache)
	{
		if(!file_exists('cache/ladder_guild.html') or filemtime('cache/ladder_guild.html') < (time() - $ladder_vote))
		{
			ob_start();
			$query = 'SELECT * FROM guilds ORDER BY xp DESC LIMIT 20';
			$retour = mysql_query($query) or die(mysql_error());
			$i = 1;
			?>
			<center><table>
				<tr><td>Position : </td><td>Nom : </td><td>Level : </td><td>Expérience :</td></tr>
			<?php 
			while($donnees = mysql_fetch_array($retour))
			{
				echo '<tr>
							<td>'.$i.'</td><td>'.$donnees['name'].'</td><td>'.$donnees['lvl'].'</td><td>'.$donnees['xp'].'</td>
					 </tr>'; 
				$i++;
			}?>
			</table></center>
			<?php
			//partie cache	
			$tampon = ob_get_contents();
			file_put_contents('cache/ladder_guild.html', $tampon);
			ob_end_clean();
			header('location: index.php?page=ladder_guild');
		}elseif (file_exists('cache/ladder_guild.html'))
		{
			readfile('cache/ladder_guild.html');
		}
	}else 
	{
		$query = 'SELECT * FROM guilds ORDER BY xp DESC LIMIT 20';
		$retour = mysql_query($query) or die(mysql_error());
		$i = 1;
		?>
		<center><table>
			<tr><td>Position : </td><td>Nom : </td><td>Level : </td><td>Expérience :</td></tr>
		<?php 
		while($donnees = mysql_fetch_array($retour))
		{
			echo '<tr>
						<td>'.$i.'</td><td>'.$donnees['name'].'</td><td>'.$donnees['lvl'].'</td><td>'.$donnees['xp'].'</td>
				 </tr>'; 
			$i++;
		}?>
		</table></center>
	<?php
	}
}