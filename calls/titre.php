<?php
session_start();
require_once('../connexionPDO.php');
require_once('functions.php');

/* ------------------------- INITIALISATION DES VARIABLES -------------------- */
$reponse = "";
$numero = isset( $_POST['numero'] ) ? $_POST['numero'] : '';
$newTitre = isset( $_POST['newTitre'] ) ? $_POST['newTitre'] : '';
$action = isset( $_POST['action'] ) ? $_POST['action'] : '';
// Conversion et protection des variables / chaînes de caractères
$intNumero = intval($numero);

cleanString($newTitre);

/* ------------------------- FIN - INITIALISATION DES VARIABLES -------------------- */
    switch ($action){
        case 'add':
            try{
                if( $newTitre !== '' ){
                    if( !existeTitre($connexion, $newTitre) ) {
                        $query_add = "INSERT INTO tbl_titres(numero, titre) VALUES(null, :titre)";
                        $res_add = $connexion->prepare($query_add);
                        // Liaison des variables pour protection de la requête
                        $res_add->bindValue(':titre', $newTitre, PDO::PARAM_STR);
                        $res_add->execute();
                        $res_add->closeCursor();
                        $reponse = 'OK';
                    } else {
                        $reponse = 'Le titre existe déjà';
                    }
                }
            } catch(Exception $e){
                $reponse = "Impossible d'ajouter le titre";
            }
            break;
        case 'edit':
            try{
                if( $newTitre !== '' ){
                    $query_save = "UPDATE tbl_titres SET titre = :titre WHERE tbl_titres.numero=:num";
                    $res_save = $connexion->prepare($query_save);
                    // Liaison des variables pour protection de la requête
                    $res_save->bindValue(':titre', $newTitre, PDO::PARAM_STR);
                    $res_save->bindValue(':num', $intNumero, PDO::PARAM_INT);
                    $res_save->execute();
                    $res_save->closeCursor();
                    $reponse = 'OK';
                } else {
                    $reponse = "Veuillez entrer un titre";
                }
            } catch (Exception $e){
                $reponse = "Impossible de mettre à jour le titre";
            }
            break;
        default :
            $reponse = "Action non définie";
            break;
    }
    
$arr = array('reponse' => $reponse);
echo json_encode($arr);
