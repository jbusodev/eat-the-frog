<?php
if ( isset($_SESSION['user']) ) {
    $menu_inscription = '';
    $menu_connexion = '<li><a href="logout.php">Déconnexion</a></li>';
} else {
    $menu_inscription = '<li><a href="#Sign">Inscription</a></li>';
    $menu_connexion = '';
}
?>
<div id="footer_wrapper">
	<div id="footer">
		<div>
			<!--
			<ul class="little ul_ligne">
				<li><a href="contact.php">Nous contacter</a></li>
				<li><a href="userconditions.html">Conditions d'utilisation</a></li>
				<li><a href="generalconditions.php">Conditions générales</a></li>
			</ul>
			-->
			<ul class="little inline-block">
                <li><span class="little">© Copyright 2020. Tous droits réservés. <i>Application développée par <a href="https://jbusodev.github.io">Jonathan Buso</a></i></span></li>
			</ul>
		</div>
	</div>
</div>

      <!-- jQuery -->
      <!--
      <script type="text/javascript" src="./js/lib/jquery-2.1.1.min.js"></script>
      -->
      <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
      <!-- jQuery UI -->
      <script type="text/javascript" src="./js/lib/jquery-ui.min.js"></script>
      <script type="text/javascript" src="./js/functions.js"></script>
      <script type="text/javascript" src="./js/events.js"></script>
      <!---------------------------- Plugins --------------------- -->
         <!-- Plugin JS jQuery-Upload-File permettant l'upload d'image
               trouvé sur http://hayageek.com/docs/jquery-upload-file.php -->
					<script src="./js/plugins/jquery.uploadfile.js"></script>

         <!-- Plugin JS jQuery.print permettant d'imprimer une partie de page
               trouvé sur https://github.com/DoersGuild/jQuery.print -->
					<script src="./js/plugins/jQuery.print.js"></script>
</body>
</html>
