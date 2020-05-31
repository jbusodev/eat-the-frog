<?php
/**
 * This file draws list of images from the Database
 * into the dialog of the Folders page
 * When selecting an existing image to choose from
 */
if(session_id() == "") {
    session_start();
    $user = $_SESSION['user'];
    $page = $_SESSION['page'];
    require_once('../connexionPDO.php');
    require_once('../calls/functions.php');
    $nbRepertoire = isset( $_POST['nbRep'] ) ? $_POST['nbRep'] : '';
    $nItem = isset( $_POST['nItem'] ) ? $_POST['nItem'] : '';
    $andc = $nItem !== '' ? ') AND ((tbl_classeurs.numero)=' : ''; // selects folder to edit - SQL segment
}
unset($_SESSION['titres']);
$chemin = 'images/classeurs/';

$query = "SELECT * FROM tbl_images";
$results = $connexion->prepare($query);
$results->execute();
$count = $results->rowCount();
echo '<tbody>';
    echo '<tr>';
$init = 1;
while( $ligne = $results->fetch(PDO::FETCH_OBJ) ){
    $srcImage = $chemin . $ligne->nomImage;
    $dateUploaded = $ligne->dateUploaded;
    $numero = $ligne->numero;
    $isChecked = isset($nImage) && $nImage == $numero ? ' checked' : ''; // if the Folder has an image, checks the corresponding image in the list
    echo '<td class="cases">';
        echo '<span><img style="max-width:100px;vertical-align:top;" src="'. $srcImage .'" alt="'. $ligne->nomImage .'"/></span>';
        echo '<span style="max-width:100px;display:inline-block;width:20px;height:20px"><input type="radio" name="choixImage" value="'. $ligne->nomImage .'" data-img="'. $numero .'"'. $isChecked .'/></span>';
    echo '</td>';
    if( $init % 4 == 0 ){
        echo '</tr>';
        if( $init !== $count ){
            echo '<tr>';
        }
    }
    $init++;
}
if( $count=== 0 ){ // If no image is present in the database, draws cell informing the user
        echo '<td colspan=5>Il n\'a aucune image pour l\'instant.</td>';
    echo '</tr>';
}
$results->closeCursor();
echo '</tbody>';