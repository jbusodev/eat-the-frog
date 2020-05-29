<?php
session_start();
require_once('../connexionPDO.php');
require_once('functions.php');

/* ------------------------- INITIALISATION DES VARIABLES -------------------- */
$user = $_SESSION['user'];
$pseudo = isset( $_SESSION['pseudo']) ? $_SESSION['pseudo'] : '';
$intnImage = isset( $_SESSION['iImage'] ) ? $_SESSION['iImage'] : '';
$nClasseur = isset( $_POST['nItem'] ) ? $_POST['nItem'] : '';
$action = isset( $_POST['action'] ) ? $_POST['action'] : '';
// Reprise de l'objet Tache contenant les champs
$newClasseur = isset( $_POST['newClasseur'] ) ? $_POST['newClasseur'] : '';
$newTitres = isset( $_POST['newTitres'] ) ? $_POST['newTitres'] : '';
// Attribution des champs de l'objet dans des variables
$titreClasseur = $newClasseur !== '' && isset($newClasseur['titreClasseur']) && $newClasseur['titreClasseur'] !== '' ? $newClasseur['titreClasseur'] : '';
$nbRepertoire = $newClasseur !== '' && isset($newClasseur['nbRepertoire']) && $newClasseur['nbRepertoire'] !== '' ? $newClasseur['nbRepertoire'] : '';
$fileName = $newClasseur !== '' && isset($newClasseur['image']) && $newClasseur['image'] !== '' ? $newClasseur['image'] : '';

// Conversion et protection des variables
$intUser = intval($user);
$intnClasseur = intval($nClasseur);
$intNbR = intval($nbRepertoire);
$titreClasseur = cleanString($titreClasseur);

$reponseCl = '';
$reponseTitres = '';
$reponseImage = '';
$reponse = '';
$isInserted = false;
/* ------------------------- END - INITIALISATION DES VARIABLES -------------------- */


switch ($action){
    case 'add':
        try{
            if( $nbRepertoire !== '' && $titreClasseur !== '' ){
                // Si l'ajout du classeur réussit, on reprend son numero pour l'ajout des titres
                if( ajoutClasseur($connexion, $intUser, $intNbR, $titreClasseur, $intnImage) ){
                    // Récupération du numero du nouveau classeur (dernier enregistrement)
                    $query_last = "SELECT * FROM tbl_classeurs WHERE numero = (SELECT MAX(numero) from tbl_classeurs)";
                    $res_last = $connexion->prepare($query_last);
                    $res_last->execute();
                    $obj_classeur = $res_last->fetch(PDO::FETCH_OBJ);
                    $nClasseur = $obj_classeur->numero;
                    $intnClasseur = intval($nClasseur);
                    $res_last->closeCursor();
                    unset($query_last, $res_last, $obj_classeur);
                    // Ajout des titres du classeur
                    if(ajoutTitres($connexion, $newTitres, $intnClasseur)){
                        $reponseTitres = 'OK';
                    } else {
                        $reponseTitres = "Erreur SQL. Impossible d'ajouter les titres au répertoire (classeur.php : l.50-53)";
                    }
                    // Ajout de l'image si une image a été uploadée
                    if( $fileName !== '' && !existeImage($connexion, $fileName)) {
                        if ($fileName !== 'NULL'){
                            if( ajoutImage($connexion, $fileName) ){
                                // Récupération du numero de la nouvelle image (dernier enregistrement)
                                $query_last = "SELECT * FROM tbl_images WHERE numero = (SELECT MAX(numero) from tbl_images)";
                                $res_last = $connexion->prepare($query_last);
                                $res_last->execute();
                                $obj_image = $res_last->fetch(PDO::FETCH_OBJ);
                                $nImage = $obj_image->numero;
                                $intnImage = intval($nImage);
                                $res_last->closeCursor();
                                $_SESSION['iImage'] = $intnImage;
                                $_SESSION['fileName'] = $fileName;
                                unset($query_last, $res_last, $obj_image);
                                $reponseImage = 'OK';
                            } else {
                                $reponseImage = "Erreur SQL. Impossible d'ajouter l'image. (classeur.php : l.56-68)";
                            }
                        }
                    } else {
                        $reponseImage = "OK";
                    }
                    $reponseCl = 'OK';


                } else {
                    $reponseCl = "Erreur SQL. Impossible d'ajouter le classeur. (classeur.php)";
                }
            }
            else {
                $reponseCl = 'Les champs marqués d\'une * sont obligatoires';
            }
        } catch(Exception $e){
            $reponseCl = 'ko';
            $reponseTitres = 'ko';
        }
        break;
    case 'edit':
        try{
            if( $nbRepertoire !== '' && $titreClasseur !== '' ){
                if( $fileName !== '') {
                    if( $fileName !== 'NULL' ){
                        if( !existeImage($connexion, $fileName)) {
                            // Ajout de l'image si une image a été uploadée
                            if( ajoutImage($connexion, $fileName) ){
                                // Récupération du numero de la nouvelle image (dernier enregistrement)
                                $query_last = "SELECT * FROM tbl_images WHERE numero = (SELECT MAX(numero) from tbl_images)";
                                $res_last = $connexion->prepare($query_last);
                                $res_last->execute();
                                $obj_image = $res_last->fetch(PDO::FETCH_OBJ);
                                $nImage = $obj_image->numero;
                                $intnImage = intval($nImage);
                                $res_last->closeCursor();
                                unset($query_last, $res_last, $obj_image);
                                $reponseImage = 'OK';
                            } else {
                                $reponseImage = "Erreur SQL. Imossible d'ajouter l'image. (classeur.php)";
                            }
                        } else {
                            /* Dans le cas où on choisit une image existante, elle est selectionnée */
                            $query = "SELECT * FROM tbl_images WHERE nomImage = :fileName";
                            $res = $connexion->prepare($query);
                            $res->bindValue(':fileName', $fileName, PDO::PARAM_INT);
                            $res->execute();
                            $obj_image = $res->fetch(PDO::FETCH_OBJ);
                            $nImage = $obj_image->numero;
                            $res->closeCursor();
                            $intnImage = intval($nImage);
                            unset($query, $res, $obj_image);
                            $reponseImage = 'OK';
                        }
                    } else{
                        $reponseImage = 'OK';
                        $intnImage = 'NULL';
                    }
                } else {
                    $reponseImage = 'OK';
                }
                if( $intnImage !== '' ){
                    $newImage = "num_tbl_images = $intnImage,";
                } else {
                    $newImage = '';
                }
                // Modification du classeur
                $query_add = "UPDATE tbl_classeurs SET nbRepertoire = :nbRep, $newImage titreClasseur = :titre WHERE numero = $intnClasseur";
                $res_add = $connexion->prepare($query_add);
                // Liaison des variables pour protection de la requête
                $res_add->bindValue(':nbRep', $intNbR, PDO::PARAM_INT);
                $res_add->bindValue(':titre', $titreClasseur, PDO::PARAM_STR);
                $res_add->execute();
                $res_add->closeCursor();
                $reponseCl = 'OK';

            } else {
                $reponseImage = 'ko';
                $reponseCl = 'ko';
            }
            // Suppression des titres actuels
            if($nClasseur !== ''){
                if( suppressionTitres($connexion, $intnClasseur) ){
                $reponseTitresD = 'OK';
                }
            } else {
                $reponseTitresD = 'ko';
            }
            // Ajout des nouveaux titres du classeur
            if($nClasseur !== ''){
                if(ajoutTitres($connexion, $newTitres, $intnClasseur)){
                    $reponseTitresA = 'OK';
                }
            } else {
                $reponseTitresA = 'ko';
            }
            $reponseTitres = $reponseTitresA === 'OK' && $reponseTitresD === 'OK' ? 'OK' : 'ko' ;
            unset($reponseTitresA, $reponseTitresD);


        } catch(Exception $e){
        }
        break;
    case 'print':
        break;
    case 'delete':
        try{
            // Modification du classeur
                $query_del = "DELETE FROM tbl_classeurs WHERE numero = $intnClasseur";
                $res_del = $connexion->prepare($query_del);
                $res_del->execute();
                $res_del->closeCursor();
                $reponseCl = 'OK';
        } catch(Exception $e){
            $reponseCl = 'ko';
            $reponseImage = 'ko';
        }
        break;
    default :
        break;
}

switch ($action){
    case 'add':
        $reponse = $reponseCl === 'OK' && $reponseTitres === 'OK' && $reponseImage === 'OK' ? 'OK': '<p>'. $reponseCl .'</p><p>'. $reponseImage .'</p>';
        break;
    case 'edit':
        $reponse = $reponseCl === 'OK' && $reponseTitres === 'OK' && $reponseImage === 'OK' ? 'OK': '<p>'. $reponseCl .'</p><p>'. $reponseImage .'</p>';
        break;
    case 'delete':
        $reponse = $reponseCl === 'OK' ? 'OK' : '<p>'. $reponseCl .'</p><p>'. $reponseImage .'</p>';
        break;
    default :
        $reponse = 'ko';
        break;
}

$arr = array('reponse' => $reponse);
echo json_encode($arr);
