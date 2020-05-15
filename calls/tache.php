<?php
session_start();
require_once('../connexionPDO.php');
require_once('functions.php');

$user = $_SESSION['user'];
$nItem = isset( $_POST['nItem'] ) ? $_POST['nItem'] : '';
$action = isset( $_POST['action'] ) ? $_POST['action'] : '';
$aujourdhui = date('d-m-Y');
// Reprise de l'objet Tache contenant les champs
$newTache = isset( $_POST['newTache'] ) ? $_POST['newTache'] : '';
// Attribution des champs de l'objet dans des variables
$nomTache = $newTache !== '' && isset($newTache['tache']) && $newTache['tache'] !== '' ? $newTache['tache'] : '';
$prioTache = $newTache !== ''  && isset($newTache['priorite']) && $newTache['priorite'] !== '0' ? $newTache['priorite'] : '';
$dateDTache = $newTache !== '' && isset($newTache['dateD']) && $newTache['dateD'] !== '' ? $newTache['dateD'] : '';
$dateFTache = $newTache !== '' && isset($newTache['dateF']) && $newTache['dateF'] !== '' ? $newTache['dateF'] : '';
$remTache = $newTache !== '' && isset($newTache['remarque'])&& $newTache['remarque'] !== '' ? $newTache['remarque'] : '';

// Conversion et formatage des variables 
$intUser = intval($user);
$intPrioTache = $prioTache !== '' ? intval($prioTache) :'';
$intnItem = intval($nItem);
formatDate($dateDTache);
formatDate($dateFTache);
cleanString($nomTache);
cleanString($remTache);

$reponse = '';

    switch ($action){
        case 'end':
            try{
                $query_save = "UPDATE tbl_taches SET dateFin=:dfin WHERE numero=:nItem";
                $res_save = $connexion->prepare($query_save);
                // Liaison des variables pour protection de la requête
                $res_save->bindValue(':dfin', $dateFTache, PDO::PARAM_STR);
                $res_save->bindValue(':nItem', $intnItem, PDO::PARAM_INT);
                $res_save->execute();
                $res_save->closeCursor();
                $reponse = 'Votre tâche a été modifiée !';
            } catch (Exception $e){
                $reponse = 'ko';
            }
            break;
        case 'add':
            if( $nomTache !=='' && $prioTache !=='' && $dateDTache !=='' ) {
                try{
                    $query_save = "INSERT INTO tbl_taches(numero, num_tbl_users, num_tbl_priorites, tache, dateDebut, dateFin, remarque) VALUES(null, :user, :prio, :tache, :ddebut, :dfin, :rem)";
                    $res_save = $connexion->prepare($query_save);
                    // Liaison des variables pour protection de la requête
                    $res_save->bindValue(':user', $intUser, PDO::PARAM_INT);
                    $res_save->bindValue(':tache', $nomTache, PDO::PARAM_STR);
                    $res_save->bindValue(':prio', $intPrioTache, PDO::PARAM_INT);
                    $res_save->bindValue(':ddebut', $dateDTache, PDO::PARAM_STR);
                    $res_save->bindValue(':dfin', $dateFTache, PDO::PARAM_STR);
                    $res_save->bindValue(':rem', $remTache, PDO::PARAM_STR);
                    $res_save->execute();
                    $res_save->closeCursor();
                    $reponse = 'Votre tâche a été ajoutée !';
                } catch (Exception $e){
                    $reponse = 'ko';
                }
            } else {
                $reponse = 'ko';
            }
            break;
        case 'edit':
            if( $nomTache !=='' && $prioTache !=='' && $dateDTache !=='' ) {
                try{
                    $query_save = "UPDATE tbl_taches SET num_tbl_users = :user, num_tbl_priorites = :prio, tache = :tache, dateDebut = :ddebut, dateFin = :dfin, remarque = :rem WHERE tbl_taches.numero=:nItem";
                    $res_save = $connexion->prepare($query_save);
                    // Liaison des variables pour protection de la requête
                    $res_save->bindValue(':user', $intUser, PDO::PARAM_INT);
                    $res_save->bindValue(':tache', $nomTache, PDO::PARAM_STR);
                    $res_save->bindValue(':prio', $intPrioTache, PDO::PARAM_INT);
                    $res_save->bindValue(':ddebut', $dateDTache, PDO::PARAM_STR);
                    $res_save->bindValue(':dfin', $dateFTache, PDO::PARAM_STR);
                    $res_save->bindValue(':rem', $remTache, PDO::PARAM_STR);
                    $res_save->bindValue(':nItem', $intnItem, PDO::PARAM_INT);
                    $res_save->execute();
                    $res_save->closeCursor();
                    $reponse = 'Votre tâche a été modifiée !';
                } catch (Exception $e){
                    $reponse = 'ko';
                }
            }else {
                $reponse = 'ko';
            }
            break;
        case 'delete':
            try{
            $query_save = "DELETE FROM tbl_taches WHERE numero=:nItem";
            $res_save = $connexion->prepare($query_save);
            $res_save->bindValue(':nItem', $intnItem, PDO::PARAM_INT);
            $res_save->execute();
            $res_save->closeCursor();
            $reponse = 'Votre tâche a été supprimée !';
            } catch (Exception $e){
                $reponse = 'ko';
            }
            break;
        default :
            $reponse = 'ko';
            break;
    }
    
$arr = array('reponse' => $reponse, 'aujourdhui' => $aujourdhui);
echo json_encode($arr);
