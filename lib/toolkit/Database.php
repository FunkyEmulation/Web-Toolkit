<?php
//--------------------------
// Web Toolkit par v4vx
// Pour Funky-Emulation
// Version 1.0
//--------------------------

if(!defined('TOOLKIT_VERSION'))
    exit("Veuillez charger le toolkit avant d'utiliser Database !");

if(TOOLKIT_VERSION_ID < 100)
    exit("La version du toolkit ne correspond pas. Veuillez mettre à jour le Toolkit, ou Database !");

#==================================================
#     Classes des gestion de bases de données
#==================================================

/**
 * Classe de gestion de bases de données
 * @since 1.0
 */
class Database extends PDO {

    /**
     * Dernière connexion effectué
     * @var Database
     */
    private static $lastConnexion;
    /**
     * Dernière requête exécuté
     * @var string
     */
    private $lastQuery = '';

    /**
     * {@inheritdoc}
     */
    public function __construct($dsn, $username, $pass = null) {
        $opt = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_STATEMENT_CLASS => array('Statement', array($this))
        );
        parent::__construct($dsn, $username, $pass, $opt);
        self::$lastConnexion = $this;
    }

    /**
     * Crée une nouvelle connexion, configuré dans la configuration
     * @param string $name Nom de la connexion
     * @return Database
     * @throws DatabaseException
     */
    public static function connect($name = 'default'){
        if(!isset(Toolkit::instance()->config['database'][$name]))
            throw new Exception("La configuration <b>$name</b> n'existe pas !");

        $config = Toolkit::instance()->config['database'][$name];

        if(isset($config['dsn'])){
            return self::$lastConnexion = new self(
                $config['dsn'],
                empty($config['username']) ? 'root' : $config['username'],
                empty($config['pass']) ? '' : $config['pass']
            );
        }

        return self::create_mysql_connexion(
                empty($config['host']) ? '127.0.0.1' : $config['host'],
                empty($config['dbname']) ? 'unknown' : $config['dbname'],
                empty($config['username']) ? 'root' : $config['username'],
                empty($config['pass']) ? '' : $config['pass']
        );

    }

    /**
     * Crée une nouvelle connexion avec un serveur mysql
     * @param string $host IP du serveur mysql
     * @param string $dbname Nom de la base de données
     * @param string $username Nom d'utilisateur
     * @param string $pass Mot de passe
     * @return Database
     */
    public static function create_mysql_connexion($host, $dbname, $username = 'root', $pass = ''){
        return self::$lastConnexion = new self(
                'mysql:host='.$host.';dbname='.$dbname,
                $username,
                $pass
        );
    }

    /**
     * Retourne la dernière connection utilisé
     * @return Database
     */
    public static function getLastConnexion(){
        return self::$lastConnexion;
    }

}

/**
 * Classe de gestion de requête
 * @since 1.0
 */
class Statement extends PDOStatement {

    /**
     * Connexion utilisé pour créer ce Statement
     * @var Database
     */
    private $_connexion;

    protected function __construct($connexion) {
        $this->_connexion = $connexion;
    }

    /**
     * {@inheritdoc}
     * @return ActiveRecord
     */
    public function fetch($fetch_style = null, $cursor_orientation = PDO::FETCH_ORI_NEXT, $cursor_offset = 0) {
        if ($fetch_style === null)
            $this->setFetchMode(PDO::FETCH_CLASS, 'ActiveRecord', array(false, $this->_connexion));
        return parent::fetch($fetch_style, $cursor_orientation, $cursor_offset);
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function fetchAll($fetch_style = null, $fetch_argument = null, $ctor_args = array()) {
        if($fetch_style === null){
            $fetch_style = PDO::FETCH_CLASS;
            $fetch_argument = 'ActiveRecord';
            $ctor_args = array(false, $this->_connexion);
        }

        return parent::fetchAll($fetch_style, $fetch_argument, $ctor_args);
    }

}

/**
 * Ligne d'enregistrement d'une table
 * Peut être utiliser en style OOP
 * ou procedurial (tableau)
 * @since 1.0
 */
class ActiveRecord implements Iterator, ArrayAccess {

    /**
     * Les valeurs des colonnes courantes
     * @var array
     */
    private $vars = array();
    /**
     * Valeur des colonnes à l'initialisation
     * @var array
     */
    private $old_vars = array();
    /**
     * Si une entré est nouvelle ou non
     * Utile pour la méthode save (INSERT ou UPDATE ?)
     * @var boolean
     */
    private $is_new;
    /**
     * Connexion utilisé pour créer cet objet (ou passé au constructeur)
     * @var Database
     */
    private $connexion;

    /**
     * Crée une nouvelle ligne
     * @param boolean $is_new Indique si la ligne existe déjà dans la bdd ou non
     */
    public function __construct($is_new = true, Database $connexion = null) {
        if($connexion === NULL)
            $connexion = Database::getLastConnexion ();

        $this->connexion = $connexion;
        $this->is_new = $is_new;
    }

    /**
     * Variable à enregistrer lors de la sérialisation
     * @return array
     */
    public function __sleep() {
        return array_keys($this->vars);
    }

    #######################
    ### Accès style OOP ###
    #######################

    /**
     * Getter style OOP
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        return isset($this->vars[$name]) ? $this->vars[$name] : null;
    }

    /**
     * Setter style OOP
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value) {
        if (!isset($this->vars[$name]))
            $this->old_vars[$name] = $value;
        $this->vars[$name] = $value;
    }

    public function __unset($name) {
        unset($this->vars[$name]);
        unset($this->old_vars[$name]);
    }

    public function __isset($name) {
        return isset($this->vars[$name]);
    }

    ##########################
    ### Méthodes itérateur ###
    ##########################

    public function current() {
        return current($this->vars);
    }

    public function next() {
        return next($this->vars);
    }

    public function rewind() {
        return reset($this->vars);
    }

    public function key() {
        return key($this->vars);
    }

    public function valid() {
        return isset($this->vars[$this->key()]);
    }


    ###########################
    ### Accès style tableau ###
    ###########################

    public function offsetExists($offset) {
        return isset($this->vars[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->vars[$offset]) ? $this->vars[$offset] : null;
    }

    public function offsetSet($offset, $value) {
        $this->$offset = $value;
    }

    public function offsetUnset($offset) {
        unset($this->vars[$offset]);
        unset($this->old_vars[$offset]);
    }


    ################################
    ### Intéractions avec la BDD ###
    ################################

    /**
     * Enregistre la ligne dans la bdd
     * Si is_new = true : une nouvelle ligne est inséré (INSERT INTO)
     * Sinon on met à jour la ligne courante (UPDATE)
     * @param string $table Le nom de la table où l'on doit enregistrer la ligne
     * @return boolean
     */
    public function save($table) {
        if ($this->is_new)
            return Database::getLastConnexion()->prepare('INSERT INTO ' . addslashes($table) . '(' . implode(', ', array_keys($this->vars)) . ') VALUES(:' . implode(',:', array_keys($this->vars)) . ')')->execute($this->vars);

        $tmp1 = $tmp2 = $tmp3 = array();

        foreach ($this->vars as $col => $value) {
            $tmp1[] = $col . '= :' . $col;
        }

        foreach ($this->old_vars as $col => $value) {
            $tmp2[] = $col . '=:old_' . $col;
            $tmp3['old_' . $col] = $value;
        }
        
        $this->old_vars = $this->vars;
        return Database::getLastConnexion()->prepare('UPDATE ' . addslashes($table) . ' SET ' . implode(', ', $tmp1) . ' WHERE ' . implode(' AND ', $tmp2))->execute($this->vars + $tmp3);
    }

    /**
     * Supprime la ligne de la table
     * @param string $table Nom de la table où se trouve la ligne
     * @return boolean
     */
    public function delete($table) {
        $tmp = array();
        foreach ($this->vars as $col => $value)
            $tmp[] = $col . '= :' . $col;
        $state = Database::getLastConnexion()->prepare('DELETE FROM ' . addslashes($table) . ' WHERE ' . implode(' AND ', $tmp))->execute($this->vars);

        if($state){
            unset($this);
            return true;
        }
        return false;
    }

}

#==================================================
#                Fonctions helpers
#==================================================

/**
 * Crée une nouvelle connexion avec un serveur mysql
 * @param string $host IP du serveur mysql
 * @param string $dbname Nom de la base de données
 * @param string $username Nom d'utilisateur
 * @param string $pass Mot de passe
 * @return Database
 * @since 1.0
 */
function database_mysql_connect($host, $dbname, $username = 'root', $pass = ''){
    return Database::create_mysql_connexion($host, $dbname, $username, $pass);
}


/**
 * Crée une nouvelle connexion, configuré dans la configuration
 * @param string $name Nom de la connexion
 * @return Database
 * @throws DatabaseException
 */
function database_connect($name = 'default'){
    return Database::connect($name);
}

/**
 * Initialise la connexion à la base de données (à utiliser comme PDO)
 * Pour ce connecter à mysql, il est possible d'utiliser database_mysql_connect
 * Pour utiliser une connexion déjà configuré, veuillez utiliser database_connect
 * @param string $dsn
 * @param string $username
 * @param string $pass
 * @return \Database
 */
function database_init($dsn, $username, $pass = null){
    return new Database($dsn, $username, $pass);
}

/**
 * Exécute une requête SQL
 * @param string $query La requête SQL
 * @param Database $connexion La connexion à utiliser, ou NULL
 * @param mixed $arg
 * @param mixed $_
 * @return Statement
 */
function database_query($query, $connexion = null, $arg = null, $_ = null){
    if($connexion === null)
        $connexion = Database::getLastConnexion();

    if($arg === null)
        return $connexion->query($query);

    if(is_array($arg))
        $args = $arg;
    else
        $args = array_slice(func_get_args(), 2);

    $stmt = $connexion->prepare($query);
    return $stmt->execute($args);
}