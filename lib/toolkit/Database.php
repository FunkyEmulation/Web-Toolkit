<?php
//--------------------------
// Web Toolkit par v4vx
// Pour Funky-Emulation
// Version 1.0
//--------------------------

if(!defined('TOOLKIT_VERSION'))
    exit("Veuillez charger le toolkit avant d'utiliser Database !");

if(TOOLKIT_VERSION_ID < 101)
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
        try{
            parent::__construct($dsn, $username, $pass, $opt);
        }catch(Exception $e){
            throw new SQLException($e->getMessage(), '');
        }
        self::$lastConnexion = $this;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare($statement, $driver_options = array()) {
        $this->lastQuery = $statement;
        try{
            return parent::prepare($statement, $driver_options);
        }catch(Exception $e){
            throw new SQLException($e->getMessage(), $statement);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function query($statement) {
        $this->lastQuery = $statement;
        try{
            return parent::query($statement);
        }catch(Exception $e){
            throw new SQLException($e->getMessage(), $statement);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function exec($statement) {
        try{
            return parent::exec($statement);
        }catch(Exception $e){
            throw new SQLException($e->getMessage(), $statement);
        }
    }

    /********************
     * Fonctions helpers
     ********************/

    /**
     * Initialise le générateur de requêtes
     * @return \QueryBuilder
     */
    public function builder(){
        return new QueryBuilder($this);
    }

    /**
     * Compte le nombre d'éléments dans une table
     * @param string $table Nom de la table
     * @param array $requirements condition WHERE
     * @return int Nombre de lignes
     */
    public function count($table, array $requirements = array()){
        if($requirements === array()){
            $arr = $this->query('SELECT COUNT(*) FROM '.addslashes($table))->fetch(PDO::FETCH_ASSOC);
            return $arr['COUNT(*)'];
        }

        $query = new QueryBuilder($this);
        $data = $query->select('COUNT(*)')
                ->from($table, true)
                ->where($requirements)
                ->execute()->fetch(PDO::FETCH_ASSOC);
        return $data['COUNT(*)'];
    }

    /**************************************
     * Fonctions de gestion des connexions
     **************************************/

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
        if(self::$lastConnexion === null)
            self::connect();
        return self::$lastConnexion;
    }

    /**
     * Retourne la dernière requête exécuté
     * @return string
     */
    public function getLastQuery(){
        return $this->lastQuery;
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

        try{
            return parent::fetch($fetch_style, $cursor_orientation, $cursor_offset);
        }catch(Exception $e){
            throw new SQLException($e->getMessage(), $this->_connexion->getLastQuery());
        }
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

        try{
            return parent::fetchAll($fetch_style, $fetch_argument, $ctor_args);
        }catch(Exception $e){
            throw new SQLException($e->getMessage(), $this->_connexion->getLastQuery());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function execute($input_parameters = null) {
        try{
            return parent::execute($input_parameters);
        }catch(Exception $e){
            throw new SQLException($e->getMessage(), $this->_connexion->getLastQuery());
        }
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

    /**
     * Retourne la ligne sous forme de tableau
     * @return array
     */
    public function toArray(){
        return $this->vars;
    }
}


#==================================================
#         Création et gestion des requêtes
#==================================================

/**
 * Classe de création de requêtes
 * @since 1.1
 */
class QueryBuilder{
    private static $CONDITION_TYPES = array('OR', 'AND', 'XOR');
    private static $CONDITION_SIGNS = array('<', '>', '<=', '>=', '<>', 'LIKE', '=');
    /**
     * Requête généré
     * @var string
     */
    private $query = '';
    /**
     * Connexion courante utilisé pour exécuter la requête
     * @var Database
     */
    private $connexion;
    private $q_sel = array(), $q_from = array(), $q_where = array(),
            $q_params = array(), $q_set = array(), $q_values = array();

    /**
     * Table à utiliser pour l'insersion
     * @var string
     */
    private $q_insert_table = '';
    /**
     * Nom de la table pour le update
     * @var string
     */
    private $q_update_table = '';
    /**
     * Type de requête
     * @var int
     */
    private $type;
    
    const SELECT = 1;
    const INSERT = 2;
    const DELETE = 3;
    const UPDATE = 4;


    public function __construct(Database $conn = null){
        if($conn === null)
            $this->connexion = Database::getLastConnexion();
        else
            $this->connexion = $conn;
    }
    /**
     * Initialise une requête de sélection
     * @param mixed $selected colonnes à sélectionner, sous forme de tableau, ou de chaine SQL
     * @param boolean $escape échaper les sélecteur ou non ? ne mettez true que si il y a un risque de faille.
     * @return \QueryBuilder
     */
    public function select($selected = '*', $escape = false){
        if($this->type !== null && $this->type !== self::SELECT)
            trigger_error('Sélection dans une requête autre que select !', E_USER_WARNING);
        
        if(!is_array($selected))
            $selected = explode(',', $selected);

        if($escape){
            $selected = array_map(function($value){
                return trim(addslashes($value));
            }, $selected);
        }

        $this->type = self::SELECT;

        $this->q_sel += $selected;
        return $this;
    }

    /**
     * Ajout de la clause FROM
     * @param string $table nom de la ou les tables à sélectionner
     * @param boolean $escape échaper le nom de la table ?
     * @return \QueryBuilder
     */
    public function from($table, $escape = false){
        if($this->type === self::UPDATE)
            trigger_error('La clause FROM n\'est pas géré dans une requête de type update !', E_USER_WARNING);

        $this->q_from[] = $escape ? addslashes($table) : $table;
        return $this;
    }

    /**
     * Ajoute une condition WHERE
     * @param string $column nom de la colonne
     * @param mixed $value valeur a chercher
     * @param string $type AND OR XOR
     * @param string $sign operateur de comparaison ( = < > <= >= <> LIKE)
     * @return \QueryBuilder
     */
    public function where($column, $value = null, $type = 'AND', $sign = '='){
        if(!is_array($column)){
            $this->_where($column, $value, $type, $sign);
            return $this;
        }

        foreach($column as $c => $v)
            $this->_where($c, $v, $type, $sign);

        return $this;
    }

    /**
     * Ajoute une condition AND
     * @param string $col colonne à tester
     * @param mixed $value valeur à trouver
     * @param string $sign signe de comparaison
     * @return \QueryBuilder
     */
    public function and_where($col, $value, $sign = '='){
        $this->_where($col, $value, 'AND', $sign);
        return $this;
    }

    /**
     * Ajoute une condition OR
     * @param string $col colonne à tester
     * @param mixed $value valeur à trouver
     * @param string $sign signe de comparaison
     * @return \QueryBuilder
     */
    public function or_where($col, $value, $sign = '='){
        $this->_where($col, $value, 'OR', $sign);
        return $this;
    }

    private function _where($col, $value, $type, $sign){
        if($type === self::INSERT)
            trigger_error('la clause WHERE n\'est pas géré par insert !', E_USER_WARNING);
        
        $col = trim(addslashes($col));

        if(!in_array($type, self::$CONDITION_TYPES)){
            trigger_error('Type de comparaison indisponible : <b>'.$type.'</b> !', E_USER_WARNING);
            $type = 'AND';
        }

        if(!in_array($sign, self::$CONDITION_SIGNS)){
            trigger_error('Operateur de comparaison indisponible : <b>'.$sign.'</b> !', E_USER_WARNING);
            $sign = '=';
        }

        $this->q_where[] = array($type, $col, $sign);
        $this->q_params['w_'.md5($col)] = $value;
    }

    /**
     * Initialise une requête d'update
     * @param string $table Nom de la table
     * @return \QueryBuilder
     */
    public function update($table){
        if($this->type !== null)
            trigger_error('Le type de la requête a déjà été définie. Il sera donc redéfinie en temps que UPDATE.', E_USER_WARNING);

        $this->type = self::UPDATE;
        $this->q_update_table = trim(addslashes($table));
        return $this;
    }

    /**
     * Ajout de la clause SET
     * @param mixed $column nom de la colonne ou tableau colonne => valeur
     * @param mixed $value valeur (si le un string est passé pour $colunm)
     * @return \QueryBuilder
     */
    public function set($column, $value = null){
        if($this->type !== self::UPDATE)
            trigger_error('La clause SET n\'est supporté uniquement que par une requête de type update !', E_USER_WARNING);

        if(is_array($column))
            $this->q_set += $column;
        else
            $this->q_set[$column] = $value;

        return $this;
    }

    /**
     *
     * @param type $table
     * @param array $values les valeurs à insérer (non obligatoire)
     * @return \QueryBuilder
     */
    public function insert($table, array $values = null){
        $this->type = self::INSERT;
        $this->q_insert_table = trim(addslashes($table));
        return $this->values($values);
    }

    /**
     * Clause VALUES pour une requête INSERT
     * @param mixed $column nom de la colonne ou tableau colonne => valeur
     * @param mixed $value
     * @return \QueryBuilder
     */
    public function values($column, $value = null){
        if($this->type !== self::INSERT)
            trigger_error('La clause VALUES n\'est pas utilisable en dehors d\'une requête INSERT !', E_USER_WARNING);
        
        if(is_array($column))
            $this->q_values += $column;
        else
            $this->q_values[$column] = $value;

        return $this;
    }

    /**
     * Retourne la requête courante
     * @param boolean $force_recompile forcer la recompilation de la requête ?
     * @return string la requête généré
     */
    public function getQuery($force_recompile = false){
        if($this->query === '' || $force_recompile){
            switch($this->type){
                case self::SELECT:
                    $this->_compile_select_query();
                    break;
                case self::UPDATE:
                    $this->_compile_update_query();
                    break;
                case self::INSERT:
                    $this->_compile_insert_query();
                    break;
                default:
                    throw new SQLException('Impossible de compiler la requête, car son type n\'a pas été encore définie !', '');
            }
        }

        return $this->query;
    }

    private function _compile_select_query(){
        $this->query = 'SELECT '.implode(', ', $this->q_sel);

        if($this->q_from !== array())
            $this->query .= ' FROM '.implode(', ', $this->q_from);

        $this->query .= $this->_compile_where_clause();
    }

    private function _compile_update_query(){
        $this->query =  'UPDATE '.$this->q_update_table;

        if($this->q_set !== array()){
            $this->query .= ' SET ';
            $tmp = array();
            foreach($this->q_set as $c=>$v){
                $tmp[] = $c.'=:u_'.md5($c);
                $this->q_params['u_'.md5($c)] = $v;
            }

            $this->query .= implode(', ', $tmp);
        }

        $this->query .= $this->_compile_where_clause();
    }

    private function _compile_insert_query(){
        if($this->q_values === array())
            trigger_error('Aucunes valeurs ajoutés à l\'insertion !', E_USER_WARNING);

        $cols = array();
        $vals = array();
        foreach($this->q_values as $c=>$v){
            $cols[] = $c;
            $vals[] = ':i_'.md5($c);
            $this->q_params['i_'.md5($c)] = $v;
        }

        $this->query = sprintf('INSERT INTO %s(%s) VALUES(%s)', $this->q_insert_table, implode(', ', $cols), implode(', ', $vals));
    }

    private function _compile_where_clause(){
        $return = '';
        if($this->q_where !== array()){
            $return .= ' WHERE ';
            $first = true;
            foreach($this->q_where as $c){
                if(!$first)
                    $return .= ' '.$c[0];

                $return .= ' '.$c[1].$c[2].':w_'.md5($c[1]);
                $first = false;
            }
        }

        return $return;
    }

    /**
     * Execute la requête généré.
     * Si il s'agit d'une sélection, Statement st retourné
     * Sinon le statue est retourné
     * @return Statement
     */
    public function execute(){
        $query = $this->getQuery();

        if(count($this->q_params) > 0){
            $stmt = $this->connexion->prepare($query);
            $state = $stmt->execute($this->q_params);

            if($this->type === self::SELECT)
                return $stmt;
            else
                return $state;
        }

        if($this->type === self::SELECT)
            return $this->connexion->query($query);

        return $this->connexion->exec($query);
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

/**
 * Compte le nombre d'éléments dans une table
 * @param string $table Nom de la table
 * @param array $requirements condition WHERE
 * @return int Nombre de lignes
 */
function database_count($table, array $requirement = array(), Database $conexion = null){
    if($conexion === null)
        $conexion = Database::getLastConnexion();

    return $conexion->count($table, $requirement);
}