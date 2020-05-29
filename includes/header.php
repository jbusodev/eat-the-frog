<?php
      session_start();
      require_once ('calls/functions.php');
      require_once('./connexionPDO.php');
      $nom = isset( $_SESSION['nomUtilisateur'] ) ? $_SESSION['nomUtilisateur'] : '';
      $prenom = isset( $_SESSION['prenomUtilisateur'] ) ? $_SESSION['prenomUtilisateur'] : 'invite';
      $user = isset( $_SESSION['user'] ) ? $_SESSION['user'] : header('Location: index.php');

      $url = $_SERVER['REQUEST_URI'];
      getCurrentPage($url); // cleans url to get current page

      $_SESSION['page'] = $url;
      $page = $_SESSION['page'];

      $menu_utilisateurs = '';
      $menu_images = '';
      $mode = '<b>Utilisateur</b>';
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