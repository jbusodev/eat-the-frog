<?php
require_once('../connexionPDO.php');
require_once('functions.php');

$reponseUser = '';
$reponsePwd = '';
$pseudo = '';
$isDisabled = TRUE;

/* Vérification champ LOGIN*/
if( isset($_POST['user']) ) {
    $pseudo = $_POST['user'];
    if ($pseudo != '') {
        $query_login = "SELECT pseudo FROM tbl_users WHERE pseudo = :pseudo";
        $res_test = $connexion->prepare($query_login);
        $res_test->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
        $res_test->execute();
        $result = $res_test->fetch(PDO::FETCH_OBJ);
        if($result != NULL) {
                $reponseUser = "OK";
        }
        else {
                $reponseUser = "Pseudo incorrect";
        }
    } else {
            $reponseUser = 'Veuillez remplir ce champ';
    }
} else {
        $reponseUser = "Veuillez remplir ce champ";
}
/* FIN Vérification champ LOGIN*/

/* Vérification champ MOT DE PASSE*/
if ( isset($_POST['pwd']) ) {
    $pwd = $_POST['pwd'];
    if ($pwd != '') {
        $query_test = "SELECT password FROM tbl_users WHERE password = MD5(:mdp) AND pseudo = :pseudo";
        $res_test = $connexion->prepare($query_test);
        $res_test->bindValue(':mdp', $pwd, PDO::PARAM_STR);
        $res_test->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $res_test->execute();
        $result = $res_test->fetch(PDO::FETCH_OBJ);
        if($result != NULL) {
                $reponsePwd = "OK";
        }
        else {
                $reponsePwd = "Mot de passe incorrect";
        }
    } else {
        $reponsePwd = "Veuillez remplir ce champ";
    }
} else {
    $reponsePwd = "Veuillez remplir ce champ";
}
$redirect = $reponseUser === 'OK' && $reponsePwd === 'OK' ? 'taches.php':'';

if($redirect !== '') {
    $isDisabled = FALSE;
    $query_test = "SELECT * FROM tbl_users WHERE pseudo = :pseudo";
    $res_test = $connexion->prepare($query_test);
    $res_test->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
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
$arr = array('isDisabled' => $isDisabled, 'redirect' => $redirect, 'reponseUser' => $reponseUser, 'reponsePwd' => $reponsePwd, 'user' => $pseudo, 'pwd' => $pwd);
echo json_encode($arr);
/* FIN Vérification champ LOGIN*/

