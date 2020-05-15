<?php
session_start();
require_once ('calls/functions.php');
require_once('connexionPDO.php');
$nom = $_SESSION['nomUtilisateur'];
$prenom = $_SESSION['prenomUtilisateur'];
$user = isset( $_SESSION['user'] ) ? $_SESSION['user'] : header('Location: index.php');
$_SESSION['page'] = 'users';
$page = $_SESSION['page'];

$menu_utilisateurs = '';
$mode = '<b>utilisateur</b>';
$messageLogin = "<div style=\"text-align:right\" id=\"bienvenue\"><span style=\"text-align:right\">Vous êtes connecté en tant qu' $mode</span></div>"; 
//Vérification du niveau de l'utilisateur authentifié
if ( isset($_SESSION['niveau']) ) {
    $niveau = $_SESSION['niveau'];
    if ( $niveau == 1 || $niveau == 2 ) {
        $menu_utilisateurs = '<li><a class="liens active" href="#">Utilisateurs</a></li>';
        $menu_images = '<li liens ><a class="liens" href="images.php">Gestionnaire d\'images</a></li>';
        if($niveau == 1) {
            $mode = '<b>Super Administrateur</b>';
            $messageLogin = "<div style=\"text-align:right\" id=\"bienvenue\"><span>Vous êtes connecté en tant que $mode</span></div>";
        }
        if($niveau == 2){
            $mode = '<b>Administrateur</b>';
            $messageLogin = "<div style=\"text-align:right\" id=\"bienvenue\"><span>Vous êtes connecté en tant qu' $mode</span></div>"; 
        }       
    }
}
?>
<!DOCTYPE html> 
<!-- Le DOCTYPE HTML5 est utilisé pour cette application, certaines balises comme <nav> 
     et <section> n'est valide qu'avec ce DOCTYPE. Liste complète
     des éléments valides à la page http://www.w3schools.com/tags/ref_html_dtd.asp

     Ce document a été validé en retirant préalablement les balises php du document. -->
<html lang="fr">
    <head>
            <meta name="viewport" content="width=device-width, user-scalable=no">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Avalez le crapaud - Gestion des utilisatieurs</title>
            <link rel="stylesheet" type="text/css" media="all" href="./css/responsive.css" />
            <link rel="stylesheet" type="text/css" media="all" href="./css/polices/polices.css" />
			<link rel="icon" href="images/favicon.ico" />
            
            <script type="text/javascript" src="./js/lib/jquery-2.1.1.min.js"></script>
            <script type="text/javascript" src="./js/functions.js"></script>
            <script type="text/javascript" src="./js/events.js"></script>
            
            <!-- jQuery UI -->
            <script type="text/javascript" src="./js/lib/jquery-ui.min.js"></script>
            <link rel="stylesheet" type="text/css" media="all" href="./css/jquery-ui.css" />
    </head>
    <body>
        <div id="menu_wrapper">
            <nav id="menu">
                <div id="menu_toggle">
                    <img src="./images/menu_toggle.png" alt="Toggle"/>
                </div>
                <ul class="fleft">
                    <li><a class="liens" href="taches.php">Tâches</a></li>
                    <li><a class="liens" href="classeurs.php">Classeurs</a></li>
                    <li><a class="liens" href="titres.php">Titres</a></li>
                    <?php echo $menu_images; ?>
                    <?php echo $menu_utilisateurs; ?>
                </ul>
                <ul class="fright">
                    <li><a class="liens" href="logout.php">Déconnexion</a></li>
                </ul>
            </nav>
        </div>
        <div id="contenu">
            <section id="Users" class="sections sections1">
                <div class="panels first">
                    <?php echo $messageLogin; ?>
                    <h1>Gestion des utilisateurs</h1>
                    <table class="tableaux">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Niveau</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                    <?php
                        include_once('includes/tableau.php');
                    ?>
                    </table>
                    <div id="add"><span class="add" title="Ajouter un nouvel utilisateur">+</span><span id="messageUser"></span></div>
                    
                </div>
            </section>
        </div>
        <?php
            require_once('includes/pied.php');
        ?>
    </body>
</html> 
