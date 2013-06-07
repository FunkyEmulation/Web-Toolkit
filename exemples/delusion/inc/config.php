<?php
if(!defined('TOOLKIT_VERSION'))
    exit("Accès direct à la configuration non autorisé !");

//configuation de la bdd
$db_static = 'ancestra_static'; //nom de la db static (pour la boutique)

//configuration serveur
$ip_serv = '127.0.0.1'; //adresse du serveur
$port_serv = '444'; //port du serveur de jeu

//config du d�bug perso
$debug_map = 1000; //map o� le perso doit se t�l�porter
$debug_cell = 250; //cellule

//configuation de la page rejoindre
$url_config = 'config.xml'; //lien vers la config.xml
$url_client = 'http://www.megaupload.com/?d=QQCHPUZ6'; //lien vers le client dofus 1.29 ->Celui par d�faut est l'installeur officiel

//configuration g�n�rale
$serv_name = 'CMS by v4vx'; //nom du serveur
$url_forum = 'forum/index.php'; //adresse du forum
$url_accueil = 'index.php?page=news'; //adresse de la page d'accueil ou du portail
$level_admin = 3; //level pour pouvoir administrer le site
$temps_vote = 120; //temps entre deux vote en minutes
$pts_vote = 15; //nombre de points par votes
$lien_vote = 'http://php.net/manual/fr/function.time.php'; //lien de vote rpg (ou autre ;p)

//Boutique :
$boutiqueOn = true; //boutique on ou pas ? (true/false)
$pts_max = 100; //pourcentage de points � payer en plus pour un jet max

//page d'infos
	//donn�es techniques
		$infrastructure = 'local'; //infrastructure du serveur (d�di�, hamachi, VPS, no-ip...)
		$ram = '2048'; //m�moire vive du serveur
		$ram_total = '3584'; //m�moire vive total (SWAP + RAM)
		$grandeur_ram = 'Mio'; //grandeur utilis�, Gio, Mio, Kio, Tio...
		//dans le language courant on parle de Mo/Go, mais en r�alit�, c'est de Mio/Gio (x1024 au lieu de x1000)
		$processeur = '2 x 2.40 Ghz'; //processeurs
		$BP = '2'; //bande passante (habituellement en Mb/s)
		$grandeur_BP = 'Mb/s'; //grandeur utilis� dans la BP (Kb/s, Mb/s, Gb/s...)
		$hebergeur = 'local'; //h�bergeur utilis� (OVH, exon, free-H, local...)
		$HDD = '250'; //taille du disque dure
		$grandeur_HDD = 'Gio'; //grandeur du disque dur (Gio / Tio)
		$show_tech = true; //voir les infos techniques sur la page infos ? true / false
	//rates
		$rate_pvm = '1';
		$rate_pvp = '1';
		$rate_drop = '1';
		$rate_kamas = '1';
	//jeu
		$type = 'Anka_like'; //type de serveur (pvp, pvm, fun...)
		$crea = 'v4vx'; //nom / pseudo du cr�ateur
		$raison_crea = ''; //raison de la cr�ation du serveur
//FIN page d'info

//gestion du cache
$cache = false; //met en place le syst�me de cache ou non 
//(si votre version php est inf�rieur � 4 
//ou que mon cms ne marche pas, mettez false.)

$ladder = 3600; //temps de rafraichissement du ladder (en sec)
$ladder_vote = 3600; //idem pour ladder vote
$perso = 3600; //idem pour les perso

//je tiens � pr�siser que plus les valeurs sont petites, plus le serveur va utiliser de requ�tes,
//et donc plus il sera lent. Veuillez trouver le juste milieu entre le temps d'attente et les ressources
//utilis�. Pour un serveur � fort trafique, mettez un nombre haut,
//pour un serveur � faible trafique, vous pouvez mettre un nombre tr�s bas.

return array(
    'output' => array(
        'default_layout' => null,
        'views_path' => dirname(LIB_DIR).DS.'views'.DS,
        'xss_clean' => true
    ),
    'database' => array(
        'default' => array(
            'host' => '127.0.0.1',
            'username' => 'root',
            'pass' => '',
            'dbname' => 'test'
        )
    ),
    'input' => array(
        'GET_object' => true,
        'POST_object' => true
    )
);
