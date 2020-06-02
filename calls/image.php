<?php
require_once('pdo.php');
require_once ('../calls/functions.php');
$output_dir = "../images/classeurs/";

$nImage = isset( $_POST['nItem'] ) ? $_POST['nItem'] : '';

$intNimage = intval($nImage);
unset($nImage);

// Selection du nom de fichier à supprimer
$query = "SELECT * FROM tbl_images WHERE numero = :numero";
$res = $connexion->prepare($query);
$res->bindValue(':numero', $intNimage, PDO::PARAM_INT);
$res->execute();
$obj_image = $res->fetch(PDO::FETCH_OBJ);
$fileName = $obj_image->nomImage;
$res->closeCursor();

$filePath = $output_dir. $fileName;
if (file_exists($filePath)) {
    unlink($filePath);
    suppressionImage($connexion, $fileName);
    $reponse = 'OK';
} else {
    $reponse = "Aucun fichier avec ce nom ne se trouve sur l'application";
}
$arr = array('reponse' => $reponse);
echo json_encode($arr);
