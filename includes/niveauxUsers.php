<?php
if(session_id() == "") {
    session_start();
    $user = $_SESSION['user'];
    $page = $_SESSION['page'];
    $niveau = $_SESSION['niveau'];
    require_once('../connexionPDO.php');
    require_once('../calls/functions.php');
}
$nUser = isset( $_POST['nUser'] ) ? $_POST['nUser'] : '0';
$nNiveau = '0';

$intUser = intval($nUser);

if( $nUser !== '0' ){
    $queryListe = "SELECT * FROM tbl_users WHERE numero = $intUser";
    $resListe = $connexion->prepare($queryListe);
    $resListe->execute();
    $obj = $resListe->fetch(PDO::FETCH_OBJ);
    if( $resListe != NULL ){
        $nNiveau = $obj->num_tbl_niveaux;
    }
    $resListe->closeCursor();
}
listeNiveaux($connexion, $niveau, $nUser, $nNiveau, false);
unset($nUser);