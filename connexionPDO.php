<?php
//PDO Connection file - EDIT PARAMATERS BELOW IF YOU ARE HOSTING ON YOUR LOCAL MACHINE
	$PARAM_hote='postgres://nxmrnloptcddpn:42703db7b2bb298ddfd2f2555af5d9ee4f3223da540acbf4db5fe26da96d0b91@ec2-54-246-87-132.eu-west-1.compute.amazonaws.com:5432/d7ggb86d69c7un';
	$PARAM_nom_bd='crapaud';
	$PARAM_utilisateur='root';
	$PARAM_mot_passe=''; // ENTER YOUR PASSWORD HERE
	//Options
	$PARAM_options = array (
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	);

	try{
		$connexion = new PDO(
			'mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd, $PARAM_utilisateur, $PARAM_mot_passe, $PARAM_options
		);
	}
	catch(Exception $e){
		echo 'Connection failed : '.$e->getMessage().'<br />';
		echo 'N° : '.$e->getCode();
	}
?>