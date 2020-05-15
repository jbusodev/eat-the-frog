<?php
session_start();
require_once ('calls/functions.php');
require_once('./connexionPDO.php');
$nom = isset( $_SESSION['nomUtilisateur'] ) ? $_SESSION['nomUtilisateur'] : '';
$prenom = isset( $_SESSION['prenomUtilisateur'] ) ? $_SESSION['prenomUtilisateur'] : 'invite';
$user = isset( $_SESSION['user'] ) ? $_SESSION['user'] : header('Location: index.php');
$_SESSION['page'] = 'taches';
$page = $_SESSION['page'];

$menu_utilisateurs = '';
$menu_images = '';
$mode = '<b>utilisateur</b>';
$messageLogin = "<div style=\"text-align:right\" id=\"bienvenue\"><span style=\"text-align:right\">Vous êtes connecté en tant qu' $mode</span></div>"; 
// Verification of Authenticated user level 
if ( isset($_SESSION['niveau']) ) {
    $niveau = $_SESSION['niveau'];
    if ( $niveau == 1 || $niveau == 2 ) {
        $menu_utilisateurs = '<li liens ><a class="liens" href="users.php">Utilisateurs</a></li>';
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
        <title>Avalez le crapaud - Tâches</title>
        <link rel="stylesheet" type="text/css" media="screen" href="./css/responsive.css" />
        <link rel="stylesheet" type="text/css" media="all" href="./css/polices/polices.css" />
        <link rel="stylesheet" type="text/css" media="print" href="./css/print.css" />
        <link rel="icon" href="images/favicon.ico" />

        <!-- jQuery -->
        <script type="text/javascript" src="./js/lib/jquery-2.1.1.min.js"></script>
        
        
        <!-- Custom Functions + Events -->
        <script type="text/javascript" src="./js/functions.js"></script>
        <script type="text/javascript" src="./js/events.js"></script>

        <!-- jQuery UI -->
        <script type="text/javascript" src="./js/lib/jquery-ui.min.js"></script>
        <link rel="stylesheet" type="text/css" media="all" href="./css/jquery-ui.css" />

        <!-- Plugin JS PrinthThis impression -->
        <script type="text/javascript" src="js/plugins/jQuery.print.js"></script>
    </head>
    <body>
        <div id="pageTaches">
            <div class="print"><span>Application WEB : Avalez le crapaud</span><span>Utilisateur : <?php echo $prenom .' '. $nom; ?></span></div>
            <div id="menu_wrapper">
                <nav id="menu">
                    <div id="menu_toggle">
                        <img src="./images/menu_toggle.png" alt="Toggle"/>
                    </div>
                    <ul class="fleft">
                        <li><a class="liens active" href="taches.php">Tâches</a></li>
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
                <section id="Taches" class="sections sections1">
                    <div class="panels first">
                        <?php echo $messageLogin; ?>
                        <div class="right"><span class="imprint">imprimer</span></div>
                        <h1>Liste des tâches</h1>
                        
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="toggle_terminees" name="example1">
                            <label class="custom-control-label" for="toggle_terminees">Afficher les tâches terminées</label>
                        </div>

                        <div id="cacher" class="hidden custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="cacher_encours" name="example1">
                            <label class="custom-control-label" for="cacher_encours">Afficher les tâches terminées</label>
                        </div>
                        <!--
                            // initial checkbox - finished tasks
                            <div>
                                <input type="checkbox" id="toggle_terminees"/>
                                <span for="toggle_terminees">Afficher les tâches terminées</span>
                            </div>
                            // initial checkbox - hide ongoing tasks
                            <div id="cacher" class="hidden">    
                                <input type="checkbox" id="cacher_encours"/>
                                <span for="cacher_encours">Cacher les tâches en cours</span>
                            </div>
                        -->
                        <table class="tableaux">
                            <thead>
                                <tr>
                                    <th>Priorité</th>
                                    <th>Tâche</th>
                                    <th>Date début</th><th>Date de fin</th>
                                    <th class="actions">Actions</th>
                                </tr>
                            </thead>
                        <?php
                            include_once('includes/tableau.php');
                        ?>
                        </table>
                        <!--
                        <div id="add">
                            <span class="add" title="Ajouter une nouvelle tâche">+</span>
                        </div>
                        -->

                        <svg id="add" class="bi bi-plus-square-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path class="add" title="Ajouter une nouvelle tâche" fill-rule="evenodd" d="M2 0a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V2a2 2 0 00-2-2H2zm6.5 4a.5.5 0 00-1 0v3.5H4a.5.5 0 000 1h3.5V12a.5.5 0 001 0V8.5H12a.5.5 0 000-1H8.5V4z" clip-rule="evenodd"/>
                        </svg>
                    </div>

                </section>
            </div>
            <?php
                require_once('includes/pied.php');
            ?>
        </div>
    </body>
</html> 
