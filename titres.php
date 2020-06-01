<?php
    require_once('includes/header.php');
?>
            <div id="contenu">
                <section id="Taches" class="sections sections1">
                    <div class="panels first">
                        <?php echo $messageLogin; ?>
                        <h1>Liste des Titres</h1>
                        <div class="margin-left">
                            <p id="advertise" class="rouge">Vous devez choisir un titre pour activer les boutons</p>
                            <label>Titre :</label>
                            <?php
                                include_once('includes/titres.php');
                            ?>
                            <input type="text" id="c_edit_titre" placeholder="Entrez le nouveau titre ici"/>
                            <input id="edit_titre" class="bouton inline-block" type="button" value="Modifier" disabled/>
                            <input id="save_titre" class="bouton" type="button" value="Enregistrer" disabled/>
                            <span id="messageEdit" class="margin-top"></span>
                            <div id="add_titre" class="margin-top">
                                <a>Ajouter un titre</a>
                            </div>
                            <br/>
                            <input type="text" id="c_add_titre" placeholder="Entrez le nouveau titre ici"/>
                            <input type="button" id="save_add_titre" class="bouton btnDisabled" value="Enregistrer"/>
                            <span id="messageAdd"></span>
                            <input id="page" type="hidden" value="titres"/>
                        </div>

                    </div>

                </section>
            </div>
            <?php
                require_once('includes/footer.php');
            ?>
        </div>
    </body>
</html>
