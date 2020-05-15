<?php
/*
 * Page contenant toutes les fonctions du site.
 */

// Fonction d'affichage de date dans la page
function afficheDate(&$D) {
    // Si la date est vide, transforme la date en chaîne vide
    if ( $D === '0000-00-00' || $D === null ) {
        $D = '-';
        return $D;
    }
    // Change la date en format usuel (exemple : 26-09-2014)
    $D = date("d-m-Y", strtotime($D));
    return $D;
}

// Fonction de formatage de date pour enregistrement dans la base
function formatDate(&$D){
    // Formate la date $D pour correspondre à celui du SGBD : aaaa-mm-jj
    $D = $D !== '' ? date("Y-m-d", strtotime($D)): '0000-00-00';
}

// Fonction qui liste les priorités
function listePriorites($C, $S = '0'){
    $query = "SELECT * FROM tbl_priorites";
    $res = $C->prepare($query);
    $res->execute();
    
    echo '<select id="c_priorite">';
    echo '<option value="0"></option>';
    while( $ligne = $res->fetch(PDO::FETCH_OBJ) ){ 
       $selected = $S == $ligne->numero ? 'selected=\"selected\"' : '';
        echo '<option '. $selected .' value="'. $ligne->numero .'" title="'. $ligne->remarque .'" >'. $ligne->priorite .'</option>';
    }
    $res->closeCursor();
    echo '</select>';
    echo '<option value="0"></option>';
}

// Fonction qui liste les nb de répertoires
function listeRepertoire($NbR = ''){
    switch ($NbR){
        case '10':
            $first_selected = ' selected="selected"';
            break;
        case '11':
            $sec_selected = ' selected="selected"';
            break;
        case '12':
            $third_selected = ' selected="selected"';
            break;
        case '20':
            $fourth_selected = ' selected="selected"';
            break;
        default:
            $first_selected = '';
            $sec_selected = '';
            $third_selected = '';
            $fourth_selected = '';
            break;
    }
    echo '<select id="c_repertoire">';
    echo '<option value="0"></option>';
    echo '<option value="1"'. $first_selected .'>10</option>';
    echo '<option value="2"'. $sec_selected .'>11</option>';
    echo '<option value="3"'. $third_selected .'>12</option>';
    echo '<option value="4"'. $fourth_selected .'>20</option>';
    echo '</select>';
}

// Fonction qui liste les niveaux

/* Les paramètres $User et $Selectionne servent à définir les données
     * selon SQL pour une facilité de traitement lors des opérations
     * d'ajout/suppression dans la page users */
    
    // $User est le numero d'utilisateur à qui apartient ce niveau
    
    /* $Selectionne est le numero du niveau de l'utilisateur à qui apartient ce niveau.
     * Sert à titre de comparaison lors de la selection d'un autre item que celui-ci */
    
    /* $isAlone est un booléen servant à determiner si la liste sera utilisée et
     * comparée(changement de niveau) : false. Ou alors utilisée seule dans une formulaire (ajout d'utilisateur) : true
     */
function listeNiveaux($Connexion, $Niveau, $User = '0', $Selectionne = '0', $isAlone = true){ 
    $paramUser = '';
    $paramInit = '';
    $name = 'c_niveaux';
    $query = "SELECT * FROM tbl_niveaux";
    $res = $Connexion->prepare($query);
    $res->execute();
    if(!$isAlone){
        $paramUser = ' data-user="'. $User .'"';
        $paramInit = ' data-niveauIni="'. $Selectionne .'"';
        $name = 's_niveaux';
    }
    echo '<select class="c_niveaux input" id="'. $name .'"'. $paramUser . $paramInit .'>';
    if( $Selectionne == 0 ){
        echo '<option value="0"></option>';
    }
    while( $ligne = $res->fetch(PDO::FETCH_OBJ) ){ 
        $selected = $Selectionne == $ligne->numero ? 'selected="selected"' : '';
        // On cache le niveau s'il est super administrateur ou égal à celui de l'utilisateur utilisant l'application présentement
        $disabled = $ligne->numero == 1 || $ligne->numero == $Niveau ? true : false;
        if( !$disabled ) {
            echo '<option '. $selected . $disabled . ' value='. $ligne->numero .'>'. $ligne->niveau .'</option>';
        }
    }
    echo '</select>';
    $res->closeCursor();
}
function listeUsers($Connexion, $Niveau){
    $query = "SELECT * FROM tbl_users";
    $results = $Connexion->prepare($query);
    $results->execute();
    echo '<select id="s_users">';
    echo '<option value="0" disabled selected>Utilisateur</option>';
    while( $ligne = $results->fetch(PDO::FETCH_OBJ) ){
        if($ligne->num_tbl_niveaux > $Niveau){
            $nItem = $ligne->numero;
            $nomComplet = $ligne->nomUtilisateur .' '. $ligne->prenomUtilisateur;
            echo '<option value="'. $nItem .'" >'. $nomComplet .'</option>';
        }
    }
    $results->closeCursor();
}

// Fonction qui liste les titres des boîtes de dialogue de classeur.php
function listeTitres($Connexion, $Indice, $Selectionne = '0'){
    $arrTitres = array();
    $query_titres = "SELECT * FROM tbl_titres";
    $res_titres = $Connexion->prepare($query_titres);
    $res_titres->execute();
    
    echo '<select class="c_titres input" id="c_titre_'. $Indice .'">';
    echo '<option value="0" selected>Titre '. $Indice .'</option>';
    while( $ligne = $res_titres->fetch(PDO::FETCH_OBJ) ){ 
        $selected = $Selectionne == $ligne->numero ? 'selected="selected"' : '';
        $numTitre = $ligne->numero;
        $nomTitre = $ligne->titre;
        echo '<option '. $selected .' value='. $numTitre .'>'. $nomTitre .'</option>';
        
        $arrA = array($numTitre => $nomTitre); // Nouvelle position du tableau
        $arrTitres = arrayMergeKeepKeys($arrA, $arrTitres); // Concatène le tableau avec la nouvelle position
    }
    echo '</select>';
    $res_titres->closeCursor();
    return $arrTitres;
}

// Requête SQL d'ajout d'un classeur. Retourne true si effectuée avec succès
function ajoutClasseur($C, $iUser, $iNbRep, $strClasseur, $iImage = ''){
    // Ajout du classeur
    $paramImage = '';
    $image = '';
    if( $iImage !== ''){
        $paramImage = 'num_tbl_images,';
        $image = $iImage .',';
    }
    $query = "INSERT INTO tbl_classeurs(numero, num_tbl_users, $paramImage nbRepertoire, titreClasseur)"
            . "VALUES(null, :user, $image :nbRep, :titreClasseur)";
    $res = $C->prepare($query);
    // Liaison des variables pour protection de la requête
    $res->bindValue(':user', $iUser, PDO::PARAM_INT);
    $res->bindValue(':nbRep', $iNbRep, PDO::PARAM_INT);
    $res->bindValue(':titreClasseur', $strClasseur, PDO::PARAM_STR);
    $res->execute();
    $res->closeCursor();
    return true;
}

/* Requête SQL d'ajout des titres d'un classeur. Retourne true si 
 * effectuée avec succès */
function ajoutTitres($C, $tabTitres, $iNumClasseur){
    if ($tabTitres !== '') {
        foreach($tabTitres as $chronologie => $numTitre) {
            $intNumTitre = intval($numTitre);
            $intChronologie = intval($chronologie);
            $query_add_t = "INSERT INTO tbl_references_cl(numero, num_tbl_classeurs, num_tbl_titres, chronologie) "
                    . "VALUES(null, :numClasseur, :numTitre, :chronologie)";
            $res_add = $C->prepare($query_add_t);
            // Liaison des variables pour protection de la requête
            $res_add->bindValue(':numClasseur', $iNumClasseur, PDO::PARAM_INT);
            $res_add->bindValue(':numTitre', $intNumTitre, PDO::PARAM_INT);
            $res_add->bindValue(':chronologie', $intChronologie, PDO::PARAM_INT);
            $res_add->execute();
            $res_add->closeCursor();
        }
    }
    return true;
}

function suppressionTitres($C, $iNumClasseur){
    $query_del = "DELETE FROM tbl_references_cl WHERE num_tbl_classeurs = :numClasseur";
    $res_del = $C->prepare($query_del);
    // Liaison des variables pour protection de la requête
    $res_del->bindValue(':numClasseur', $iNumClasseur, PDO::PARAM_INT);
    $res_del->execute();
    $res_del->closeCursor();
    return true;
}

/* ------------ Fonctions relatives au traitement des images --------*/
function existeImage($C, $fileName){
    $query = "SELECT nomImage FROM tbl_images WHERE nomImage = :image";
        $res = $C->prepare($query);
        $res->bindValue(":image", $fileName, PDO::PARAM_STR);
        $res->execute();
        $result = $res->fetch(PDO::FETCH_OBJ);
        if($result != NULL) {
            return true;
        }
        else {
            return false;
        }
}

function existePseudo($Connexion, $Pseudo){
    $query = "SELECT pseudo FROM tbl_users WHERE pseudo = :pseudo";
    $res = $Connexion->prepare($query);
    $res->bindValue(":pseudo", $Pseudo, PDO::PARAM_STR);
    $res->execute();
    $result = $res->fetch(PDO::FETCH_OBJ);
    if($result != NULL) {
        return true;
    }
    else {
        return false;
    }
}
function existeTitre($Connexion, $Titre){
    $query = "SELECT titre FROM tbl_titres WHERE titre = :titre";
    $res = $Connexion->prepare($query);
    $res->bindValue(":titre", $Titre, PDO::PARAM_STR);
    $res->execute();
    $result = $res->fetch(PDO::FETCH_OBJ);
    if($result != NULL) {
        return true;
    }
    else {
        return false;
    }
}

function ajoutImage($C, $fileName){
    list($pseudo, $date, $heureExt) = explode('_', $fileName);
    unset($pseudo);
    list($heure, $extension) = explode('.', $heureExt);
    unset($extension);
    $heureDB = preg_replace('/-/', ':', $heure);
    $datetime = $date .' '. $heureDB;
    // Ajout de l'image
    $query_add = "INSERT INTO tbl_images(numero, dateUploaded, nomImage)"
            . " VALUES(null, :date, :image)";
    $res_add = $C->prepare($query_add);
    // Liaison des variables pour protection de la requête
    $res_add->bindValue(':date', $datetime, PDO::PARAM_STR);
    $res_add->bindValue(':image', $fileName, PDO::PARAM_STR);
    $res_add->execute();
    $res_add->closeCursor();
    return true;
}

function suppressionImage($C, $fileName){
    $query = "DELETE FROM tbl_images WHERE nomImage = :image";
    $res = $C->prepare($query);
    // Liaison des variables pour protection de la requête
    $res->bindValue(':image', $fileName, PDO::PARAM_STR);
    $res->execute();
    $res->closeCursor();
}

/* Remplace ':' par '_' pour le nom de l'image enregistrée */
function fileName(&$Str){
    $Str = preg_replace('/ /', '_', $Str);
    $Str = preg_replace('/[:]/', '-', $Str); /* Remplace le caractère interdit
    * ':'pour les noms de fichiers par un tiret bas '_' */
    return $Str;
}
/* ------------ FIN Fonctions relatives au traitement des images --------*/

/* Fonction qui nettoie les chaînes de caractères */
function cleanString(&$Str, $spaceAutorized = true){
    $Str = trim($Str); // retire les espaces autour de la chaîne
    $Str = trim($Str, "\xA0" ); // supprime les balises '&nbsp' autour de la chaîne
    $Str = preg_replace('/ ?- ?/', '-', $Str);
    $Str = preg_replace('/-{2,}/', '-', $Str);
    $Str = trim($Str, "-" );
    $Str = preg_replace('#<[^>]+>#','',$Str); // retire les balises html
    $Str = preg_replace('/[\$\/*\#`\^\<\>\=\(\)\|\[\]\{\}\:\.\,\?\+\%]/', '', $Str);
    /* retire les caractères spéciaux suivants : $, /, %, ', ", `, ^, =, (, ), |, [, ], {, } 
     */
    while(strchr($Str,'\\')) {
        $Str = stripslashes($Str);
    }
    $Str = preg_replace('/\s\s+/', ' ', $Str); // retire les espaces en trop
    return $Str;
}

/* Fonction équivalente à array_merge mais qui laisse les clés intactes
 * array_merge fusionne plusieurs tableaux ensemble mais supprime les clés présentes
 * dans le premier tableau et met des clés par numéro croissant
 * 
 * Récupérée sur http://php.net/manual/fr/function.array-merge.php
 * Commentaire de Frederick.Lemasson{AT}kik-it.com
 */
function arrayMergeKeepKeys() {
      $arg_list = func_get_args();
      foreach((array)$arg_list as $arg){
          foreach((array)$arg as $K => $V){
              $Zoo[$K]=$V;
          }
      }
    return $Zoo;
}