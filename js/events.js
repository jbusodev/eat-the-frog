
// Animations Menu
$(document).ready(function() {
/* ------------------------ Evenements page de Connexion ------------------------ */
    // Vérification de pseudo
    verifieIdentifiants();
/* -------------------------- FIN Evenements page de connexion */


/* -------------------------- Evenements tableaux ------------------- */

    // Affichage des tâches terminées au clic de la checkbox
    $('#cacher_encours').prop('checked', false);
    $('#toggle_terminees').click(function(){
        if( $(this).is(':checked') ){
            $('.terminees').show();
            $('#cacher').show();
        } else{
            $('.terminees').hide();
            $('#cacher').hide();
        }
    });
    $('#cacher_encours').click(function(){
        $('.encours').toggle();
    });
    $('#password').keydown(function(event){
        if(event.keyCode === 13){
            $('#btnLogin').click();
        }
    });
    iconesBoutons();
/* -------------------------- FIN Evenements tableaux ------------------- */


/* -------------------------- Evenements Boites de dialogue ------------------- */
     
   // Définition des paramètres par défaut des datepicker (calendrier de sélection de date)
    setDefaultDatepicker();
    
        
    // Terminer un tâche
    $( document ).on( "click", ".finir", function(){
        var nItem = $(this).attr('id');
        setDialog('end', nItem);
    });
    
    // Ajouter une tâche
    $( document ).on( "click", ".add", function(){
        setDialog('add');
    });
    
    // Modifier une tâche
    $( document ).on( "click", ".edit", function(){
        var nItem = $(this).attr('id');
        setDialog('edit', nItem);
    });
    
    // Supprimer une tâche
    $( document ).on( "click", ".del", function(){
        var nItem = $(this).attr('id');
        setDialog('delete', nItem);
    });
    $( document ).on("click", ".imprint", function(){
        var nItem = $(this).attr('id'); // le numero de la ligne est récuperé
        image = $(this).attr('data-image'); // l'image de la ligne est récuperé
        if( nItem ){
            setDialog('print', nItem);
        } else{
            window.print();
        }
    });
    // Appel de la fonction qui rafrachit la liste des titres selon le nb de repertoires choisi
    // sur la page classeurs.php
    $( document ).on("change", "#c_repertoire", function(){
        var nItem = $('#item').val();
        rafraichirTitresDialog(nItem);
    });
    $( document ).on("click", "#toggle_titres", function(){
        if(toggled){
            $('#toggle_titres').text('(Afficher)');
            toggled = false;
        } else{
            $('#toggle_titres').text('(Masquer)');
            toggled = true;
        }
        $('#hidden_titres').toggle();
    });
    
    
    
/* -------------------------- FIN Evenements Boites de dialogue -------------- */


/* -------------------------- Evenements page de gestion des titres -------------- */
    requetesTitres();
    $('#advertise').show();
/* -------------------------- FIN Evenements page de gestion des titres -------------- */


/* -------------------------- Evenements page de gestion des utilisateurs -------------- */
    eventsUsers();
    $('.save_user').click(function(){
        var nItem = $(this).attr('id');
        var action = 'edit';
        var newUtilisateur = new Object();
        newUtilisateur.niveau = $('#s_niveaux option:selected').val();
        $.ajax({
            type: 'POST',
            url: 'calls/user.php',
            data: {action:action, nItem:nItem, newUtilisateur:newUtilisateur},
            dataType:"json",
            success: function(data){
                    if(data.reponse === 'OK'){
                        $('#s_niveaux[value="0"]').prop('selected', true);
                        $('#messageUser').show();
                        $('#messageUser').text("Les modifications ont été enregistrées");
                        $('#s_users option[value="0"]').prop('selected', true);
                        $('.del_user').prop('disabled', true);
                        eventsUsers();
                        $('#s_niveaux').hide();
                        $('.save_user').prop('disabled', true);
                    }else{
                        $('#messageUser').show();
                        $('#messageUser').text('');
                        $('#messageUser').append(data.reponse);
                    }
            },
            error: function(data){
                $('#messageUser').replaceWith(data);
            }
        });
    });
/* -------------------------- Evenements page de gestion des utilisateurs -------------- */


/* -------------------------- Evenements généraux -------------- */

    // Evenement click sur bouton menu Mobile
    $( "#menu_toggle img" ).click(function() {
            $( "#menu ul" ).slideToggle();
    });
    
    // Evenement clic menu
    $( ".liens" ).click(function() {
            $( "#menu ul" ).toggle();
    });
});

/* -------------------------- FIN Evenements généraux -------------- */