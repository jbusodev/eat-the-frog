<?php
session_start();
require_once('../calls/pdo.php');
require_once('../calls/functions.php');

// Variables générales
unset($_SESSION['fileName']);
unset($_SESSION['iImage']);
unset($_SESSION['titres']);
$user = $_SESSION['user'];
$niveau = $_SESSION['niveau'];
$pseudo = isset( $_SESSION['pseudo']) ? $_SESSION['pseudo'] : '';
$page = isset( $_SESSION['page'] ) ? $_SESSION['page'] : '';

// Feature database ID (task | folder | title | user)
$nItem = isset( $_POST['nItem'] ) ? $_POST['nItem'] : NULL;
$andt = $nItem!=='' ? 'AND tbl_'. $page .'.numero=' : '';
$delete = '';

// Variables relatives à la tache
$nomTache = '';
$prioTache = '';
$dateDTache = '';
$dateFTache = '';
$remTache = '';

// Variables relatives au classeur
$nbRepertoire = '';
$titreClasseur = '';
$nImage = '';
$noImageChecked = '';

if( isset($_POST['action']) ){
    $action = $_POST['action'];
    switch($page){
        case 'taches':
            switch($action){
                case 'end':
                    $title = 'Terminer une tâche';
                    break;
                case 'add':
                    $title = 'Ajouter une tâche';
                    break;
                case 'edit':
                    $_SESSION['fileName'] = '';
                    $title = 'Modifier une tâche';
                    $query_edit = "SELECT tbl_priorites.priorite, tbl_taches.tache, tbl_taches.dateDebut, tbl_taches.dateFin, tbl_taches.numero, tbl_taches.num_tbl_priorites, tbl_taches.remarque
                        FROM tbl_users INNER JOIN (tbl_priorites INNER JOIN tbl_taches ON tbl_priorites.numero = tbl_taches.num_tbl_priorites) ON tbl_users.numero = tbl_taches.num_tbl_users
                            WHERE tbl_taches.num_tbl_users=$user $andt $nItem";
                    $res_edit = $connexion->prepare($query_edit);
                    $res_edit->execute();
                    $obj_tache = $res_edit->fetch(PDO::FETCH_OBJ);
                    $nomTache = $obj_tache->tache;
                    $prioTache = $obj_tache->num_tbl_priorites;
                    $dateDTache = $obj_tache->dateDebut;
                    $dateFTache = $obj_tache->dateFin;
                    $remTache = $obj_tache->remarque;
                    $res_edit->closeCursor();
                    break;
                case 'delete':
                    $title = 'Supprimer une tâche';
                    $delete = 'Êtes-vous sûr de vouloir supprimer cette tâche ?';
                    break;
                case 'imprint':
                    break;
            }
            $dateDTache = $dateDTache !=='' ? afficheDate($dateDTache) : date('d-m-Y');
            if ( $dateFTache !=='' && $dateFTache !=='0000-00-00' ){
                $dateFTache = afficheDate($dateFTache);
                $isDateF = 'true';
            } else {
                $dateFTache = date('d-m-Y');
                $isDateF = 'false';
            }
            echo '<div class="dialogs" id="dialog" title="'. $title .'">';
                echo '<div class="champs"><div id="message">'. $delete .'</div></div>';
                echo '<div class="champs c_caches">';
                    echo '<label class="required">Tâche : </label><input type="text" id="c_tache" value="'. $nomTache .'"/>';
                echo '</div>';
                echo '<div class="champs c_caches">';
                    echo '<label class="required">Priorité : </label>';
                    listePriorites($connexion, $prioTache);
                echo '</div>';
                echo '<div class="champs c_caches">';
                    echo '<label class="required">Date de début : </label><input type="text" class="dates" id="c_dateD" value="'. $dateDTache .'" readonly="readonly"/>';
                echo '</div>';
                echo '<div class="champs c_caches">';
                    echo '<input type="checkbox" id="toggle_dateFyes"/><label class="inline-block" for="toggle_dateFyes">Terminée</label>';
                echo '</div>';
                echo '<div class="champs" id="hidden_dates"><label>Date de fin : </label><input type="text" class="dates" id="c_dateF" value="'. $dateFTache .'" readonly="readonly"/></div>';
                echo '<div class="champs c_caches"><label>Remarque : </label><textarea id="c_remarque" class="margin_centered" maxlength="120">'. $remTache .'</textarea></div>';
                echo '<input id="page" type="hidden" value="'. $page .'" />';
                echo '<input id="isDateF" type="hidden" value="'. $isDateF .'" />';
            echo '</div>';

        break;
        case 'classeurs':
            // Requête pseudo utilisateur
            $query_pseudo = "SELECT * FROM tbl_users WHERE numero=$user";
            $res_pseudo = $connexion->prepare($query_pseudo);
            $res_pseudo->execute();
            $obj_user = $res_pseudo->fetch(PDO::FETCH_OBJ);
            $pseudo = $obj_user->pseudo;
            $res_pseudo->closeCursor();

            switch($action){
                case 'add':
                    $title = 'Ajouter un classeur';
                    break;
                case 'edit':
                    $title = 'Modifier un classeur';
                    $query_edit = "SELECT * FROM tbl_classeurs WHERE num_tbl_users=$user $andt $nItem";
                    $res_edit = $connexion->prepare($query_edit);
                    $res_edit->execute();
                    $obj_classeur = $res_edit->fetch(PDO::FETCH_OBJ);

                    // Gets the folder properties
                    $titreClasseur = $obj_classeur->titreClasseur;
                    $nbRepertoire = $obj_classeur->nbRepertoire;
                    $nImage = $obj_classeur->num_tbl_images;

                    /**
                     * If the folder is not associated with an image,
                     * sets a variable to check the corresponding option
                     * in the dialog
                     */
                    $noImageChecked = $nImage == NULL ? 'checked' : '';

                    $res_edit->closeCursor();
                    break;
                case 'delete':
                    $title = 'Supprimer un classeur';
                    $delete = 'Êtes-vous sûr de vouloir supprimer ce classeur ?';
                    break;
                case 'print':
                    $title = 'Aperçu avant impression';
                    $query_edit = "SELECT * FROM tbl_classeurs WHERE num_tbl_users=$user $andt $nItem";
                    $res_edit = $connexion->prepare($query_edit);
                    $res_edit->execute();
                    $obj_classeur = $res_edit->fetch(PDO::FETCH_OBJ);
                    $titreClasseur = $obj_classeur->titreClasseur;
                    $nbRepertoire = $obj_classeur->nbRepertoire;
                    $nImage = $obj_classeur->num_tbl_images;
                    $res_edit->closeCursor();
                    break;
            }

            if( $action !== 'print' ){
                echo '<div class="dialogs" id="dialog" title="'. $title .'">';
                /*
                if( $action !== 'add'){
                    echo '<pre>';
                        var_dump($obj_classeur);
                    echo '</pre>';
                }
                */
                    echo '<div class="champs"><div id="message">'. $delete .'</div></div>';
                    echo '<div class="champs c_caches">';
                        echo '<label class="required">Titre du classeur : </label><input type="text" id="c_titreClasseur" value="'. $titreClasseur .'"/>';
                    echo '</div>';
                    echo '<div class="champs c_caches">';
                        echo '<label class="required">Nombre de répertoires : </label>';
                        echo '<a href="#" class="info margin-bottom">Si vous choisissez un nombre plus petit que le précédent, les titres en surplus seront tronqués(supprimés)</a>';
                        listeRepertoire($nbRepertoire);
                    echo '</div>';
                    echo '<div class="champs c_caches">';
                        echo '<label>Titres du répertoire</label><a href="#" id="toggle_titles">(Afficher)</a>';
                    echo '</div>';
                    if ( $action !== 'delete' ){
                    echo '<div class="champs" id="hidden_titles">';
                        include_once('d_titres.php');
                    echo '</div>';
                    }
                    echo '<a href="titres.php" style="font-size:10pt;border:none" class="extlink c_caches" title="Vous serez redirigé vers la page gestion des titres">Ajouter des titres</a>';
                    echo '<div class="champs c_caches ou center">';
                        echo '<input id="isImage" type="checkbox" '. $noImageChecked .'/><label for="isImage">Aucune image</label>';
                    echo '</div>';
                    echo '<div class="champs c_caches noImage ou" style="text-align:center;font-weight:bold">';
                        echo 'OU';
                    echo '</div>';
                    echo '<div class="champs noImage center">';
                        echo '<a id="choisirImage" href="#" class="c_caches">Choisir une image existante</a>';
                    echo '</div>';
                    echo '<div class="champs" id="hidden_images">';
                        echo '<table class="tables">';
                        include_once('../includes/d_images.php');
                        echo '</table>';
                    echo '</div>';

                    // uncomment once heroku file upload is functional
                    /*echo '<div class="champs c_caches noImage ou" style="text-align:center;font-weight:bold">';
                        echo 'OU';
                    echo '</div>';
                    if($action !== 'delete'){

                        echo '<div id="boxUpload">';
                            echo '<div class="champs c_caches" id="picUpload">Télécharger</div>';
                            echo '<div id="status" class="little-font-size"></div>';
                        echo '</div>';
                    }
                    */
                    echo '<input id="item" type="hidden" value="'. $nItem .'" />';
                    echo '<input id="pseudo" type="hidden" value="'. $pseudo .'" />';
                    echo '<input id="image" type="hidden" value="'. imageSelected($connexion, $nImage) .'" />';
                    echo '<input id="page" type="hidden" value="'. $page .'" />';

                echo '</div>';
            } else {
                echo '<div class="dialogs" id="dialog" title="'. $title .'">';
                        echo '<div class="champs c_caches">';
                            echo '<label class="required">Titre du classeur : </label><input class="input" type="text" id="c_titreClasseur" value="'. $titreClasseur .'"/>';
                        echo '</div>';
                        echo '<div class="champs c_caches">';
                            echo '<label class="required">Nombre de répertoires : </label>';
                            echo '<input id="f_directory" class="input" type="text" value="'. $nbRepertoire .'"/>';
                        echo '</div>';
                        echo '<div class="champs c_caches">';
                            echo '<label>Titres</label>';
                        echo '</div>';
                        echo '<div class="champs">';
                            include_once('../includes/d_titres.php');
                        echo '</div>';
                        echo '<div class="champs"><div id="message">'. $delete .'</div></div>';
                        echo '<input id="item" type="hidden" value="'. $nItem .'" />';
                        echo '<input id="pseudo" type="hidden" value="'. $pseudo .'" />';
                        echo '<input id="image" type="hidden" value="" />';
                        echo '<input id="page" type="hidden" value="'. $page .'" />';
                echo '</div>';
            }
            break;
        case 'users':
            unset($nomTache, $prioTache, $dateDTache, $dateFTache, $remTache, $nbRepertoire, $titreClasseur);
            switch($action){
                case 'add':
                    $title = 'Ajouter un utilisateur';
                    break;
                case 'delete':
                    $title = 'Supprimer un utilisateur';
                    $delete = '<span class="rouge">Attention ! </span>Toutes les tâches ainsi que tous les classeurs associés à cet utilisateur seront supprimés !<br/><br/>Êtes-vous sûr de vouloir supprimer cet utilisateur ? ';
                    break;
                default:
                    $title = 'Erreur PHP';
                    break;
            }
            echo '<div class="dialogs" id="dialog" title="'. $title .'">';
                echo '<div class="champs"><div id="message" class="error">'. $delete .'</div></div>';
                echo '<p class="c_caches">Les champs marqués d\'une * sont obligatoires</p>';
                echo '<div class="champs c_caches">';
                    echo '<label class="required">Nom : </label><input type="text" id="c_nom"/>';
                echo '</div>';
                echo '<div class="champs c_caches">';
                    echo '<label class="required">Prénom : </label><input type="text" id="c_prenom"/>';
                echo '</div>';
                echo '<div class="champs c_caches">';
                    echo '<label class="required">Nom d\'utilisateur : </label><input type="text" id="c_pseudo"/>';
                echo '</div>';
                echo '<div class="champs c_caches">';
                    echo '<label class="required">Mot de passe : </label><input type="password" id="c_mdp"/>';
                echo '</div>';
                echo '<div class="champs c_caches">';
                    echo '<label class="required">Répétez le mot de passe : </label><input type="password" id="c_mdp2"/>';
                echo '</div>';
                echo '<div class="champs c_caches">';
                    echo '<label class="required">Niveau : </label>';
                    listeNiveaux($connexion, $niveau, 0, 1);
                echo '</div>';
                echo '<input id="item" type="hidden" value="'. $nItem .'" />';
                echo '<input id="page" type="hidden" value="'. $page .'" />';
            echo '</div>';
            break;
        case 'images':
            unset($nomTache, $prioTache, $dateDTache, $dateFTache, $remTache, $nbRepertoire, $titreClasseur, $user, $niveau, $pseudo, $andt);
            switch($action){
                case 'delete':
                    $title = 'Supprimer une image du serveur';
                    $delete = '<span class="rouge">Attention ! </span>vous êtes sur le point de supprimer l\'image du serveur.<br/><br/>En êtes-vous certain ?';
                    break;
                default:
                    $title = 'Erreur PHP';
                    break;
            }

            echo '<div class="dialogs" id="dialog" title="'. $title .'">';
                echo '<div class="champs"><div id="message">'. $delete .'</div></div>';
                echo '<input id="item" type="hidden" value="'. $nItem .'" />';
                echo '<input id="page" type="hidden" value="'. $page .'" />';
            echo '</div>';

            break;
        default:
            break;
    }
}