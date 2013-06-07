<?php
if(isset($_GET['page']))
{ ?>
	<p class="titrePage">Classement Vote</p>
	<div class="clean"></div><br>
	<?php
	if($cache)
	{
		if(!file_exists('cache/ladder_vote.html') or filemtime('cache/ladder_vote.html') < (time() - $ladder_vote))
		{
			ob_start();
			$query = 'SELECT * FROM accounts ORDER BY vote DESC LIMIT 20';
			$retour = mysql_query($query) or die(mysql_error());
			$i = 1;
			?>
			Les meilleurs voteurs sont répertoriés ici.<br> Votez pour en faire partie, et peu être, en plus des points boutiques gagnés lors des votes, remporter des cadeaux !<br><br><br>
			<center><table>
				<tr><td>Position : </td><td>Pseudo : </td><td>Nombres de votes : </td></tr>
			<?php 
			while($donnees = mysql_fetch_array($retour))
			{
				echo '<tr>
							<td>'.$i.'</td><td>'.$donnees['pseudo'].'</td><td>'.$donnees['vote'].'</td>
					 </tr>'; 
				$i++;
			}?>
			</table></center>
			<?php
			//partie cache	
			$tampon = ob_get_contents();
			file_put_contents('cache/ladder_vote.html', $tampon);
			ob_end_clean();
			header('location: index.php?page=ladder_vote');
		}elseif (file_exists('cache/ladder_vote.html'))
		{
			readfile('cache/ladder_vote.html');
		}
	}else 
	{
		$query = 'SELECT * FROM accounts ORDER BY vote DESC LIMIT 20';
		$retour = mysql_query($query) or die(mysql_error());
		$i = 1;
		?>
		Les meilleurs voteurs sont répertoriés ici.<br> Votez pour en faire partie, et peu être, en plus des points boutiques gagnés lors des votes, remporter des cadeaux !<br><br><br>
		<center><table>
			<tr><td>Position : </td><td>Pseudo : </td><td>Nombres de votes : </td></tr>
		<?php 
		while($donnees = mysql_fetch_array($retour))
		{
			echo '<tr>
						<td>'.$i.'</td><td>'.$donnees['pseudo'].'</td><td>'.$donnees['vote'].'</td>
				 </tr>'; 
			$i++;
		}?>
		</table></center>
	<?php
	}
}