<?php
//PDO Connection file
	$PARAM_hote='localhost';
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
		echo 'Erreur : '.$e->getMessage().'<br />';
		echo 'N° : '.$e->getCode();
	}
?>