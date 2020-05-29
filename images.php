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
            <script src="./js/plugins/jquery.uploadfile.min.js"></script>
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
            <section id="Classeurs" class="sections sections1">
                <div class="panels first">
                    <?php echo $messageLogin; ?>
                    <table class="tables">
                        <thead>
                            <tr>
                                <th colspan="4">Bibliothèque d'images</th>
                            </tr>
                        </thead>
                    <?php
                        include_once('includes/tableau.php');
                    ?>
                    </table>
                </div>
            </section>
        </div>
        <?php
            require_once('includes/pied.php');
        ?>
    </body>
</html>
