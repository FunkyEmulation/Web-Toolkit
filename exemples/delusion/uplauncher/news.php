<?php
include '../inc/config.php';
$con = mysql_connect($ip_db, $user_db, $pass_db) or die(mysql_error());
mysql_select_db($db_name, $con) or die(mysql_error());
$query = 'SELECT * FROM news ORDER BY id DESC LIMIT 0, 10';
$donnes1 = mysql_query($query) or die(mysql_error());
while($donnees = mysql_fetch_array($donnes1))
{
	$donnees['text'] = str_replace('\\', '', $donnees['text']);
	$donnees['titre'] = str_replace('\\', '', $donnees['titre']);
	echo '<h2>'.$donnees['titre'].'</h2>';
	echo nl2br($donnees['text']);
	echo '<br>Par : '.$donnees['auteur'].', Le : '.$donnees['date'].'<br>
	<hr><br>';
}