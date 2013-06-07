<?php
function getItemEqu($perso)
{
	$perso = intval($perso);
	$query = 'SELECT * FROM personnages WHERE guid = "'.$perso.'"';
	$retour = mysql_query($query) or die(mysql_error());
	$donnees = mysql_fetch_array($retour);
	$item = explode('|', $donnees['objets']);
	$query = 'SELECT * FROM items WHERE guid = "-1" AND pos != "-1"';
	for($i = 0; $i < sizeof($item)-1; $i++)
	{
		$query = $query.' OR guid = "'.$item[$i].'" AND pos != "-1"';
	}
	return  $query;
}
function calcStats($perso)
{
	$perso = intval($perso);
	$retour = mysql_query(getItemEqu($perso)) or die(mysql_error());
	$fin = array(
				'force' => 0,
				'intel' => 0,
				'chance' => 0,
				'sagesse' => 0,
				'vie' => 0,
				'agi' => 0
			);
	while($donnees = mysql_fetch_array($retour))
	{
		$array = explode(',', $donnees['stats']);
		for($i = 0; $i < sizeof($array); $i++)
		{
			$carac = explode('#', $array[$i]);
			switch (strtolower($carac[0]))
			{
				case "76" : $fin['force'] += hexdec($carac[1]);
				break;
				case "9d" : $fin['force'] -= hexdec($carac[1]);
				break;
				case "77" : $fin['agi'] += hexdec($carac[1]);
				break;
				case "9a" : $fin['agi'] -= hexdec($carac[1]);
				break;
				case "7e" : $fin['intel'] += hexdec($carac[1]);
				break;
				case "9b" : $fin['intel'] -= hexdec($carac[1]);
				break;
				case "7b" : $fin['chance'] += hexdec($carac[1]);
				break;
				case "98" : $fin['chance'] -= hexdec($carac[1]);
				break;
				case "7d" : $fin['vie'] += hexdec($carac[1]);
				break;
				case "6e" : $fin['vie'] += hexdec($carac[1]);
				break;
				case "99" : $fin['vie'] -= hexdec($carac[1]);
				break;
				case "7c" : $fin['sagesse'] += hexdec($carac[1]);
				break;
				case "9c" : $fin['sagesse'] -= hexdec($carac[1]);
				break;
			}
			
		}
		
	}
	
	return $fin;
}
function nbItemPano($perso)
{
	$perso = intval($perso);
	$retour = mysql_query(getItemEqu($perso));
	$items = array();
	$nbItemPano = array();
	$b = 0;
	while($donnees = mysql_fetch_array($retour))
	{
		$items[$b] = $donnees['template'];
		$b++;
	}
	$query = 'SELECT * FROM ancestra_static.itemsets';
	$retour = mysql_query($query) or die(mysql_error());
	$itemSets = array();
	while ($donnees = mysql_fetch_array($retour))
	{
		$itemSets[$donnees['ID']] = explode(',', $donnees['items']);
		$nbItemPano[$donnees['ID']] = 0;
		for($i = 0; $i < sizeof($itemSets[$donnees['ID']]); $i++)
		{
			for($a = 0; $a < sizeof($items); $a++)
			{
				if(intval(str_replace(' ', '', $itemSets[$donnees['ID']][$i])) == intval(str_replace(' ', '', $items[$a])))
				{
					$nbItemPano[$donnees['ID']]++;
				}
			}
		}
	}
	return $nbItemPano;
}
function statsPano($perso)
{
	$perso = intval($perso);
	$nbItemPano = nbItemPano($perso);
	$statsItemPano = array();
	$statsPanoDeb = array();
	$statsPanoFin = array(
		'force' => 0,
		'intel' => 0,
		'chance' => 0,
		'agi' => 0,
		'sagesse' => 0,
		'vie' => 0
	);
	$query = 'SELECT * FROM ancestra_static.itemsets';
	$retour = mysql_query($query) or die(mysql_error());
	while ($donnees = mysql_fetch_array($retour))
	{
		$statsItemPano[$donnees['ID']] = explode(';', $donnees['bonus']);
	}
	foreach ($nbItemPano as $key => $value)
	{
		if($value > 1)
		{
			$statsPanoDeb[$key] = explode(',', $statsItemPano[$key][intval($value) - 2]);
		}
	}
	foreach ($statsPanoDeb as $key => $value)
	{
		foreach ($value as $cle => $val)
		{
			$x = explode(':', $val);
			switch ($x[0])
			{
				case '119' : $statsPanoFin['agi'] += $x[1];
				break;
				case '123' : $statsPanoFin['chance'] += $x[1];
				break;
				case '118' : $statsPanoFin['force'] += $x[1];
				break;
				case '126' : $statsPanoFin['intel'] += $x[1];
				break;
				case '124' : $statsPanoFin['sagesse'] += $x[1];
				break;
				case '125' : $statsPanoFin['vie'] += $x[1];
				break;
			}
		}
	}
	return $statsPanoFin;
}
function getGuild($persoId)
{
	include './inc/config.php';
	$persoId = intval($persoId);
	$query = 'SELECT guild FROM guild_members WHERE guid = "'.$persoId.'"';
	$result = mysql_query($query) or die(mysql_error());
	if(mysql_num_rows($result) > 0)
	{
		$donnees = mysql_fetch_array($result);
		$query = 'SELECT name FROM guilds WHERE id = "'.$donnees['guild'].'"';
		$result = mysql_query($query) or die(mysql_error());
		$donnees = mysql_fetch_array($result);
		return $donnees['name'];
	}else return false;
}
function displayStats($guid)
{
	include './inc/config.php';
	$guid = intval($guid);
	$query = 'SELECT * FROM personnages WHERE guid = "'.$guid.'"';
	$retour = mysql_query($query) or die(mysql_error());
	$donnees = mysql_fetch_array($retour);
	$lvl = intval($donnees['level']);
	$lvl++;
	$query = 'SELECT * FROM '.$db_static.'.experience WHERE lvl = "'.$lvl.'"';
	$retour = mysql_query($query) or die(mysql_error());
	if(mysql_num_rows($retour) > 0)
	{
		$xp = mysql_fetch_array($retour);
	}else $xp['perso'] = 'undefined';
	$pano1 = calcStats($donnees['guid']);
	$pano2 = statsPano($donnees['guid']);
	$force = intval($pano1['force']) + intval($pano2['force']) + $donnees['force'];
	$vie = intval($pano1['vie']) + intval($pano2['vie']) + $donnees['vitalite'] + intval($donnees['level']) * 5 + 50;
	$sagesse = intval($pano1['sagesse']) + intval($pano2['sagesse']) + $donnees['sagesse'];
	$agi = intval($pano1['agi']) + intval($pano2['agi']) + $donnees['agilite'];
	$intel = intval($pano1['intel']) + intval($pano2['intel']) + $donnees['intelligence'];
	$chance = intval($pano1['chance']) + intval($pano2['chance']) + $donnees['chance'];
	?>
	<p class="titrePage"><img src="images/retour.png" name="retour" onmouseover="retour.src='images/retour2.png'" onmouseout="retour.src='images/retour.png'" onclick="history.go(-1);" style="float: left;" title="retour" />Informations sur le Personnage :</p>
	<div class="clean"></div><br>
		<?php echo '<div style="float:left;">'.imgPerso($donnees['guid'], 1).'</div>';?><br><br>
		<br><br><h2><u><?php echo $donnees['name']; if(getGuild($guid)) echo ' (guild : '.getGuild($guid).')';?></u></h2><br><br>
		- Level : <font color=green><?php echo $donnees['level'];?></font><br>
		- Xp : <font color=green><?php echo $donnees['xp'].' / '.$xp['perso'];?></font><br><br><br><br><br>
		<br><br>
		<div class="stats"><br>
			<div style="margin-top: 10px;margin-left: 180px;"><font color=pink><?php echo $vie;?></font></div>
			<div style="margin-top: 8px;margin-left: 180px;"><font color=purple><?php echo $sagesse;?></font></div>
			<div style="margin-top: 5px;margin-left: 180px;"><font color=brown><?php echo $force;?></font></div>
			<div style="margin-top: 7px;margin-left: 180px;"><font color=red><?php echo $intel;?></font></div>
			<div style="margin-top: 7px;margin-left: 180px;"><font color=blue><?php echo $chance;?></font></div>
			<div style="margin-top: 7px;margin-left: 180px;"><font color=green><?php echo $agi;?></font></div>
		</div>
		<?php 
}