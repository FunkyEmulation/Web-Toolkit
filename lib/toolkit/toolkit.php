<?php
//--------------------------
// Web Toolkit par v4vx
// Pour Funky-emulation
// Version 1.0
//--------------------------


#==================================================
#                   Constantes
#==================================================


if(PHP_VERSION_ID < 50300)
    exit("Votre version de PHP est trop ancienne pour faire tourner Web Toolkit :/<br/>Version minimale requise : <b>php5.3</b>");

/**
 * Version du toolkit
 */
define('TOOLKIT_VERSION', '1.0');
define('TOOLKIT_VERSION_ID', 101);

/**
 * Signe pour séparer les dossier
 * cf : / sous UNIX \ sous Windows
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Répertoire de Toolkit
 */
define('TOOLKIT_DIR', __DIR__ . DS);

/**
 * Répertoire des libraries
 */
define('LIB_DIR', dirname(__DIR__).DS);

/**
 * Extension PHP
 */
define('EXT', '.php');

/**
 * Date de début d'exécution
 */
define('START_TIME', microtime(true));

/**
 * Utilisation de mémoire avant exécution
 */
define('START_MEMORY', memory_get_usage());

/**
 * Fichier de configuration
 * @since 1.1
 */
if(!defined('CONFIG_FILE'))
    define('CONFIG_FILE', TOOLKIT_DIR.'config/main'.EXT);


#==================================================
#               Classes abstraites
#==================================================

/**
 * Classe implementant le modèle Singleton
 * @since 1.0
 */
abstract class Singleton {

    public static final function instance() {
        static $instance;

        if ($instance === null)
            $instance = new static();

        return $instance;
    }

}

/**
 * Classe décrivant les composants de Toolkit
 * @since 1.0
 */
abstract class Component {

    /**
     * Instance du Toolkit
     * @var Toolkit
     */
    protected $_instance;

    public function __construct() {
        $this->_instance = Toolkit::instance();
    }

    public function __get($name) {
        return $this->_instance->$name;
    }

    public function __set($name, $value) {
        return $this->_instance->$name = $value;
    }

}

#==================================================
#               Classes principales
#==================================================

/**
 * Classe de base. Charge les éléments importants
 * @since 1.0
 */
class Toolkit{

    /**
     * L'instance de Toolkit
     * @var type 
     */
    private static $_instance;

    /**
     * Variables du registre principal
     * @var array
     */
    private $vars = array();
    /**
     * Configuration
     * @var array
     */
    public $config = array();
    /**
     * Classe de chargement
     * @var Loader
     */
    public $loader;
    /**
     * Classe de sortie
     * @var Output
     */
    public $output;
    /**
     * Classe d'entrées
     * @var Input
     */
    public $input;

    protected function __construct() {
        self::$_instance = $this;
        set_default_config();
        $this->loader = Loader::instance();
    }

    public function __get($name) {
        return isset($this->vars[$name]) ? $this->vars[$name] : null;
    }

    public function __set($name, $value) {
        $this->vars[$name] = $value;
    }

    /**
     * Retourne l'instance courante
     * @return Toolkit
     */
    public static function instance(){
        if(self::$_instance === null)
            new self;

        return self::$_instance;
    }

}

/**
 * Classe de chargement
 * @since 1.0
 */
class Loader extends Singleton {

    /**
     * L'instance de Toolkit
     * @var Toolkit
     */
    private $_instance;

    protected function __construct() {
        $this->_instance = Toolkit::instance();
        
        spl_autoload_register(function($name){
            $path = array(
                TOOLKIT_DIR,
                TOOLKIT_DIR.$name.DS,
                LIB_DIR,
                LIB_DIR.strtolower($name).DS
            );

            foreach($path as $dir){
                if(file_exists($dir.$name.EXT)){
                    require $dir.$name.EXT;
                    break;
                }
            }
        });

        $this->autoload();
    }

    private function autoload(){
        $this->_instance->output = Output::instance();
        $this->_instance->input = Input::instance();
    }

    /**
     * Charge une classe library
     * @param string $name Le nom de la lib
     * @return object
     * @throws LoaderException
     */
    public function library($name, $arg = null, $_ = null){
        $name = ucfirst($name);

        if($this->_instance->$name !== null)
            return $this->_instance->$name;

        if(!class_exists($name))
            throw new Exception("La classe <b>$name</b> n'existe pas !");

        if(is_subclass_of($name, 'Singleton'))
            return $this->_instance->$name = $name::instance();

        if($arg === null)
            return $this->_instance->$name = new $name;

        $args = func_get_args();
        array_shift($args);

        $r = new ReflectionClass($name);
        return $this->_instance->$name = $r->newInstanceArgs($args);
    }

    /**
     * Crée un alias
     * @param string $var Nom de la classe
     * @param string $alias Nouveau nom
     * @throws LoaderException
     */
    public function alias($var, $alias) {
        if ($this->_instance->$var === null)
            throw new Exception("La classe $var n'existe pas !");
        
        if ($this->_instance->$alias !== null)
            throw new Exception("L'alias $alias est déjà utilisé !");
        
        $this->_instance->$alias = $this->_instance->$var;
    }

}

#==================================================
#           Classes d'Entrées / Sortie
#==================================================

/**
 * Classe de gestion de l'affichage
 * @since 1.0
 */
class Output extends Singleton {

    private $contents = '', $cache = false, $vars = array();
    /**
     * Le fichier du thème de base
     * @var string
     */
    public $layout = null;

    protected function __construct() {
        ob_start();
    }

    public function __get($name) {
        return isset($this->vars[$name]) ? $this->vars[$name] : null;
    }

    public function __set($name, $value) {
        $this->vars[$name] = $value;
        if ($this->cache !== false)
            $this->cache['vars'][$name] = $value;
    }

    /**
     * Débute la mise en cache du buffer de sortie
     * @param string $key
     * @return boolean
     */
    public function startCache($key) {
        if (($data = Loader::instance()->library('Cache')->get($key)) !== false) {
            $this->vars+=$data['vars'];
            echo $data['contents'];
            return false;
        }
        $this->cache = array('key' => $key, 'vars' => array());
        ob_start();
        return true;
    }

    /**
     * Enregistre le buffer dans le cache
     * @param int $time Durée de vie en secondes
     */
    public function endCache($time = 60) {
        $this->cache['contents'] = ob_get_clean();
        $this->contents.=$this->cache['contents'];
        Loader::instance()->library('Cache')->set($this->cache['key'], $this->cache, $time);
        $this->cache = false;
    }

    /**
     * Envois le contenue du buffer de sortie au navigateur
     */
    public function flush() {
        $this->contents.=ob_get_clean();

        if($this->layout === null)
            $this->layout = Toolkit::instance()->config['output']['default_layout'];

        if (!empty($this->layout) && $this->contents !== '') {
            require Toolkit::instance()->config['output']['views_path'] . $this->layout . EXT;
        }else
            echo $this->contents;
        
        $this->contents = '';
        ob_start();
    }

    /**
     * Charge une vue dans le buffer de sortie
     * @param string $file Fichier de vue (ne pas mettre l'extension !)
     * @param array $vars Variables à passer à la vue
     */
    public function view($file, array $vars = array()) {
        if(isset(Toolkit::instance()->config['output']['xss_clean']) && Toolkit::instance()->config['output']['xss_clean']){
            $vars = array_map(
                function($value){
                    return filter_var($value, FILTER_SANITIZE_STRING);
                },
                $vars
            );
        }
        
        extract($vars);
        include Toolkit::instance()->config['output']['views_path'] . $file . EXT;
    }

}

/**
 * Classe de gestion des entrées
 * @since 1.0
 */
class Input extends Singleton{
    /**
     * Récupère une variable GET de façon sécurisé (par défaut enlève les balises HTML)
     * @param string $name Le nom de la varaible
     * @param int $filter Le filtre, sous forme de constance FILTER_SANITIZE_*
     * @param type $options Les options du filtre
     * @return string La variable GET sécurisé
     */
    public function get($name, $filter = FILTER_SANITIZE_STRING, $options = null){
        if(!isset($_GET[$name]))
            return '';

        return filter_var($_GET[$name], $filter, $options);
    }

    /**
     * Récupère et nettoit une variable POST (par défaut échape les guillements)
     * @param string $name Le nom de la variable
     * @param int $filter Le filtre, sous forme d'une constante FILTER_SANITIZE_*
     * @param mixed $options Option du filtre
     * @return string La varaible POST nettoyé
     */
    public function post($name, $filter = FILTER_SANITIZE_MAGIC_QUOTES, $options = null){
        if(!isset($_POST[$name]))
            return '';

        return filter_var($_POST[$name], $filter, $options);
    }
}

#==================================================
#               Gestion des erreurs
#==================================================

/*set_error_handler(function($errno, $errstr, $errfile = '', $errline = 0, array $errcontext = array()){
    if(!(error_reporting() & $errno))
        return;

    $errtype = '';
    switch($errno){
        case E_USER_NOTICE:
        case E_NOTICE:
            $errtype = 'Note';
            break;
        case E_USER_WARNING:
        case E_WARNING:
        case E_CORE_WARNING:
            $errtype = 'Attention';
            break;
        case E_STRICT:
            $errtype = 'Standards strictes';
            break;
        case E_DEPRECATED:
        case E_USER_DEPRECATED:
            $errtype = 'Obsolète';
            break;
        default:
            $errtype = 'Erreur';
    }

    
});*/

#==================================================
#               Fonctions helpers
#==================================================

/************************
 * Fonctions pour loader
 ************************/

/**
 * Charge une classe library
 * @param string $name Le nom de la lib
 * @return object
 * @throws LoaderException
 * @since 1.0
 */
function load_library($name, $arg = null, $_ = null){
    if($arg === null)
        return Loader::instance()->library($name);

    return call_user_func_array(array(Loader::instance(), 'library'), func_get_args());
}

/*****************************
 * Fonctions de configuration
 *****************************/

/**
 * Obtien la configuration
 * @param mixed $keys
 * @return mixed
 * @since 1.0
 */
function config_get($keys = array(), $k2 = null, $_ = null){
    if($k2 !== null)
        $keys = func_get_args();
    elseif(!is_array($keys))
        $keys = explode('.', $keys);

    return array_browse_recursively(Toolkit::instance()->config, $keys);
}

/**
 * Met en place une configuration
 * @param array $config
 * @since 1.0
 */
function set_config(array $config){
    Toolkit::instance()->config = array_merge(Toolkit::instance()->config, $config);
}

/**
 * Renseigne le fichier de configuration.
 * Le fichier de configuration doit OBLIGATOIREMENT retourner un tableau
 * /!\ Fonction obsolète, veuillez plutôt définir le fichier de configuration via
 * la constante CONFIG_FILE, permettant l'utilisation de la configuration à l'initialisation du Toolkit
 * @param string $file Le fichier de configuration
 * @return boolean
 * @since 1.0
 * @deprecated since version 1.1
 */
function set_config_file($file){
    if(!file_exists($file)){
        trigger_error ("Le fichier de configuration <b>$file</b> n'existe pas !", E_USER_WARNING);
        return false;
    }

    $config = include $file;

    if(!is_array($config)){
        trigger_error("Le fichier de configuration doit retourner un tableau !", E_USER_WARNING);
        return false;
    }

    set_config($config);
    return true;
}

/**
 * Remet la config par défaut (Spécifié dans la constante CONFIG_FILE
 * @since 1.0
 */
function set_default_config(){
    Toolkit::instance()->config = set_config_file(CONFIG_FILE);
}

/**
 * Charge un fichier de configuration secondaire
 * @param string $file Le ficher de configuration (doit retourner un tableau)
 * @param string $name Le nom utilisé dans la configuration (le nom par défaut est celui du fichier : "config.php" donne "config")
 * @return boolean
 * @since 1.0
 */
function load_config_file($file, $name = null){
    if(!file_exists($file)){
        trigger_error ("Le fichier de configuration <b>$file</b> n'existe pas !", E_USER_WARNING);
        return false;
    }

    $config = include $file;

    if(!is_array($config)){
        trigger_error("Le fichier de configuration doit retourner un tableau !", E_USER_WARNING);
        return false;
    }

    if(!$name)
        $name = basename($file);

    Toolkit::instance()->config[$name] = $config;
    return true;
}

/*********************
 * Fonctions d'Output
 *********************/

/**
 * Vide le buffer et envoit le contenue au client.
 * Ne doit (logiquement) pas être utilisé !
 * @since 1.0
 */
function output_flush(){
    Output::instance()->flush();
}

/**
 * Charge une vue dans le buffer de sortie
 * @param string $view Le nom de la vue
 * @param array $vars
 * @since 1.0
 */
function output_view($view, array $vars = array()){
    Output::instance()->view($view, $vars);
}

/**
 * Charge une vue dans le buffer de sortie
 * @param string $view Le nom de la vue
 * @param array $vars
 * @since 1.0
 */
function output_render($view, array $vars = array()){
    Output::instance()->view($view, $vars);
}

/**
 * Change le layout courant
 * @param string $layout Le nom de la vue de layout
 */
function set_layout($layout = null){
    Output::instance()->layout = $layout;
}

/*******************
 * Fonction d'Input
 *******************/

/**
 * Récupère une variable GET de façon sécurisé (par défaut enlève les balises HTML)
 * @param string $name Le nom de la varaible
 * @param int $filter Le filtre, sous forme de constance FILTER_SANITIZE_*
 * @param type $options Les options du filtre
 * @return string La variable GET sécurisé
 * @since 1.0
 */
function input_get($name, $filter = FILTER_SANITIZE_STRING, $options = null){
    return Input::instance()->get($name, $filter, $options);
}

if(!function_exists('GET')){
    /**
     * Alias de input_get
     */
    function GET($name, $filter = FILTER_SANITIZE_STRING, $options = null){
        return Input::instance()->get($name, $filter, $options);
    }
}

/**
 * Récupère et nettoit une variable POST (par défaut échape les guillements)
 * @param string $name Le nom de la variable
 * @param int $filter Le filtre, sous forme d'une constante FILTER_SANITIZE_*
 * @param mixed $options Option du filtre
 * @return string La varaible POST nettoyé
 */
function input_post($name, $filter = FILTER_SANITIZE_MAGIC_QUOTES, $options = null){
    return Input::instance()->post($name, $filter, $options);
}

if(!function_exists('POST')){
    /**
     * Alias de input_post
     */
    function POST($name, $filter = FILTER_SANITIZE_MAGIC_QUOTES, $options = null){
        return Input::instance()->post($name, $filter, $options);
    }
}

/*********************
 * Autres fonctions
 *********************/

/**
 * Retourne l'instance courante du Toolkit
 * Strictement identique à Toolkit::instance();
 * @return Toolkit
 * @since 1.0
 */
function get_instance(){
    return Toolkit::instance();
}

/**
 * Parcours le tableau $input récusivement et retourne l'élement voulu
 * @param array $input tableau d'entré
 * @param array $keys les clés à parcourir
 * @return mixed Valeur associé à la dernière clé, ou NULL
 * @since 1.0
 */
function array_browse_recursively(array $input, array $keys){
    $key = array_shift($keys);

    if($key === null)
        return $input;

    if(!isset($input[$key]))
        return null;

    if($keys === array()){
        return $input[$key];
    }

    return array_browse_recursively($input[$key], $keys);
}

//Permet d'afficher le buffer a l'arrêt du script
register_shutdown_function('output_flush');

//crée une nouvelle instance de Toolkit, et démarre l'application
return Toolkit::instance();