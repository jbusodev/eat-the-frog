<?php
if(session_id() == "") {
    session_start();
    $user = $_SESSION['user'];
    $page = $_SESSION['page'];
    $niveau = $_SESSION['niveau'];
    require_once('../calls/pdo.php');
    require_once('../calls/functions.php');
}
listeUsers($connexion, $niveau);
