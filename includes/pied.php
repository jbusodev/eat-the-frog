<?php
if ( isset($_SESSION['user']) ) {
    $menu_inscription = '';
    $menu_connexion = '<li><a href="logout.php">Déconnexion</a></li>';
} else {
    $menu_inscription = '<li><a href="#Sign">Inscription</a></li>';
    $menu_connexion = '';
}
?>
<div id="pied_wrapper">
	<div id="pied">
		<div>
                        <!--
			<ul class="little ul_ligne">
				<li><a href="contact.php">Nous contacter</a></li>
				<li><a href="userconditions.html">Conditions d'utilisation</a></li>
				<li><a href="generalconditions.php">Conditions générales</a></li>
			</ul>
                        -->
			<ul class="little inline-block">
                            <li><span class="little">© Copyright 2015 CPLN-ET. Tous droits réservés. <i>Application développée par Jonathan Buso</i></span></li>
			</ul>
		</div>
	</div>
</div>
