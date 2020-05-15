<?php
if(session_id() == "") {
    require_once('../connexionPDO.php');
}
$query_titres = "SELECT numero, titre FROM tbl_titres ORDER BY titre";
$res_t = $connexion->prepare($query_titres);
$res_t->execute();
echo '<select id="s_titres">';
    echo '<option value="0"></option>';
while( $titre = $res_t->fetch(PDO::FETCH_OBJ) ){
    echo '<option value="'. $titre->numero .'">'. $titre->titre .'</option>';
}
echo '</select>';
$res_t->closeCursor();