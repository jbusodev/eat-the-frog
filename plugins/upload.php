<?php
if(session_id() == ""){
    session_start();
    require_once('../connexionPDO.php');
    require_once('../calls/functions.php');
    
    $user = $_SESSION['user'];
    $pseudo = isset( $_SESSION['pseudo']) ? $_SESSION['pseudo'] : '';
}
$output_dir = "../images/classeurs/";
$aujourdhui = date('Y-m-d H:i:s');
$strAujourdhui = fileName($aujourdhui);
$intnImage = null;
if( isset($_FILES["myfile"]) ) {
    $arr = array();
    
    $error =$_FILES["myfile"]["error"];
    
    if( !is_array($_FILES["myfile"]["name"]) ) {
        $selectedFileName = $_FILES["myfile"]["name"];
        if( !existeImage($connexion, $selectedFileName) ){
            // L'extension du fichier est récupérée
            switch( $_FILES["myfile"]["type"] ){
                case 'image/png':
                    $extension = '.png';
                    break;
                case 'image/jpeg':
                    $extension = '.jpg';
                    break;
                default:
                    $extension = '.jpg';
                    break;
            }
            $fileName = $pseudo .'_'. $strAujourdhui . $extension;
            /* Si l'image n'existe pas sur le serveur, elle est uploadée 
             * sur le serveur */
            move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
            $query_last = "SELECT * FROM tbl_images WHERE numero = (SELECT MAX(numero) from tbl_images)";
            $res_last = $connexion->prepare($query_last);
            $res_last->execute();
            $obj_image = $res_last->fetch(PDO::FETCH_OBJ);
            $nImage = $obj_image->numero;
            $intnImage = intval($nImage);
            $_SESSION['iImage'] = $intnImage;
            $res_last->closeCursor();
            unset($query_last, $res_last, $obj_image);
        } else {
            $fileName = $selectedFileName;
            /* Dans le cas où l'image existe déjà, elle est selectionnée */
            $query = "SELECT * FROM tbl_images WHERE nomImage = :fileName";
            $res = $connexion->prepare($query);
            $res->bindValue(':fileName', $fileName, PDO::PARAM_INT);
            $res->execute();
            $obj_image = $res->fetch(PDO::FETCH_OBJ);
            $nImage = $obj_image->numero;
            $res->closeCursor();
            $intnImage = intval($nImage);
            $_SESSION['iImage'] = $intnImage;
            unset($query, $res, $obj_image, $selectedFileName, $strAujourdhui, $nImage, $intnImage, $aujourdhui, $pseudo, $user);
        }
        if( isset($php_errormsg) ){
            $arr[] = $php_errormsg;
            echo json_encode($arr);
        } else{
            $arr[] = $fileName;
            echo json_encode($arr);
        }
    }
}