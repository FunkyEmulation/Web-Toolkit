<?php
if(!defined('TOOLKIT_VERSION'))
    exit("Accès direct à la configuration non autorisé !");

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
