<?php
//PDO Connection file
// mysql://b5bcc1e7cbeba6:64b1232c@eu-cdbr-west-03.cleardb.net/heroku_db94a9d4da5b206?reconnect=true
	$host='eu-cdbr-west-03.cleardb.net';
	$dbuser='b5bcc1e7cbeba6';
	$dbpassword='64b1232c';
	$database='heroku_db94a9d4da5b206';
	$port="3306";
	//Options
	$options = array (
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		PDO::MYSQL_ATTR_SSL_CAPATH => '.ssl',
		PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => 'false'
	);

	try{
		// PDO Connection
		$connexion = new PDO("mysql:host=$host;dbname=$database", $dbuser, $dbpassword, $options);
		// Error mode
		$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// $connexion = new mysqli($host, $dbuser, $dbpassword, $database, $port);

	}
	catch(Exception $e){
		echo 'Connection failed : '.$e->getMessage();
	}
?>