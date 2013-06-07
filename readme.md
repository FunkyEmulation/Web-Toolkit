# Web Toolkit
_Par v4vx_

## Présentation

Web Toolkit est une bibliothèque permettant de facilité le codage en PHP des CMS.
Il est principalement axé sécurité, le point faible de la plus part des CMS actuels. Son utilisation est des plus simples possible, aucunes normes est obligatoire, et le Toolkit s'adapte totalement au codage **OOP** comme **procedurial** !

Web Toolkit est basé sur _Single Framework_, avec un code plus flexible et complet, tout en gardant les performances ! (moins de 1ms pour charge le Tookit).

## Utilisation

Son utilisation est faite pour être **simple** et **intuitive**.
Pour l'utiliser, il faut juste télécharger la dernière version (actuellement **v1.0**), mettre le dossier _lib_ à la raine du site, et include le Toolkit au tout début du code !

```php
<?php
//Fichier index.php
require 'lib/toolkit/toolkit.php';

/*
 * Le code ici !
 */
```

Le Toolkit n'imposant pas de structure stricte, il est utilisable sur un projet déjà avancé, et ce **sans rien toucher au code actuel** !

## Exemples :

Rien de vaut un bon exemple pour momentrer la puissance de **WTK** !

```php
<?php
session_start();
ob_start();
$temps1 = array_sum(explode(' ', microtime()));
include_once('inc/config.php');
include_once('inc/function.php');
$con = mysql_connect($ip_db, $user_db, $pass_db) or die(mysql_error());
mysql_select_db($db_name, $con) or die(mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="inc/style.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
	<meta http-equiv="content-language" content="fr" />
</head>
<body>
  <div id="site">
	  <div id="idbarre">
	  <span class="right">
	  	<?php if(!isset($_SESSION['login']))
	  	{?>
			<form id="login" action="index.php?page=connect" method="post">
				<input id="login_idbar" type="text" name="login" value="Connexion" onclick="login.value=''">
				<input type="password" name="pass" value="********" onclick="pass.value=''">
				<input type="submit" value="ok" class="btn"><a href="index.php?page=inscrip">Pas encore Inscrit ?</a>
			</form>

		<?php
	  	}else
	  	{
	  		echo '<b>Bienvenue '.$_SESSION['pseudo'].' ! (<a href="index.php?page=compte" title="gérer le compte">Gestion du compte</a> | <a href="pages/logout.php" title="se déconnecter">Logout</a>)</b>';
	  	}?>

		</span>
	  </div>
            <div id="header"></div>
            <div id="menu">
                <ul>
                    <li><a href="index.php?page=news">Accueil</a></li>
                    <li><a href="<?php echo $url_forum;?>">Forum</a></li>
                    <li><a href="index.php?page=rejoin">Rejoindre</a></li>
                    <li><a href="index.php?page=infos">Informations</a></li>
                    <li><a href="index.php?page=vote">Votez</a></li>
                    <li><a href="index.php?page=stats">Stats</a></li>
                    <li><a href="index.php?page=boutique">Boutique</a></li>
                    <div class="clean"></div>
                </ul>
            </div>
            <div id="corps">
                <div id="corps-int">
                        <div id="full">
                            <div id="corps-gauche">
                              <?php
                            	if(!isset($_GET['page']) or !file_exists('./pages/'.$_GET['page'].'.php'))
								{
									header("location: index.php?page=news");
								}else
								{
									include_once('./pages/'.$_GET['page'].'.php');
								}?>
							</div>
						<?php
						if(isset($_SESSION['login']))
						{?>
							<div id="corps-droite">

                                            <div id="menudroite">
                                                <div class="titre">Compte</div>
                                                <div class="content">
												<div id="bu">
													<ul class="FondMenu">
														<li><a href="index.php?page=compte" class="menuG">Gestion du compte</a></li>
														<li><a href="index.php?page=boutique" class="menuM">Boutique</a></li>
													</ul>
													</div>
                                                </div>
                                            </div>
                            </div>
                           <?php
						}
						if(isset($_SESSION['level']) and $_SESSION['level'] >= $level_admin)
						{
						?>
						    <div id="corps-droite">

                                            <div id="menudroite">
                                                <div class="titre">Admin</div>
                                                <div class="content">
												<div id="bu">
													<ul class="FondMenu">
														<li><a href="index.php?page=compte_admin" class="menuG">Gestion des comptes</a></li>
														<li><a href="index.php?page=boutique/admin" class="menuM">Gestion de la Boutique</a></li>
														<li><a href="index.php?page=news_admin" class="menuG">Gestion des News</a></li>
														<li><a href="index.php?page=clean_cache" class="menuM">Vider le Cache</a></li>
													</ul>
													</div>
                                                </div>
                                            </div>
                            </div>
                            <?php
						}?>
							<div id="corps-droite">

                                            <div id="menudroite">
                                                <div class="titre">Menu</div>
                                                <div class="content">
												<div id="bu">
													<ul class="FondMenu">
														<li><a href="index.php?page=ladder" class="menuG">Classement</a></li>
														<li><a href="index.php?page=ladder_vote" class="menuM">Classement Vote</a></li>
														<li><a href="index.php?page=ladder_guild" class="menuG">Classement Guilds</a></li>
														<li><a href="index.php?page=guessbook" class="menuM">Livre D'or</a></li>
														<li><a href="index.php?page=inscrip" class="menuG">Inscription</a></li>
													</ul>
													</div>
                                                </div>
                                            </div>
                            </div>
                            <div id="corps-droite">

                                            <div id="menudroite">
                                                <div class="titre">Infos</div>
                                                <div class="content">
												<div id="bu">
													<ul class="FondMenu">
														<li><a class="menuG">état du serveur :
																<?php
																$check = @fsockopen ($ip_serv, $port_serv, $errno, $errstr, 1.0);
																if (!$check)
																{
																	?><strong><font color="red"><?php
																	echo "Hors Ligne";?></strong></font><?php
																}
																else
																{
																	?><strong>
																	<font color="green"><?php
																	echo "En Ligne"; ?></strong></font><?php
																	$gs_online = TRUE;
																}
																@fclose($$check);?></a></li>
														<li><a class="menuM">état base de donnée :
																<?php
																$check = @fsockopen ($ip_db, 3306, $errno, $errstr, 1.0);
																if (!$check)
																{
																	?><strong>
																	<font color="red"><?php
																	echo "Hors Ligne";?></strong></font><?php
																}
																else
																{
																	?><strong>
																	<font color="green"><?php
																	echo "En Ligne"; ?></strong></font><?php
																	$gs_online = TRUE;
																}
																@fclose($$check);?></a></li>
														<li><a class="menuG">Nombre de comptes : <font color=green><?php echo nbLigne('accounts');?></font></a></li>
														<li><a class="menuM">Nombre de perso : <font color=green><?php echo nbLigne('personnages');?></font></a></li>
														<li><a class="menuG">Nombre de connexions : <?php $nbCo = mysql_fetch_array(mysql_query('SELECT COUNT(account) AS connected FROM `accounts` WHERE `logged` = "1"'));echo '<font color=green>'.$nbCo['connected'].'</font>';?></a></li>
														<li><a href="index.php?page=cgu" class="menuM">Conditions générales D'utilisation</a></li>
													</ul>
													</div>
                                                </div>
                                            </div>
                            </div>
                            <div class="clean"></div>
                        </div>
                </div>
            </div>


    <div id="footer"><a href="http://www.KitGraphique.net" title="Kits Graphiques" class="footer1"></a><a href="http://www.djokx.com" title="Services WebMaster" class="footer2"></a></div>
	</div>
</body>
</html>
<?php ob_flush();
ob_end_clean();?>
```

devient :

```php
<?php
define('CONFIG_FILE', 'inc/config.php');
require 'lib/toolkit/toolkit.php';
include_once('inc/function.php');

//initialise la session
$session = Session::instance();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <title>Delusion CMS</title>
    <link rel="stylesheet" type="text/css" href="inc/style.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="content-language" content="fr" />
</head>
<body>
  <div id="site">
	  <div id="idbarre">
	  <span class="right">
	  	<?php if(!session_islog()){?>
			<form id="login" action="index.php?page=connect" method="post">
				<input id="login_idbar" type="text" name="login" value="Connexion" onclick="login.value=''">
				<input type="password" name="pass" value="********" onclick="pass.value=''">
				<input type="submit" value="ok" class="btn"><a href="index.php?page=inscrip">Pas encore Inscrit ?</a>
			</form>
		<?php
	  	}else
	  	{
	  		echo '<b>Bienvenue '.$session->pseudo.' ! (<a href="index.php?page=compte" title="gérer le compte">Gestion du compte</a> | <a href="pages/logout.php" title="se déconnecter">Logout</a>)</b>';
	  	}?>

		</span>
	  </div>
            <div id="header"></div>
            <div id="menu">
                <ul>
                    <li><a href="index.php?page=news">Accueil</a></li>
                    <li><a href="<?php echo $url_forum;?>">Forum</a></li>
                    <li><a href="index.php?page=rejoin">Rejoindre</a></li>
                    <li><a href="index.php?page=infos">Informations</a></li>
                    <li><a href="index.php?page=vote">Votez</a></li>
                    <li><a href="index.php?page=stats">Stats</a></li>
                    <li><a href="index.php?page=boutique">Boutique</a></li>
                    <div class="clean"></div>
                </ul>
            </div>
            <div id="corps">
                <div id="corps-int">
                        <div id="full">
                            <div id="corps-gauche">
                              <?php
                            	if(!$_GET->page or !file_exists('./pages/'.$_GET->page.'.php'))
				{
                                    header("location: index.php?page=news");
				}else
				{
                                    include_once('./pages/'.$_GET->page.'.php');
				}?>
                            </div>
                            <?php
                            if(isset(session_islog())):?>
                            <div id="corps-droite">

                                            <div id="menudroite">
                                                <div class="titre">Compte</div>
                                                <div class="content">
												<div id="bu">
													<ul class="FondMenu">
														<li><a href="index.php?page=compte" class="menuG">Gestion du compte</a></li>
														<li><a href="index.php?page=boutique" class="menuM">Boutique</a></li>
													</ul>
													</div>
                                                </div>
                                            </div>
                            </div>
                           <?php endif;
                            if($session->level and $_SESSION['level'] >= $level_admin):?>
						    <div id="corps-droite">

                                            <div id="menudroite">
                                                <div class="titre">Admin</div>
                                                <div class="content">
												<div id="bu">
													<ul class="FondMenu">
														<li><a href="index.php?page=compte_admin" class="menuG">Gestion des comptes</a></li>
														<li><a href="index.php?page=boutique/admin" class="menuM">Gestion de la Boutique</a></li>
														<li><a href="index.php?page=news_admin" class="menuG">Gestion des News</a></li>
														<li><a href="index.php?page=clean_cache" class="menuM">Vider le Cache</a></li>
													</ul>
													</div>
                                                </div>
                                            </div>
                            </div>
                            <?php endif?>
							<div id="corps-droite">

                                            <div id="menudroite">
                                                <div class="titre">Menu</div>
                                                <div class="content">
												<div id="bu">
													<ul class="FondMenu">
														<li><a href="index.php?page=ladder" class="menuG">Classement</a></li>
														<li><a href="index.php?page=ladder_vote" class="menuM">Classement Vote</a></li>
														<li><a href="index.php?page=ladder_guild" class="menuG">Classement Guilds</a></li>
														<li><a href="index.php?page=guessbook" class="menuM">Livre D'or</a></li>
														<li><a href="index.php?page=inscrip" class="menuG">Inscription</a></li>
													</ul>
													</div>
                                                </div>
                                            </div>
                            </div>
                            <div id="corps-droite">

                                            <div id="menudroite">
                                                <div class="titre">Infos</div>
                                                <div class="content">
												<div id="bu">
													<ul class="FondMenu">
														<li><a class="menuG">état du serveur :
																<?php
																$check = @fsockopen ($ip_serv, $port_serv, $errno, $errstr, 1.0);
																if (!$check)
																{
																	?><strong><font color="red"><?php
																	echo "Hors Ligne";?></strong></font><?php
																}
																else
																{
																	?><strong>
																	<font color="green"><?php
																	echo "En Ligne"; ?></strong></font><?php
																	$gs_online = TRUE;
																}
																@fclose($check);?></a></li>
														<li><a class="menuM">état base de données :
																<?php
																$check = @fsockopen ($ip_db, 3306, $errno, $errstr, 1.0);
																if (!$check)
																{
																	?><strong>
																	<font color="red"><?php
																	echo "Hors Ligne";?></strong></font><?php
																}
																else
																{
																	?><strong>
																	<font color="green"><?php
																	echo "En Ligne"; ?></strong></font><?php
																	$gs_online = TRUE;
																}
																@fclose($check);?></a></li>
														<li><a class="menuG">Nombre de comptes : <font color=green><?php echo nbLigne('accounts');?></font></a></li>
														<li><a class="menuM">Nombre de perso : <font color=green><?php echo nbLigne('personnages');?></font></a></li>
                                                                                                                <li><a class="menuG">Nombre de connexions : <?php $nbCo = database_query('SELECT COUNT(account) AS connected FROM `accounts` WHERE `logged` = "1"')->fetch();echo '<font color=green>'.$nbCo->connected.'</font>';?></a></li>
														<li><a href="index.php?page=cgu" class="menuM">Conditions générales D'utilisation</a></li>
													</ul>
													</div>
                                                </div>
                                            </div>
                            </div>
                            <div class="clean"></div>
                        </div>
                </div>
            </div>


    <div id="footer"><a href="http://www.KitGraphique.net" title="Kits Graphiques" class="footer1"></a><a href="http://www.djokx.com" title="Services WebMaster" class="footer2"></a></div>
	</div>
</body>
</html>
```

## Fonctionnalités

- [x] Un système de configuration (simple)
- [x] Un registre (enregistre des variables de façon à ce qu'elles soient utilisable n'importe où dans le code)
- [x] Un Loader (s'occupe de charge des classes / lib, ect...)
- [x] Un système de bdd utilisant PDO, mais simplifié, aliant facilité et sécurité
- [x] gestion des ActiveRecord (Opérations de sauvegarde ou de suppression facilités)
- [x] Gestions des entrés de façon sécurisé (sans passer directement par $_GET ou $_POST) avec nettoyage des varaibles
- [x] Système de cache performant et simple
- [x] Système de gestion des sorties (Output), avec vues et layout en natif

## Liens :

Description du projet : [Funky-emu](http://www.funky-emu.net/showthread.php?tid=41661&pid=336602#pid336602)
