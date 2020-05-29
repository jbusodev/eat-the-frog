<?php
    require_once('includes/header.php');
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
            <title>Avalez le crapaud - Classeurs</title>
            <link rel="stylesheet" type="text/css" media="all" href="./css/responsive.css" />
            <link rel="stylesheet" type="text/css" media="all" href="./css/polices/polices.css" />
            <link rel="icon" href="images/favicon.ico" />

            <script type="text/javascript" src="./js/lib/jquery-2.1.1.min.js"></script>
            <script type="text/javascript" src="./js/functions.js"></script>
            <script type="text/javascript" src="./js/events.js"></script>

            <!-- jQuery UI -->
            <script type="text/javascript" src="./js/lib/jquery-ui.min.js"></script>
            <link rel="stylesheet" type="text/css" media="all" href="./css/jquery-ui.css" />

        <!-- -------------------------- Plugins --------------------- -->
            <!-- Plugin JS jQuery-Upload-File permettant l'upload d'image
                 trouvé sur http://hayageek.com/docs/jquery-upload-file.php -->
            <script src="./js/plugins/jquery.uploadfile.js"></script>
            <link rel="stylesheet" type="text/css" media="all" href="./css/uploadfile.css" />

            <!-- Plugin JS jQuery.print permettant d'imprimer une partie de page
                 trouvé sur https://github.com/DoersGuild/jQuery.print -->
            <script src="./js/plugins/jQuery.print.js"></script>
        <!-- -------------------------- END Plugins --------------------- -->

    </head>
    <body>
        <div class="print"><span>Application WEB : Avalez le crapaud</span><span>Utilisateur : <?php echo $prenom .' '. $nom; ?></span></div>
        <div id="menu_wrapper">
            <nav id="menu">
                <div id="menu_toggle">
                    <img src="./images/menu_toggle.png" alt="Toggle"/>
                </div>
                <ul class="fleft">
                    <li><a class="liens" href="taches.php">Tâches</a></li>
                    <li><a class="liens active" href="classeurs.php">Classeurs</a></li>
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
            <section id="Classeurs" class="sections sections1">
                <div class="panels first">
                    <?php echo $messageLogin; ?>
                    <h1>Gestion des classeurs</h1>
                    <table class="tables">
                        <thead>
                            <tr>
                                <th>Titre du classeur</th>
                                <th>Répertoires</th>
                                <th>Image</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                    <?php
                        include_once('includes/tableau.php');
                    ?>
                    </table>
                    <!--
                    <div id="add">
                        <span class="add" title="Ajouter un nouveau classeur">+</span>
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
    </body>
</html>
