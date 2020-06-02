<?php
if(session_id() == "") {
    session_start();
    $user = $_SESSION['user'];
    $page = $_SESSION['page'];
    $niveau = $_SESSION['niveau'];
    require_once('../calls/pdo.php');
    require_once('../calls/functions.php');
    $suffPath = '../';
} else{
    $suffPath = '';
}
if( isset($_POST['page']) ){
    $page = $_POST['page'];
}
$aujourdhui = date('Y-m-d');
$chemin = 'images/classeurs/';

switch ($page){
    case 'taches':
        $query = "SELECT tbl_priorites.priorite, tbl_taches.tache, tbl_taches.dateDebut, tbl_taches.dateFin, tbl_taches.numero, tbl_taches.remarque
                FROM tbl_users INNER JOIN (tbl_priorites INNER JOIN tbl_taches ON tbl_priorites.numero = tbl_taches.num_tbl_priorites) ON tbl_users.numero = tbl_taches.num_tbl_users
                    WHERE tbl_taches.num_tbl_users=$user
                        ORDER BY tbl_priorites.priorite;";
        $results = $connexion->prepare($query);
        $results->execute();
        $count = $results->rowCount();
        echo '<tbody>';
        while( $ligne = $results->fetch(PDO::FETCH_OBJ) ){
            $dfin = $ligne->dateFin;
            $remarque = $ligne->remarque !== '' ? ' title="'. $ligne->remarque .'"' : '';
            $terminee = $dfin !== '0000-00-00' && $dfin <= $aujourdhui ? ' class="finished hidden"' : ' class="ongoing"';
            $fini = $ligne->dateFin == '0000-00-00' ? '<a class="finir" href="#" data-id="'. $ligne->numero .'">Terminer</a>': '';
            echo '<tr'. $terminee .'>';
                echo '<td>'. $ligne->priorite .'</td>';
                echo '<td'. $remarque .'>'. $ligne->tache .'</td>';
                echo '<td>'. afficheDate($ligne->dateDebut) .'</td>';
                echo '<td>'. afficheDate($ligne->dateFin) .'</td>';
                echo '<td class="actions">'. $fini .'<a class="edit" href="#" data-id="'. $ligne->numero .'">Modifier</a><a class="del" href="#" data-id="'. $ligne->numero .'">Supprimer</a></td>';
            echo '</tr>';
        }
        if( $count=== 0 ){ // Si le résultat de la requête est vide, la valeur FALSE est retournée
            echo '<tr>';
                echo '<td colspan=5 class="empty">Aucune tâche pour l\'instant.</td>';
            echo '</tr>';
        }
        $results->closeCursor();
        echo '</tbody>';
        break;
    case 'classeurs':
        $query = "SELECT tbl_classeurs.numero AS numClasseur, tbl_classeurs.titreClasseur ,tbl_classeurs.nbRepertoire, tbl_images.nomImage, tbl_users.numero
                    FROM tbl_users INNER JOIN (tbl_images RIGHT JOIN tbl_classeurs ON tbl_images.numero = tbl_classeurs.num_tbl_images) ON tbl_users.numero = tbl_classeurs.num_tbl_users
                        WHERE (((tbl_users.numero)=$user));";
        $results = $connexion->prepare($query);
        $results->execute();
        $count = $results->rowCount();
        echo '<tbody>';
        while( $ligne = $results->fetch(PDO::FETCH_OBJ) ){
            $nbRep = $ligne->nbRepertoire;
            $numClasseur = $ligne->numClasseur;
            $titreClasseur = $ligne->titreClasseur;
            $image = $ligne->nomImage;
            $imageHTML = $image !== NULL ? '<img src="'. $chemin.$image .'"/>': '';
            echo '<tr>';
                echo '<td>'. $titreClasseur .'</td>';
                echo '<td>'. $nbRep .'</td>';
                echo '<td>'. $imageHTML .'</td>';
                echo '<td class="actions"><a class="edit" href="#" data-id="'. $numClasseur .'" data-image="'. $image .'">Modifier</a><a href="#" data-id="'. $numClasseur .'" class="imprint" data-image="'. $image .'">Imprimer</a><a class="del" href="#" data-id="'. $numClasseur .'" data-image="'. $image .'">Supprimer</a></td>';
            echo '</tr>';
        }
        if( $count=== 0 ){ // Si le résultat de la requête est vide, la valeur FALSE est retournée
            echo '<tr>';
                echo '<td colspan=5>Aucun classeur pour l\'instant.</td>';
            echo '</tr>';
        }
        $results->closeCursor();
        echo '</tbody>';
        break;
    case 'images':
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
            echo '<td class="cases">';
                echo '<span><img src="'. $srcImage .'" alt="'. $ligne->nomImage .'"/></span>';
                echo '<span><a data-id="'. $numero .'" href="#" class="del">Supprimer l\'image</a></span>';
            echo '</td>';
            if( $init % 4 == 0 ){
                echo '</tr>';
                if( $init !== $count ){
                    echo '<tr>';
                }
            }
            $init++;
        }
        if( $count=== 0 ){ // Si le résultat de la requête est vide, la valeur FALSE est retournée
            echo '<tr>';
                echo '<td colspan=5>Il n\'a aucune image sur le serveur pour l\'instant.</td>';
            echo '</tr>';
        }
        $results->closeCursor();
        echo '</tbody>';
    break;
    case 'users':
        echo '<tbody>';
            echo '<tr>';
                echo '<td>';
                listeUsers($connexion, $niveau);
                    echo '</select>';
                echo '</td>';
                echo '<td>';
                    include_once($suffPath . 'includes/niveauxUsers.php');
                echo '</td>';
                echo '<td>';
                    echo '<input type="button" class="bouton btnDisabled save_user hidden" value="Enregistrer" />';
                    echo '<input type="button" class="bouton btnDisabled del_user" value="Supprimer" />';
                echo '</td>';
            echo '</tr>';
        echo '</tbody>';
        break;
}