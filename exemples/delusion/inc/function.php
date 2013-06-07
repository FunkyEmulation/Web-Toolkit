<?php
function getInfo($account, $info)
{
        $data = database_query('SELECT '.addslashes($info).' FROM accounts WHERE account = ?', null, $account)->fetch();
	return  $data->$info;
}
function getAccount($guid)
{
	return database_query('SELECT * FROM accounts WHERE guid = ?', null, $guid)->fetch();
}
function existe($table, $col, $var)
{
	$var = mysql_real_escape_string($var);
	$table = mysql_real_escape_string($table);
	$col = mysql_real_escape_string($col);
	$query = 'SELECT '.$col.' FROM '.$table.' WHERE '.$col.' = "'.$var.'"';
	$retour = mysql_query($query) or die(mysql_error());
	return mysql_num_rows($retour) > 0;
}
function nbLigne($table)
{
	$table = mysql_real_escape_string($table);
	$query = 'SELECT * FROM '.$table;
	$retour = mysql_query($query) or die(mysql_error());
	$nb = mysql_num_rows($retour);
	return $nb;
}
function nbLigne2($table, $col, $var)
{
	$query = 'SELECT * FROM '.$table.' WHERE '.$col.' = "'.$var.'"';
	$nb = mysql_num_rows(mysql_query($query));
	return $nb;
}
function supprLigne($table, $col, $var)
{
	$var = mysql_real_escape_string($var);
	$query = 'DELETE FROM '.$table.' WHERE '.$col.' = "'.$var.'"';
	mysql_query($query) or die(mysql_error());
}
function getPerso($guid)
{
	$guid = intval($guid);
	$query = 'SELECT * FROM personnages WHERE account = "'.$guid.'"';
	return mysql_query($query);
}
function getLevel($guid)
{
	if(is_numeric($guid))
	{
		$query = 'SELECT * FROM accounts WHERE guid = "'.$guid.'"';
		$retour = mysql_query($query) or die(mysql_error());
		$donnees = mysql_fetch_array($retour);
		return $donnees['level'];
	}else return false;
}
function timeVote($guid, $lag)
{
	$guid = intval($guid);
	$lag = intval($lag);
	$lag *= 60;
	$lag = time() - $lag;
	$query = 'SELECT * FROM accounts WHERE guid = "'.$guid.'"';
	$retour = mysql_query($query) or die(mysql_error());
	$donnees = mysql_fetch_array($retour);
	if($donnees['heurevote'] <= $lag)
	{
		return true;
	}else
	{
		return $donnees['heurevote'] - $lag;
	}
}
function secToHuman($sec)
{
	$fin = '';
	$sec = intval($sec);
	if($sec >= 3600)
	{
		$h = intval($sec / 3600);
		$hs = $h.' Heures, ';
	}
	if($sec >= 60)
	{
		$m = intval($sec / 60 % 60);
		$ms = $m.' Minutes et ';
	}
	$s = intval($sec % 60);
	$ss = $s.' Secondes';
	if(isset($hs))
	{
		$fin = $hs;
	}
	if(isset($ms))
	{
		$fin = $fin.$ms;
	}
	if(isset($ss))
	{
		$fin = $fin.$ss;
	}
	return $fin;
}
function imgPerso($guid, $mode)
{
	$mode = intval($mode);
	$guid = intval($guid);
	$query = 'SELECT * FROM personnages WHERE guid = "'.$guid.'"';
	$retour = mysql_query($query) or die(mysql_error());
	$donnees = mysql_fetch_array($retour);
	switch ($donnees['class'])
	{
		case 1 : $str = 'feca';
		break;
		case 2 : $str = 'osa';
		break;
		case 3 : $str = 'enu';
		break;
		case 4 : $str = 'sram';
		break;
		case 5 : $str = 'xel';
		break;
		case 6 : $str = 'eca';
		break;
		case 7 : $str = 'eni';
		break;
		case 8 : $str = 'iop';
		break;
		case 9 : $str = 'cra';
		break;
		case 10 : $str = 'sadi';
		break;
		case 11 : $str = 'sacri';
		break;
		case 12 : $str = 'pand';
		break;
	}
	switch ($donnees['sexe'])
	{
		case 0 : $s = '_m';
		break;
		case 1 : $s = '_f';
		break;
	}
	switch ($donnees['alignement'])
	{
		case 0 : $a = 'neutre';
		break;
		case 1 : $a = 'bonta';
		break;
		case 2 : $a = 'brack';
		break;
	}
	return ($mode == 1 ? '<div class="'.$a.'">' : '').'<img src="images/classe/'.($mode == 1 ? 'big/': '').$str.$s.'.png" '.($mode == 1 ? 'style="margin-left: 60px; margin-top: 65px;"' : '').'/>'.($mode == 1 ? '</div>' : '');
}
function persoEaccount($account, $guid)
{
	$account = intval($account);
	$guid = intval($guid);
	$query = 'SELECT * FROM personnages WHERE guid = "'.$guid.'"';
	$retour = mysql_query($query) or die(mysql_error());
	$donnees = mysql_fetch_array($retour);
	return $donnees['account'] == $account;
}
function siAdmin($guid)
{
	$guid = intval($guid);
	$donnees = getAccount($guid);
	return $donnees['level'] > 0;
}
function reloadCache($type)
{
	$type = intval($type);
	$query = 'SELECT * FROM cache WHERE type = "'.$type.'"';
	$retour = mysql_query($query) or die(mysql_error());
	$donnees = mysql_fetch_array($retour);
	return $donnees['reload'] == 1;
}
function verifName($str)
{
	return preg_match('#^[a-z0-9._-]{4,32}$#i', $str);
}
function isMail($str)
{
	return preg_match('#^.+@[a-z0-9]+\.[a-z]{2,4}$#i', $str);
}
?>