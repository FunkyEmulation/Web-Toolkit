<?php
if(isset($_GET['page']))
{
	$query = 'SELECT AVG(level) FROM personnages';
	$result = mysql_query($query) or die(mysql_error());
	$lvlMoy = mysql_fetch_array($result);
	$lvlMoy = $lvlMoy['AVG(level)'];
	$query = 'SELECT * FROM personnages';
	$result = mysql_query($query) or die(mysql_error());
	$nbPerso = mysql_num_rows($result);
	$feca = 0;$osa = 0; $enu = 0;$sram = 0;$xel = 0;$eca = 0;$eni = 0;$iop = 0;$cra = 0;$sadi = 0;$sacri = 0;$pand = 0;
	$males = 0;$femmelles = 0;
	while($donnees = mysql_fetch_array($result))
	{
		switch ($donnees['class'])
		{
			case 1 : $feca++;
			break;
			case 2 : $osa++;
			break;
			case 3 : $enu++;
			break;
			case 4 : $sram++;
			break;
			case 5 : $xel++;
			break;
			case 6 : $eca++;
			break;
			case 7 : $eni++;
			break;
			case 8 : $iop++;
			break;
			case 9 : $cra++;
			break;
			case 10 : $sadi++;
			break;
			case 11 : $sacri++;
			break;
			case 12 : $pand++;
			break;
		}
		switch ($donnees['sexe'])
		{
			case 0 : $males++;
			break;
			case 1 : $femmelles++;
			break;
		}
	}
	$query = 'SELECT * FROM accounts';
	$result = mysql_query($query) or die(mysql_error());
	$nbComptes = mysql_num_rows($result);
	$ban = 0;
	while ($donnees = mysql_fetch_array($result))
	{
		if($donnees['banned'] == 1 ) $ban++;
	}
	?>
	<p class="titrePage">Statistiques Globales</p>
	<div class="clean"></div><br>
	
	<center><table>
		<tr><td>Nombre de compte</td><td><font color=red><?php echo $nbComptes;?></font></td></tr>
		<tr><td>Comptes Bannis</td><td><font color=red><?php echo $ban;?></font></td></tr>
		<tr><td>Nombre de personnages</td><td><font color=red><?php echo $nbPerso;?></font></td></tr>
		<tr><td>Nombre de Mâles</td><td><font color=red><?php echo $males;?></font></td></tr>
		<tr><td>Nombre de Femmelles</td><td><font color=red><?php echo $femmelles;?></font></td></tr>
	</table></center><br><br>
	
	<p class="titrePage">Classes</p>
	<div class="clean"></div><br>
	
	<center><table>
  		<tr><td>Feca</td><td><?php echo $feca;?></td></tr>
  		<tr><td>Osamodas</td><td><?php echo $osa;?></td></tr>
  		<tr><td>Enutrophes</td><td><?php echo $enu;?></td></tr>
  		<tr><td>Srams</td><td><?php echo $sram;?></td></tr>
  		<tr><td>Xelors</td><td><?php echo $xel;?></td></tr>
  		<tr><td>Ecaflips</td><td><?php echo $eca;?></td></tr>
  		<tr><td>Eniripsas</td><td><?php echo $eni;?></td></tr>
  		<tr><td>Iops</td><td><?php echo $iop;?></td></tr>
  		<tr><td>Crâs</td><td><?php echo $cra;?></td></tr>
  		<tr><td>Sadidas</td><td><?php echo $sadi;?></td></tr>
  		<tr><td>Sacrieurs</td><td><?php echo $sacri;?></td></tr>
  		<tr><td>Padawas</td><td><?php echo $pand;?></td></tr>
	</table></center>
	<?php 
} ?>