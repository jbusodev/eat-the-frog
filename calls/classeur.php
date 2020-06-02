<?php
session_start();
require_once('pdo.php');
require_once('functions.php');

/* ------------------------- INITIALISATION DES VARIABLES -------------------- */
$user = $_SESSION['user'];
$pseudo = isset( $_SESSION['pseudo']) ? $_SESSION['pseudo'] : '';
$intnImage = isset( $_SESSION['iImage'] ) ? $_SESSION['iImage'] : '';
// Folder id
$nClasseur = isset( $_POST['nItem'] ) ? $_POST['nItem'] : '';
$action = isset( $_POST['action'] ) ? $_POST['action'] : '';
// Reprise de l'objet Classeur contenant les champs
$newClasseur = isset( $_POST['newClasseur'] ) ? $_POST['newClasseur'] : '';

// Get the array Titles containing the fields
$newTitres = isset( $_POST['newTitres'] ) ? $_POST['newTitres'] : '';

// Attribution des champs de l'objet dans des variables
// Title Field value
$titreClasseur = $newClasseur !== '' && isset($newClasseur['titreClasseur']) && $newClasseur['titreClasseur'] !== '' ? $newClasseur['titreClasseur'] : '';
// Directory Number Field value
$nbRepertoire = $newClasseur !== '' && isset($newClasseur['nbRepertoire']) && $newClasseur['nbRepertoire'] !== '' ? $newClasseur['nbRepertoire'] : '';
// Image Option (No image='NULL' | Existing image=image Database's filename | Uploaded image=)
$fileName = $newClasseur !== '' && isset($newClasseur['image']) && $newClasseur['image'] !== '' ? $newClasseur['image'] : '';
// if image option is existing, get their id
$intIDimage = $newClasseur !== '' && isset($newClasseur['idImage']) && $newClasseur['idImage'] !== NULL ? $newClasseur['idImage'] : NULL;

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

$e ='';
/* ------------------------- END - INITIALISATION DES VARIABLES -------------------- */


switch ($action){
    case 'add':
        try{
            if( $nbRepertoire !== '' && $titreClasseur !== '' && $fileName !== ''){
                // Ajout de l'image si une image a été uploadée
                        if ($fileName !== 'NULL'){
                            if( !existeImage($connexion, $fileName)){
                                if( ajoutImage($connexion, $fileName) ){
                                    // Récupération du numero de la nouvelle image (dernier enregistrement)
                                    $query_last = "SELECT * FROM tbl_images WHERE numero = (SELECT MAX(numero) from tbl_images)";
                                    $res_last = $connexion->prepare($query_last);
                                    $res_last->execute();
                                    $obj_image = $res_last->fetch(PDO::FETCH_OBJ);
                                    $nImage = $obj_image->numero;
                                    $intIDimage = intval($nImage);
                                    $res_last->closeCursor();
                                    $_SESSION['iImage'] = $intIDimage;
                                    $_SESSION['fileName'] = $fileName;
                                    unset($query_last, $res_last, $obj_image);
                                    $reponseImage = 'OK';
                                }
                            } else {
                                // Image is selected if existant
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
                        } else {
                            $reponseImage = 'OK';
                        }

                    // Si l'ajout du classeur réussit, on reprend son numero pour l'ajout des titres
                    if( ajoutClasseur($connexion, $intUser, $intNbR, $titreClasseur, $intIDimage) ){
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
                            $reponseTitres = "Erreur SQL. Impossible d'ajouter les titres au répertoire (classeur.php : l.95)";
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
            if( $nbRepertoire !== '' && $titreClasseur !== '' && $fileName !=='' ){
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
                            $_SESSION['iImage'] = $intnImage;
                            $_SESSION['fileName'] = $fileName;
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
                        $nImage = $obj_image->numero; // Gets image ID to update folder reference
                        $res->closeCursor();
                        $intnImage = intval($nImage);
                        unset($query, $res, $obj_image);
                        $reponseImage = 'OK';
                    }
                } else{
                    $reponseImage = 'OK';
                    $reponseCl = 'OK';
                    $intnImage = NULL;
                }
                // Modification du classeur
                $query_edit = "UPDATE tbl_classeurs SET nbRepertoire = :nbRep, num_tbl_images = :image, titreClasseur = :titre WHERE numero = :numero";
                $res_edit = $connexion->prepare($query_edit);
                // Liaison des variables pour protection de la requête
                $res_edit->bindValue(':numero', $intnClasseur, PDO::PARAM_INT);
                $res_edit->bindValue(':titre', $titreClasseur, PDO::PARAM_STR);
                $res_edit->bindValue(':nbRep', $intNbR, PDO::PARAM_INT);
                $res_edit->bindValue(':image', $intIDimage, PDO::PARAM_INT);
                $res_edit->execute();
                $res_edit->closeCursor();
                $reponseCl = 'OK';

            } else {
                $reponseImage = 'Pick one';
                $reponseCl = 'Fill all fields';
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
            $e->getMessage();
        }
        break;
    case 'print':
        break;
    case 'delete':
        try{
            // Suppression du classeur
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

// Response processing
switch ($action){
    case 'add':
        $reponse = $reponseCl === 'OK' && $reponseTitres === 'OK' && $reponseImage === 'OK' ? 'OK': 'Champs Classeur: '. $reponseCl .'. | Option Image: '. $reponseImage;
        break;
    case 'edit':
        $reponse = $reponseCl === 'OK' && $reponseTitres === 'OK' && $reponseImage === 'OK' ? 'OK': 'Champs Classeur: '. $reponseCl .'. | Option Image: '. $reponseImage;
        break;
    case 'delete':
        $reponse = $reponseCl === 'OK' ? 'OK' : 'Champs Classeur: '. $reponseCl .'. | Option Image: '. $reponseImage;
        break;
    default :
        $reponse = 'ko';
        break;
}

$arr = array(
    'reponse' => $reponse,
    'error' => $e,
    /*
    'fileName' => $fileName,
    'nbDir' => $nbRepertoire,
    */
    'imageNumber' => $intIDimage
);
echo json_encode($arr);
