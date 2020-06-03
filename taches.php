<?php
    require_once('includes/header.php');
?>
            <div id="contenu">
                <section id="Taches" class="sections sections1">
                    <div class="panels first">
                        <?php echo $messageLogin; ?>
                        <div class="left"><span class="imprint">imprimer</span></div>
                        <h1>Liste des tâches</h1>

                        <div id="parent-toggle_finished" class="hidden margin-left margin-bottom inline-block">
                            <input type="checkbox" id="toggle_finished" name="example1">
                            <label class="custom-control-label" for="toggle_finished">Afficher les tâches terminées</label>
                        </div>

                        <div id="hide" class="hidden margin-left margin-bottom inline-block">
                            <input type="checkbox" id="hide_ongoing" name="example1">
                            <label class="custom-control-label" for="hide_ongoing">Masquer les tâches en cours</label>
                        </div>
                        <!--
                            // initial checkbox - finished tasks
                            <div>
                                <input type="checkbox" id="toggle_finished"/>
                                <span for="toggle_finished">Afficher les tâches terminées</span>°§§§§§§§§§§§§§§§§§§§§
                            </div>
                            // initial checkbox - hide ongoing tasks
                            <div id="hide" class="hidden">
                                <input type="checkbox" id="hide_ongoing"/>
                                <span for="hide_ongoing">hide les tâches en cours</span>
                            </div>
                        -->
                        <table class="tables">
                            <thead>
                                <tr>
                                    <th>Priorité</th>
                                    <th>Tâche</th>
                                    <th>Date début</th><th>Date de fin</th>
                                    <th class="actions">Actions</th>
                                </tr>
                            </thead>
                        <?php
                            include_once('includes/tableau.php');
                        ?>
                        </table>
                        <!--
                        <div id="add">
                            <span class="add" title="Ajouter une nouvelle tâche">+</span>
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
        </div>
