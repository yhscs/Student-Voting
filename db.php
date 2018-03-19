<?php
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); 
    $db = new PDO("mysql:host=DB_HOST;dbname=MAIN_DB_NAME;charset=utf8", 'USERNAME', 'PASSWORD', $options);
	$votersDB = new PDO("mysql:host=DB_HOST;dbname=VOTER_DB_NAME;charset=utf8", 'USERNAME', 'PASSWORD', $options);
?>