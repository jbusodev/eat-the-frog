<?php
session_start();
require_once('connexionPDO.php');
$url = htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, "utf-8");

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
            <title>Avalez le crapaud - Connexion</title>
            <link rel="stylesheet" type="text/css" media="all" href="./css/responsive.css" />
            <link rel="stylesheet" type="text/css" media="all" href="./css/polices/polices.css" />
            <link rel="icon" href="images/favicon.ico" />
            
            <!-- Bootstrap dependencies + jQuery -->
            <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
            <!-- Bootstrap -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

            <!-- Custom Functions + Events -->
            <script type="text/javascript" src="./js/functions.js"></script>
            <script type="text/javascript" src="./js/events.js"></script>
    </head>
    <body>
        <div id="fond">
            <div id="conteneur">
                <div id="login">
                    <h1>Avalez le crapaud</h1>
                    <h2>Connexion</h2>
                    <label class="loginresponse"></label>
                    <div style="margin: 0 auto">
                        <div class="champs">
                            <label>Nom d'utilsateur</label>
                            <input id="user" type="text" placeholder="Entrez votre nom d'utilisateur"/>
                            <label class="messagesUser" style="min-width: 0"></label><span id="messageUser" class="messagesUser messages"></span>
                        </div>
                        <div class="champs">
                        <label>Mot de passe</label>
                            <input id="password" type="password" placeholder="Entrez votre mot de passe"/>
                            <label class="messagesPwd" style="min-width: 0"></label><span id="messagePwd" class="messagesPwd messages"></span>
                        </div>
                        <input id="btnLogin" type="button" class="bouton" value="Connexion"/>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>