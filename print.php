<?php
session_start();
require_once('calls/pdo.php');
$nom = $_SESSION['nomUtilisateur'];
$prenom = $_SESSION['prenomUtilisateur'];
$nomComplet = $prenom .' '. $nom;
unset($nom, $prenom);

$tailleDos = '';
$filePath = 'images/classeurs/';
// Reprise de l'objet Tache contenant les champs
$print = isset( $_POST['data']['print'] ) ? $_POST['data']['print'] : '';
$titres = isset( $_POST['data']['titres'] ) ? $_POST['data']['titres'] : '';
// Attribution des champs des objets dans des variables
$titreClasseur = $print !== '' && isset($print['titreClasseur']) && $print['titreClasseur'] !== '' ? $print['titreClasseur'] : '';
$nbRepertoire = $print !== '' && isset($print['nbRepertoire']) && $print['nbRepertoire'] !== '' ? $print['nbRepertoire'] : '';
$fileName = $print !== '' && isset($print['image']) && $print['image'] !== '' ? $print['image'] : '';
$tailleImage = $print !== '' && isset($print['tailleImage']) && $print['tailleImage'] !== '' ? $print['tailleImage'] : '';

$widthDosPetit = '3cm';
$widthImagePetit = '2.5cm';
$heightImagePetit = '3.5cm';
$widthDosGrand = '5cm';
$widthImageGrand = '4cm';
$heightImageGrand = '6cm';
$heightDos = '14cm'; // hauteur fixe

$intNbRepertoire = intval($nbRepertoire);
?>
<html lang="fr">
    <head>
            <meta name="viewport" content="width=device-width, user-scalable=no">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Avalez le crapaud - Impression</title>
            <link rel="stylesheet" type="text/css" media="all" href="./css/polices/polices.css" />
            <link rel="icon" href="images/favicon.ico" />

            <!-- jQuery -->
            <!--
            <script type="text/javascript" src="./js/lib/jquery-2.1.1.min.js"></script>
            -->
            <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
            <script type="text/javascript" src="./js/functions.js"></script>
            <script type="text/javascript" src="./js/events.js"></script>

            <!-- jQuery UI -->
            <script type="text/javascript" src="./js/lib/jquery-ui.min.js"></script>
            <link rel="stylesheet" type="text/css" media="all" href="./css/jquery-ui.css" />

            <!-- Plugin JS jQuery.print permettant d'imprimer une partie de page
                 trouvé sur https://github.com/DoersGuild/jQuery.print -->
            <script src="./js/plugins/jQuery.print.js"></script>
        <!-- -------------------------- END Plugins --------------------- -->

    </head>
    <body>
        <div id="print">
            <h1>Dos de classeur</h1>
            <div id="dos">
                <div id="dosPetit" style="vertical-align:top;border:3px dashed #666;margin: 2em;padding:8px;display:inline-block;width:<?php echo $widthDosPetit ?>;height:<?php echo $heightDos ?>;text-align:center">
                    <p style="border: 1px solid black"><b>CPLN-ET</b></p>
                    <p style="margin:0 auto;width:<?php echo $widthImagePetit ?>;height:<?php echo $heightImagePetit ?>;">
                        <img src="<?php echo $filePath.$fileName ?>" style="vertical-align: middle;max-width:100%;max-height:100%" alt=""/>
                    </p>
                    <span>TIP 2015</span>
                    <p style="border: 1px solid black"><b><?php echo $titreClasseur ?></b></p>
                    <p><?php echo $nomComplet ?></p>
                </div>
                <div id="dosGrand" style="vertical-align:middle;border:3px dashed #666;margin: 2em;padding:8px;display:inline-block;width:<?php echo $widthDosGrand ?>;height:<?php echo $heightDos ?>;text-align:center">
                    <p style="border: 1px solid black"><b>CPLN</b></p>
                    <p style="margin:0 auto;width:<?php echo $widthImageGrand ?>;height:<?php echo $heightImageGrand ?>;">
                        <img src="<?php echo $filePath.$fileName ?>" style="vertical-align: middle;max-width:100%;max-height:100%" alt=""/>
                    </p>
                    <span>TIP 2015</span>
                    <p style="border: 1px solid black"><b><?php echo $titreClasseur ?></b></p>
                    <p><?php echo $nomComplet ?></p>
                </div>
            </div>
            <input id="imprimerDos" type="button" value="Imprimer le dos de classeur"/>
            <h1>Répertoire</h1>
            <div id="repertoire">
                <?php
                $heightRep = 100/$intNbRepertoire;
                for($i = 1; $i < ($nbRepertoire + 1); $i++){
                    echo '<div style="height:'. $heightRep .'%;position:relative;border-bottom:1px solid black">';
                    if( isset($titres[$i]) ){
                        // reuperation de la valeur du titre
                        $query = "SELECT * FROM tbl_titres WHERE numero = $titres[$i]";
                        $res = $connexion->prepare($query);
                        $res->execute();
                        $obj = $res->fetch(PDO::FETCH_OBJ);
                        $nomTitre = $obj->titre;
                        $res->closeCursor();
                        unset($res, $obj, $query);
                        echo '<p style="margin:0"><span style="margin-right:3em;"><b>'. $i .'.</b></span>'. $nomTitre .'</p>';
                    } else {
                        echo '<p style="margin:0"><span><b>'. $i .'.</b></span></p>';
                    }
                    echo '</div>';
                }
                ?>
            </div>
            <input id="imprimerRepertoire" type="button" value="Imprimer le repertoire"/>
        </div>
    </body>
</html>
<script>
    $(document).ready(function(){
        var repHeight = $('#repertoire div').height();
        var repHeight = repHeight;
        var pxH = repHeight + 'px';
        $('#repertoire div').css('line-height', pxH);

        $('*').css({
            '-moz-box-sizing'       : 'border-box',
            '-webkit-box-sizing'    : 'border-box',
            'box-sizing'            : 'border-box',
            'font-family'           : 'Raleway-light'
        });

        $('#imprimerDos').click(function(){
            $('#dos').print();
        });
        $('#imprimerRepertoire').click(function(){
            $('#repertoire').print();
        });
    });
</script>