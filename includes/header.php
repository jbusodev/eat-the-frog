<?php
   session_start();
   require_once ('calls/functions.php');
   require_once('./calls/pdo.php');
   $nom = isset( $_SESSION['nomUtilisateur'] ) ? $_SESSION['nomUtilisateur'] : '';
   $prenom = isset( $_SESSION['prenomUtilisateur'] ) ? $_SESSION['prenomUtilisateur'] : 'invite';
   $user = isset( $_SESSION['user'] ) ? $_SESSION['user'] : header('Location: index.php');

   $url = $_SERVER['REQUEST_URI'];
   getCurrentPage($url); // cleans url to get current page

   $_SESSION['page'] = $url;
   $page = $_SESSION['page'];

   $menu_arr_extra = array();
   $mode = '<b>utilisateur</b>';
   $messageLogin = '<div style="text-align:right" id="bienvenue"><span style="text-align:right">Vous êtes connecté en tant qu\''. $mode .'</span></div>';
   // Verification of Authenticated user level
      if ( isset($_SESSION['niveau']) ) {
         $niveau = $_SESSION['niveau'];
         if ( $niveau == 1 || $niveau == 2 ) {
            $menu_arr_extra = array (
               3 => array(
                  "data" => "images",
                  "href" => "images.php",
                  "value" => "Gestionnaire d'images" // could later be replaced by LANG corresponding value
                  ),
               4 => array(
                  "data" => "users",
                  "href" => "users.php",
                  "value" => "Utilisateurs" // could later be replaced by LANG corresponding value
                  )
               );
            if($niveau == 1) {
               $mode = '<b>super Administrateur</b>';
               $messageLogin = '<div style="text-align:right" id="bienvenue"><span>Vous êtes connecté en tant que '. $mode.'</span></div>';
            }
            if($niveau == 2){
               $mode = '<b>administrateur</b>';
               $messageLogin = '<div style="text-align:right" id="bienvenue"><span>Vous êtes connecté en tant qu\''. $mode .'</span></div>';
            }
         }
      }
   //

   // Format title - for implementation when internationalization
   $title=ucfirst($page);
?>
<!DOCTYPE html>
<html lang="fr">
   <head>
      <meta name="viewport" content="width=device-width, user-scalable=no">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title>Avalez le crapaud - <?php echo $title; ?></title>
      <link rel="stylesheet" type="text/css" media="all" href="./css/responsive.css" />
      <link rel="stylesheet" type="text/css" media="all" href="./css/polices/polices.css" />
      <link rel="icon" href="images/favicon.ico" />

      <!-- jQuery -->
      <!--
      <script type="text/javascript" src="./js/lib/jquery-2.1.1.min.js"></script>
      -->
      <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
      <!-- jQuery UI -->
      <script type="text/javascript" src="./js/lib/jquery-ui.min.js"></script>
      <script type="text/javascript" src="./js/functions.js"></script>
      <script type="text/javascript" src="./js/events.js"></script>


      <link rel="stylesheet" type="text/css" media="all" href="./css/jquery-ui.css" />

      <!---------------------------- Plugins --------------------- -->
         <!-- Plugin JS jQuery-Upload-File permettant l'upload d'image
               trouvé sur http://hayageek.com/docs/jquery-upload-file.php -->
         <script src="./js/plugins/jquery.uploadfile.js"></script>
         <link rel="stylesheet" type="text/css" media="all" href="./css/uploadfile.css" />

         <!-- Plugin JS jQuery.print permettant d'imprimer une partie de page
               trouvé sur https://github.com/DoersGuild/jQuery.print -->
         <script src="./js/plugins/jQuery.print.js"></script>
      <!---------------------------- END Plugins --------------------- -->
   </head>
   <body>
        <div class="print"><span>Application WEB : Avalez le crapaud</span><span>Utilisateur : <?php echo $prenom .' '. $nom; ?></span></div>
        <div id="menu_wrapper">
            <div id="menu_toggle">
               <img src="./images/menu_toggle.png" alt="Toggle"/>
            </div>
            <nav id="menu">
               <ul class="fleft">
               <?php
                  include_once('includes/menu.php');
               ?>
               </ul>
               <ul class="fright">
                  <li><a class="liens" href="logout.php">Déconnexion</a></li>
               </ul>
            </nav>
        </div>
