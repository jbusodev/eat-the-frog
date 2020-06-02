<?php
//PDO Connection file - EDIT PARAMATERS BELOW IF YOU ARE HOSTING ON YOUR LOCAL MACHINE
	$host='q7cxv1zwcdlw7699.chr7pe7iynqr.eu-west-1.rds.amazonaws.com';
	$dbuser='b4f8r56806xqe4h9';
	$dbpassword='ghhtk018gt24b6i4'; // ENTER YOUR PASSWORD HERE
	$database='tndjl5p9q5z0x107';
	//Options
	$PARAM_options = array (
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	);

	try{
		$connexion = new PDO(
			"mysql:host=$host;dbname=$database", $dbuser, $dbpassword, $PARAM_options
		);
	}
	catch(Exception $e){
		echo 'Connection failed : '.$e->getMessage().'<br />';
		echo 'N° : '.$e->getCode();
	}
?>