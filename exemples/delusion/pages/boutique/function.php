<?php
function hexToCarac($hex)
{
	$fin = "";
	$hex = strtolower($hex);
	switch ($hex)
	{
		//special
		case "b6" : $fin = '<font>Invocations : ';
		break;
		case "6f" : $fin = '<font color=yellow>PA : ';
		break;
		case "75" : $fin = '<font>PO : ';
		break;
		case "80" : $fin = '<font color=green>PM : ';
		break;
		case "79" : $fin = '<font>Dommages : ';
		break;
		case "70" : $fin = '<font>Dommages : ';
		break;
		case "73" : $fin = '<font>CC : ';
		break;
		case "e1" : $fin = '<font>Dommages aux pièges : ';
		break;
		case "e2" : $fin = '<font>% dommages aux pièges : ';
		break;
		case "31b" : $fin = '<font>Arme de chasse : ';
		break;
		case "8a" : $fin = '<font>% de dommages : ';
		break;
		case "2c2" : $fin = '<font>% de chance de capture de monture : ';
		break;
		case "7f" : $fin = '<font color=green>PM perdus : ';
		break;
		case "b2" : $fin = '<font color=pink>+ en soins : ';
		break;
		case "32c" : $fin = '<font>Arme éthérée ! ';
		
		//resis
		case "d4" : $fin = '<font color=green>% resis air : ';
		break;
		case "d2" : $fin = '<font color=brown>% resis terre : ';
		break;
		case "d3" : $fin = '<font color=blue>% resis eau : ';
		break;
		case "d5" : $fin = '<font color=red>% resis feu : ';
		break;
		case "d6" : $fin = '<font color=grey>% resis neutre : ';
		break;
		case "d9" : $fin = '<font color=green>% de faiblesse air : ';
		break;
		case "db" : $fin = '<font color=grey>% de faiblesse neutre : ';
		break;
		case "d7" : $fin = '<font color=brown>% de faiblesse terre : ';
		break;
		case "d8" : $fin = '<font color=blue>% de faiblesse eau : ';
		break;
		case "da" : $fin = '<font color=red>% de faiblesse feu : ';
		break;
		case "b7" : $fin = '<font>Resis à la magie : ';
		break;
		case "b8" : $fin = '<font>Resis physique : ';
		break;
		case "f4" : $fin = '<font color=grey>+ de resis neutre : ';
		break;
		case "f3" : $fin = '<font color=red>+ resis feu : ';
		break;
		case "f0" : $fin = '<font color=brown>+ resis terre : ';
		break;
		case "f1" : $fin = '<font color=blue>+ de resis eau : ';
		break;
		case "f2" : $fin = '<font color=green>+ resis air : ';
		break;
		
		//effets armes
		case "62" : $fin = '<font color=green>Dégats air : ';
		break;
		case "61" : $fin = '<font color=brown>Dégats terre : ';
		break;
		case "63" : $fin = '<font color=red>Dégats feu : ';
		break;
		case "60" : $fin = '<font color=blue>Dégats eau : ';
		break;
		case "64" : $fin = '<font color=grey>Dégats neutres : ';
		break;
		case "65" : $fin = '<font color=yellow>Retraits de PA : ';
		break;
		case "6c" : $fin = '<font color=pink>PDV rendus : ';
		break;
		case "51" : $fin = '<font color=pink>PDV rendus : ';
		break;
		case "5b" : $fin = '<font color=bleu>Vol de vie eau : ';
		break;
		case "5d" : $fin = '<font color=green>Vol de vie air : ';
		break;
		case "5e" : $fin = '<font color=red>Vol de vie feu : ';
		break;
		case "5c" : $fin = '<font color=brown>Vol de vie terre : ';
		break;
		case "5f" : $fin = '<font color=grey>Vol de vie neutre : ';
		break;
		case "82" : $fin = '<font color=yellow>Vol d\'or : ';
		break;
		
		//carac
		case "76" : $fin = '<font color=brown>Terre : ';
		break;
		case "25f" : $fin = '<font color=brown>Terre : ';
		break;
		case "77" : $fin = '<font color=green>Air : ';
		break;
		case "7e" : $fin = '<font color=red>Feu : ';
		break;
		case "7b" : $fin = '<font color=blue>Eau : ';
		break;
		case "7d" : $fin = '<font color=pink>Vie : ';
		break;
		case "6e" : $fin = '<font color=pink>Vie : ';
		break;
		case "7c" : $fin = '<font color=purple>Sagesse : ';
		break;
		case "ae" : $fin = '<font color=purple>Initiative : ';
		break;
		case "b0" : $fin = '<font>Prospection : ';
		break;
		case "b1" : $fin = '<font>Moins en prospection : ';
		break;
		case "af" : $fin = '<font color=purple>Moins en initiative : ';
		break;
		case "74" : $fin = '<font>Moins en porté : ';
		break;
		case "9a" : $fin = '<font color=green>Moins en air : ';
		break;
		case "99" : $fin = '<font color=red>Moins en Vie : ';
		break;
		case "9b" : $fin = '<font color=red>Moins en feu : ';
		break;
		case "9c" : $fin = '<font color=purple>Moins en sagesse : ';
		break;
		case "9d" : $fin = '<font color=brown>Moins en terre : ';
		break;
		case "98" : $fin = '<font color=blue>Moins en eau : ';
		break;
		
		//en cas d'erreur
		default: $fin = '<font>Indefini : '.$hex.' !';
		break;
	}
	return $fin;
}
function displayGFX($gfxId, $type)
{
	$gfxId = intval($gfxId);
	$type = intval($type);
	?>
	<div style="float: right;"><object type="application/x-shockwave-flash" data="<?php echo 'swf/'.$type.'/'.$gfxId;?>.swf" width="145" height="145">
		<param name="movie" value="<?php echo 'swf/'.$type.'/'.$gfxId;?>.swf" />
		<param name="wmode" value="transparent" />
		<p>Aucun aperçus disponibles... Veuillez nous en excuser...</p>
	</object></div>
	<?php
}
function affCarac($id, $pts, $pts_item)
{
	
	include './inc/config.php';
	$id = intval($id);
	$pts = intval($pts);
	$pts_item = intval($pts_item);
	if($cache)
	{
		if(!file_exists('./cache/boutique/'.$id.'.html'))
		{
			ob_start();
			$query = 'SELECT name, level, statsTemplate, type FROM '.$db_static.'.item_template WHERE id = "'.$id.'"';
			$result = mysql_query($query) or die(mysql_error());
			$array = mysql_fetch_array($result);
			$tab = explode(',', $array['statsTemplate']);
			echo '<div class=news-titre><p class="titre">'.$array['name'].' (lvl '.$array['level'].', Prix = '.$pts_item.') :</p></div><br>';
			echo '<div class="news-content"><div class="content">';
			for($i = 0; $i < sizeof($tab); $i++)
			{
				$carac = explode('#', $tab[$i]);
				//displayGFX($array['gfxid'], $array['type']);
				echo hexToCarac($carac[0]).' '.hexdec($carac[1]).' '.(hexdec($carac[2])>0?'- '.hexdec($carac[2]):'').'</font><br>';
			}
			//partie cache
			$tampon = ob_get_contents();
			file_put_contents('./cache/boutique/'.$id.'.html', $tampon);
			ob_flush();
			ob_end_clean();
		}elseif(file_exists('./cache/boutique/'.$id.'.html'))
		{
			readfile('./cache/boutique/'.$id.'.html');
		}
	}else 
	{
		$query = 'SELECT name, level, statsTemplate, type FROM '.$db_static.'.item_template WHERE id = "'.$id.'"';
		$result = mysql_query($query) or die(mysql_error());
		$array = mysql_fetch_array($result);
		$tab = explode(',', $array['statsTemplate']);
		echo '<div class=news-titre><p class="titre">'.$array['name'].' (lvl '.$array['level'].', Prix = '.$pts_item.') :</p></div><br>';
		echo '<div class="news-content"><div class="content">';
		for($i = 0; $i < sizeof($tab); $i++)
		{
			$carac = explode('#', $tab[$i]);
			//displayGFX($array['gfxid'], $array['type']);
			echo hexToCarac($carac[0]).' '.hexdec($carac[1]).' '.(hexdec($carac[2])>0?'- '.hexdec($carac[2]):'').'</font><br>';
		}
	}
	echo '<br>';
	if(isset($_SESSION['login']) and $_SESSION['login'] != null and $pts >= $pts_item)
	{
		echo '<a href="index.php?page=boutique/buy&id='.$id.'" title="Prix : '.$pts_item.'"><img src="images/bouttons/achat.png" name="buy'.$id.'" onmouseover="buy'.$id.'.src=\'images/bouttons/achat2.png\'" onmouseout="buy'.$id.'.src=\'images/bouttons/achat.png\'" align="right" /></a><br><br>';
	}else echo '<a title="trop chere !"><img src="images/bouttons/achat3.png" align="right" /></a><br><br>';
	echo '</div></div>';
}
?>