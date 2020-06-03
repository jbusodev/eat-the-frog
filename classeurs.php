<?php
    require_once('includes/header.php');
?>
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
            require_once('includes/footer.php');
        ?>
