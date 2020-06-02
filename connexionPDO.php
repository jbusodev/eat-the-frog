<?php
//PDO Connection file
// mysql://b5bcc1e7cbeba6:64b1232c@eu-cdbr-west-03.cleardb.net/heroku_db94a9d4da5b206?reconnect=true
	$host='eu-cdbr-west-03.cleardb.net';
	$dbuser='b5bcc1e7cbeba6';
	$dbpassword='64b1232c'; // ENTER YOUR PASSWORD HERE
	$database='heroku_db94a9d4da5b206';
	//Options
	$PARAM_options = array (
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	);

	try{
		$connexion = new PDO(
			"mysql:host=$host;dbname=$database", $dbuser, $dbpassword
		);
		$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(Exception $e){
		echo 'Connection failed : '.$e->getMessage().'<br />';
		echo 'N° : '.$e->getCode();
	}
?>