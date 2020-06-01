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
