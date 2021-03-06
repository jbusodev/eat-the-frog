<?php
require_once('pdo.php');
require_once('functions.php');

$reponse='';
$username = '';

/* LOGIN Check in Database*/
if( isset($_POST['user']) ) {
    $username = $_POST['user'];
    if ($username != '') {
        $query_login = "SELECT pseudo FROM tbl_users WHERE pseudo = :pseudo";
        $res_test = $connexion->prepare($query_login);
        $res_test->bindValue(":pseudo", $username, PDO::PARAM_STR);
        $res_test->execute();
        $result = $res_test->fetch(PDO::FETCH_OBJ);
        if($result != NULL) {
                $responseUser = "OK";
        }
    }
}
/* END LOGIN Check*/

/* Password Check*/
if ( isset($_POST['pwd']) ) {
    $pwd = $_POST['pwd'];
    if ($pwd != '') {
        $query_test = "SELECT password FROM tbl_users WHERE password = MD5(:mdp) AND pseudo = :pseudo";
        $res_test = $connexion->prepare($query_test);
        $res_test->bindValue(':mdp', $pwd, PDO::PARAM_STR);
        $res_test->bindValue(':pseudo', $username, PDO::PARAM_STR);
        $res_test->execute();
        $result = $res_test->fetch(PDO::FETCH_OBJ);
        if($result != NULL) {
                $responsePwd = "OK";
        }
    }
}
// Set redirection if credentials OK
$redirect = $responseUser === 'OK' && $responsePwd === 'OK' ? 'taches.php':'';

if($redirect !== '') {
    $isDisabled = FALSE;
    $query_test = "SELECT * FROM tbl_users WHERE pseudo = :pseudo";
    $res_test = $connexion->prepare($query_test);
    $res_test->bindValue(":pseudo", $username, PDO::PARAM_STR);
    $res_test->execute();
    $result = $res_test->fetch(PDO::FETCH_OBJ);
    if($result != NULL) {
            session_start();
            $_SESSION['niveau'] = $result->num_tbl_niveaux;
            $_SESSION['user'] = $result->numero;
            $_SESSION['nomUtilisateur'] = $result->nomUtilisateur;
            $_SESSION['prenomUtilisateur'] = $result->prenomUtilisateur;
            $_SESSION['pseudo'] = $result->pseudo;
    }
}

$username = $username !== '' ? 'ok' : '';
$pwd = $pwd !== '' ? 'ok' : '';

$arr = array('redirect' => $redirect, 'responseUser' => $responseUser, 'responsePwd' => $responsePwd, 'user' => $username, 'pwd' => $pwd);
echo json_encode($arr);
/* END PASSWORD Check*/

