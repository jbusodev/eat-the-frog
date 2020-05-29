<?php
session_start();
require_once('../connexionPDO.php');
require_once('functions.php');

/* ------------------------- INITIALISATION DES VARIABLES -------------------- */
$reponse = '';
$numero = isset( $_POST['nItem'] ) ? $_POST['nItem'] : '';
$action = isset( $_POST['action'] ) ? $_POST['action'] : '';
$newUtilisateur = isset( $_POST['newUtilisateur'] ) ? $_POST['newUtilisateur'] : '';

$nomU = $newUtilisateur !== '' && isset($newUtilisateur['nom']) && $newUtilisateur['nom'] !== '' ? $newUtilisateur['nom'] : '';
$prenomU = $newUtilisateur !== '' && isset($newUtilisateur['prenom']) && $newUtilisateur['prenom'] !== '' ? $newUtilisateur['prenom'] : '';
$pseudo = $newUtilisateur !== '' && isset($newUtilisateur['pseudo']) && $newUtilisateur['pseudo'] !== '' ? $newUtilisateur['pseudo'] : '';
$pwd = $newUtilisateur !== '' && isset($newUtilisateur['mdp']) && $newUtilisateur['mdp'] !== '' ? $newUtilisateur['mdp'] : '';
$pwdCheck = $newUtilisateur !== '' && isset($newUtilisateur['mdp2']) && $newUtilisateur['mdp2'] !== '' ? $newUtilisateur['mdp2'] : '';
$niveauU = $newUtilisateur !== '' && isset($newUtilisateur['niveau']) && $newUtilisateur['niveau'] !== '' ? $newUtilisateur['niveau'] : '';

// Conversion et protection des variables / chaînes de caractères
$nom = cleanString($nomU);
$prenom = cleanString($prenomU);
$intNiveau = intval($niveauU);
$intNumero = intval($numero);
unset($nomU, $prenomU, $pseudoU, $niveauU, $numero, $newUtilisateur);


/* ------------------------- END - INITIALISATION DES VARIABLES -------------------- */
    switch ($action){
        case 'add':
            try {
                $isPwdSame = $pwd === $pwdCheck ? true : false;
                $reponsePseudo = $isPwdSame ? "" : "<p>Les mots de passe doivent être égaux.</p>";
                unset($isPwdSame);

                $pattern = '/^[a-zA-Z]+$/';
                $isPseudoGood = preg_match($pattern, $pseudo) ? true : false;
                $responsePwd = $isPseudoGood ? "" : "<p>Le nom d'utilisateur doit s'écrire en un mot sans espaces, contenir uniquement des lettres et aucun caractère accentué.</p>";
                unset( $isPseudoGood );

                if( $nom !== '' && $prenom !== '' && $pseudo !== '' && $pwd !== '' && $pwdCheck !== '' && $intNiveau !== 0 ) {
                    if( $reponsePseudo === '' && $responsePwd === '' ) {
                        if( !existePseudo($connexion, $pseudo) ) {
                            $query_add = "INSERT INTO tbl_users(nomUtilisateur, prenomUtilisateur, pseudo, password, num_tbl_niveaux) VALUES(:nom, :prenom, :pseudo, MD5(:pwd), :niveau)";
                            $res_add = $connexion->prepare($query_add);
                            // Liaison des variables pour protection de la requête
                            $res_add->bindValue(':nom', $nom, PDO::PARAM_STR);
                            $res_add->bindValue(':prenom', $prenom, PDO::PARAM_STR);
                            $res_add->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
                            $res_add->bindValue(':pwd', $pwd, PDO::PARAM_STR);
                            $res_add->bindValue(':niveau', $intNiveau, PDO::PARAM_INT);
                            $res_add->execute();
                            $res_add->closeCursor();
                            $reponse = 'OK';
                        } else {
                            $reponse = "<p>Le nom d'utilisateur est déjà utilisé.</p>";
                        }
                    } else {
                        $reponse = $reponsePseudo . $responsePwd;
                    }
                } else {
                    $reponse = "Veuillez remplir tous les champs.";
                }
            } catch(Exception $e){
                $reponse = "Erreur : Insertion dans la base de données impossible.";
            }
        break;
        case 'edit':
            try{
                $query = "UPDATE tbl_users SET num_tbl_niveaux=:niveau WHERE numero = :numero";
                $res = $connexion->prepare($query);
                // Liaison des variables pour protection de la requête
                $res->bindValue(':niveau', $intNiveau, PDO::PARAM_INT);
                $res->bindValue(':numero', $intNumero, PDO::PARAM_INT);
                $res->execute();
                $res->closeCursor();
                $reponse = "OK";
            } catch (Exception $e){
                $reponse = "Erreur SQL : Suppression de l'utilisateur impossible";
            }
        break;
        case 'delete':
            try{
                $query_del = "DELETE FROM tbl_users WHERE numero=:num";
                $res_del = $connexion->prepare($query_del);
                // Liaison des variables pour protection de la requête
                $res_del->bindValue(':num', $intNumero, PDO::PARAM_INT);
                $res_del->execute();
                $res_del->closeCursor();
                $reponse = "OK";
            } catch (Exception $e){
                $reponse = "Erreur SQL : Suppression de l'utilisateur impossible";
            }
        break;
        default :
            $reponse = "Erreur PHP : Action non définie";
            break;
    }

$arr = array('reponse' => $reponse);
echo json_encode($arr);
