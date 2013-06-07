<?php
//--------------------------
// Web Toolkit par v4vx
// Pour Funky-emulation
// Version 1.0
//--------------------------

if(!defined('TOOLKIT_DIR'))
    exit('Veuillez charger le toolkit avant d\'utiliser le système de session !');

if(TOOLKIT_VERSION_ID < 101)
    exit('Version du toolkit trop ancienne pour faire tourner le système de session !');

#==================================================
#                 Classe principale
#==================================================

/**
 * Classe de gestion de sessions
 * Utilisation des sessions natives php pour ne pas
 * changer le code actuelle des site (cf: session_start() / $_SESSION[...])
 * @since 1.1
 */
class Session extends Singleton{
    /**
     * Fonction de connexion
     * @var callable
     */
    private $login_callback;

    protected function __construct() {
        if(session_status() !== PHP_SESSION_ACTIVE){
            session_start();
        }

        if(!isset($_SESSION['CLIENT_IP']))
            $_SESSION['CLIENT_IP'] = $_SERVER['REMOTE_ADDR'];

        if($_SESSION['CLIENT_IP'] !== $_SERVER['REMOTE_ADDR'])
            $this->logout();
    }

    public function __get($name) {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }

    public function __set($name, $value) {
        $_SESSION[$name] = $value;
    }

    /**
     * Utilisation de la session comme une fonction
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function __invoke($name, $value = null) {
        if($value === null)
            return $_SESSION[$name];
        $_SESSION[$name] = $value;
    }

    /**
     * Déconnecte le client
     */
    public function logout(){
        session_regenerate_id();
        session_destroy();
        //pour éviter tout problèmes
        $_SESSION = array();
    }

    /**
     * Enregistre une fonction de connexion
     * Cette fonction doit prendre pour arguments (au minimum)
     * le nom d'utilisateur, et le mot de passe.
     * Elle doit OBLIGATOIREMENT retourner le tableau de données
     * de session à enregister en cas de succès, ou FALSE en cas d'erreur.
     *
     * Exemple :
     * $session->register_login_callback(function($user, $pass){
     *          $stmt = database_query('SELECT * FROM accounts WHERE account = ? AND pass = ?', null, $user, $pass);
     *          $data = $stmt->fetch(PDO::FETCH_ASSOC);
     *          if(!$data)
     *              return false;
     *          return $data;
     * });
     * @param callable $callback
     */
    public function register_login_callback($callback){
        if(!is_callable($callback)){
            trigger_error('fonction invalide passé à <b>register_login_callback</b> !', E_USER_WARNING);
            return;
        }

        $this->login_callback = $callback;
    }

    /**
     * Tente de se connecter
     * @param mixed $data si $data est un tableau, il est directement enregister dans la session (et le client est considéré comme connecté) sinon, $data est le nom d'utilisateur, et il utilise la fonction de login enregistré par register_login_callback()
     * @param mixed $pass Mot de passe, si $data n'est pas un tableau, et que l'on utilise la fonction de register_login_callback()
     * @param mixed $_ aure argument à passer à la callback de login (optionnel)
     * @return boolean TRUE si la connexion est bien effectué. FALSE en cas d'erreur
     */
    public function login($data, $pass = null, $_ = null){
        if(is_array($data)){
            array_merge($_SESSION, $data);
            $this->isLog = true;
            return true;
        }

        if(!is_callable($this->login_callback)){
            trigger_error('Aucunes fonctions de connexion définie !', E_USER_WARNING);
            $this->logout();
            return false;
        }

        if($pass === null)
            trigger_error("Nombre d'arguments invalide pour <em>Session::login()</em>. Un deux arguments attendue, mais qu'un seul n'a été passé !", E_USER_WARNING);

        $data = call_user_func_array($this->login_callback, func_get_args());
        if(!$data){
            $this->logout();
            return false;
        }

        array_merge($_SESSION, $data);
        return $this->isLog = true;
    }

    /**
     * Test si le client est connecté
     * @return boolean
     */
    public function isLogged(){
        return $this->isLog;
    }
}


#==================================================
#                 Fonctions helpers
#==================================================

/**
 * initialise la session
 * @return Session
 */
function session_init(){
    return Session::instance();
}

/**
 * Tente de se connecter
 * @param mixed $data si $data est un tableau, il est directement enregister dans la session (et le client est considéré comme connecté) sinon, $data est le nom d'utilisateur, et il utilise la fonction de login enregistré par register_login_callback()
 * @param mixed $pass Mot de passe, si $data n'est pas un tableau, et que l'on utilise la fonction de register_login_callback()
 * @param mixed $_ aure argument à passer à la callback de login (optionnel)
 * @return boolean TRUE si la connexion est bien effectué. FALSE en cas d'erreur
 */
function session_login($data, $pass = null, $_ = null){
    return call_user_func_array(array(Session::instance(), 'login'), func_get_args());
}

/**
 * Test si le client est connecté
 * @return boolean
 */
function session_islog(){
    return isset($_SESSION['isLog']) ? $_SESSION['isLog'] : false;
}

/**
 * Déconnecte le client
 */
function session_logout(){
    Session::instance()->logout();
}