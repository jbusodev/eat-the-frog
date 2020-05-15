<?php
if(session_id() == "") {
    session_start();
    $user = $_SESSION['user'];
    $page = $_SESSION['page'];
    require_once('../connexionPDO.php');
    require_once('../calls/functions.php');
    $nbRepertoire = isset( $_POST['nbRep'] ) ? $_POST['nbRep'] : '';
    $nItem = isset( $_POST['nItem'] ) ? $_POST['nItem'] : '';
    $andc = $nItem !== '' ? ') AND ((tbl_classeurs.numero)=' : '';
}
unset($_SESSION['titres']);
$andc = $nItem !== '' ? ') AND ((tbl_classeurs.numero)=' : '';
$arrR = array();
if($nbRepertoire !== ''){
    $intNbR = intval($nbRepertoire);
    // Ex. si nbR = 10 : ((10+0)/2)+1
    //        nbR = 11 : ((11+1)/2)+1
    $demi = (($intNbR+$intNbR%2)/2)+1;
    // Condition qui définit le nombre de répertoire du classeur
    if($nItem !== ''){
        // Requêtes pour connaître le nombre de titres enregistré pour le classeur.
        $query = "SELECT tbl_titres.numero, tbl_titres.titre, tbl_references_cl.chronologie
                    FROM tbl_titres INNER JOIN ((tbl_users INNER JOIN tbl_classeurs ON tbl_users.numero = tbl_classeurs.num_tbl_users) INNER JOIN tbl_references_cl ON tbl_classeurs.numero = tbl_references_cl.num_tbl_classeurs) ON tbl_titres.numero = tbl_references_cl.num_tbl_titres
                        WHERE (((tbl_classeurs.num_tbl_users)=$user $andc $nItem))
                            ORDER BY tbl_references_cl.chronologie;";
        $res = $connexion->prepare($query);
        $res->execute();
        // Nombre de lignes(titres) d'après la requête.
        $count = $res->rowCount();
        $res->closeCursor();
    } else {
        $count = 0;
    }
    
    echo '<div class="cells">';
    for($i = 1; $i <$intNbR+1; $i++){
        $chronologie = '';
        if($i === $demi ){
            echo '</div><div class="cells">';
        }
        try{
            if( $nItem !== '' ){
                // Requête des titres par orde chronologique
                $query_order = "SELECT tbl_titres.titre, tbl_references_cl.chronologie, tbl_references_cl.num_tbl_titres
                                    FROM tbl_users INNER JOIN (tbl_titres INNER JOIN (tbl_classeurs INNER JOIN tbl_references_cl ON tbl_classeurs.numero = tbl_references_cl.num_tbl_classeurs) ON tbl_titres.numero = tbl_references_cl.num_tbl_titres) ON tbl_users.numero = tbl_classeurs.num_tbl_users
                                        WHERE (((tbl_references_cl.chronologie)=$i) AND ((tbl_classeurs.num_tbl_users)=$user $andc $nItem))";
                $res_o = $connexion->prepare($query_order);
                $res_o->execute();
                $titre = $res_o->fetch(PDO::FETCH_OBJ);
                /* si la requête retourne un résultat, reprend la chronologie
                 * du titre. Sinon, met la chronologie à 0 */
                $chronologie = $titre != NULL ? $titre->num_tbl_titres : '0';
                $res_o->closeCursor();
            } else{
                $chronologie = '0';
            }
        } catch (Exception $e){
            $chronologie = '0';
        }
        $arrR = listeTitres($connexion, $i, $chronologie);
    }
    echo '</div>';
} else {
    echo '<div class="little_font-size">Veuillez sélectionner un nombre de répertoires</div>';
}