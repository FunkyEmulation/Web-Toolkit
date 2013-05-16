<?php
include '../../lib/toolkit/toolkit.php';

set_config(array(
    'output'=>array(
        'views_path' => __DIR__.DS.'views'.DS,
        'default_layout' => 'layout',
        'xss_clean' => true
    )
));

try{
    $db = Database::create_mysql_connexion('127.0.0.1', 'test', 'Vincent');
}catch(Exception $e){
    exit($e);
}

$stmt = $db->query('SELECT * FROM news');

echo '<pre>';
var_dump($stmt->fetchAll());
echo '</pre>';

echo 'temps de génération : ', number_format((microtime(true) - START_TIME) * 1000, 2), 'ms';
