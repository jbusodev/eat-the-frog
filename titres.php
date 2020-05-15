<?php
session_start();
require_once ('calls/functions.php');
require_once('./connexionPDO.php');
$nom = isset( $_SESSION['nomUtilisateur'] ) ? $_SESSION['nomUtilisateur'] : '';
$prenom = isset( $_SESSION['prenomUtilisateur'] ) ? $_SESSION['prenomUtilisateur'] : 'invite';
$user = isset( $_SESSION['user'] ) ? $_SESSION['user'] : header('Location: index.php');
$_SESSION['page'] = 'titres';
$page = $_SESSION['page'];

$menu_utilisateurs = '';
$menu_images = '';
$mode = '<b>utilisateur</b>';
$messageLogin = "<div style=\"text-align:right\" id=\"bienvenue\"><span style=\"text-align:right\">Vous êtes connecté en tant qu' $mode</span></div>"; 
//Vérification du niveau de l'utilisateur authentifié
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
        <title>Avalez le crapaud - Titres</title>
        <link rel="stylesheet" type="text/css" media="screen" href="./css/responsive.css" />
        <link rel="stylesheet" type="text/css" media="all" href="./css/polices/polices.css" />
        <link rel="stylesheet" type="text/css" media="print" href="./css/print.css" />
        <link rel="icon" href="images/favicon.ico" />

        <script type="text/javascript" src="./js/lib/jquery-2.1.1.min.js"></script>
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
                        <li><a class="liens" href="taches.php">Tâches</a></li>
                        <li><a class="liens" href="classeurs.php">Classeurs</a></li>
                        <li><a class="liens active" href="d_titres.php">Titres</a></li>
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
                        <h1>Liste des Titres</h1>
                        <p id="advertise" class="rouge">Vous devez choisir un titre pour activer les boutons</p>
                        <label>Titre :</label>
                        <?php
                            include_once('includes/titres.php');
                        ?>
                        <input type="text" id="c_edit_titre" placeholder="Entrez le nouveau titre ici"/>
                        <input id="edit_titre" class="bouton btnDisabled" type="button" value="Modifier"/>
                        <input id="save_titre" class="bouton btnDisabled" type="button" value="Enregistrer"/>
                        <span id="messageEdit"></span>
                        <div id="add_titre"><a>Ajouter un titre</a></div>
                        <br/>
                        <input type="text" id="c_add_titre" placeholder="Entrez le nouveau titre ici"/>
                        <input type="button" id="save_add_titre" class="bouton btnDisabled" value="Enregistrer"/>
                        <span id="messageAdd"></span>
                        <input id="page" type="hidden" value="titres"/>
                    </div>

                </section>
            </div>
            <?php
                require_once('includes/pied.php');
            ?>
        </div>
    </body>
</html> 
