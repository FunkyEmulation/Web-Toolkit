<?php
define('CONFIG_FILE', 'config.php');
include '../../lib/toolkit/toolkit.php';

output_view('hello', array('name' => $_GET['name']));

new test;

echo 'temps de génération : ', number_format((microtime(true) - START_TIME) * 1000, 2), 'ms';
