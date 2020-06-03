<?php
    require_once('includes/header.php');
?>
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
            require_once('includes/footer.php');
        ?>
