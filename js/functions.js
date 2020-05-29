// Login Page - Functions
function checkCredentials() {
    // login fileds
    let username = document.getElementById('user');
    let password = document.getElementById('password');

    // error message
    let errormessage = document.getElementsByClassName('error');

    username.focus();

    // Authentication check
    $('#btnLogin').click(function() {
        user = $(username).val();
        pwd = $(password).val();

        $.ajax({
            type: 'POST',
            url: 'calls/login.php',
            data: { user: user, pwd: pwd },
            dataType: "json",
            success: function(data) {
                // Successfully authenticated
                if (data.redirect !== '') {
                    fieldsMessages(errormessage);
                    setTimeout(function() {
                        window.location.href = data.redirect;
                    }, 600);

                }
                // Empty fields
                else if (data.user == "" || data.pwd == "") {
                    $(errormessage).text('Veuillez remplir tous les champs.');
                    $(errormessage).fadeIn();
                }
                // No matching credentials
                else {
                    $(errormessage).text('Aucun utilisateur/mot de passe ne correspond à ces identifiants. Veuillez vérifiez les champs.');
                    $(errormessage).fadeIn();
                }
                $(username).select();
            },
            error: function(data) {
                console.error(data);
                $(errormessage).text('Aucun utilisateur/mot de passe ne correspond à ces identifiants. Veuillez vérifiez les champs.');
                $(errormessage).fadeIn();
            }
        });
    });
}

function fieldsMessages(err, usr, pass) {
    // UI Refresh
    $(err).text('');
    $(err).hide();
    $('#message').hide();
    $('#messageUser').hide();
    $('#messagePwd').hide();

    // Get fields values
    var user = $('#user').val();
    var pwd = $('#password').val();

    // --------------- Username Check via Ajax -> PHP -> SQL(PDO) ------------
    $.ajax({
        type: 'POST',
        url: 'calls/login.php',
        data: { user: user, pwd: pwd }, // fields values sent in request
        dataType: "json",
        success: function(data) { // data includes the login process response (JSON Array)
            if (data.responseUser === "OK") { // responseUser corresponds to username response
                $('#user').css("border", "1px solid #0D9123");
                $('#messageUser').css({ //Change la couleur du message et son ombre
                    "color": "#66FF00"
                });
                $('#messageUser').show(); // Affiche le message du champ login
                $('.messages').hide;
                $('#messageUser').text('✓');
            }
        },
        error: function(data) {
            //alert('erreur');
        }
    });

    // -------------------------- Password Check --------------------------

    // Gets password length
    var taille = (pwd).length;

    if (taille >= 4) {
        $.ajax({
            type: 'POST',
            url: 'calls/login.php',
            data: { user: user, pwd: pwd },
            dataType: "json",
            success: function(data) {
                if (data.responsePwd === "OK") {
                    $('#password').css("border", "1px solid #0D9123");
                    $('#messagePwd').css({
                        "color": "#66FF00"
                    });
                    $('#messagePwd').show();
                    $('.messagesPwd').show;
                    $('#messagePwd').text('✓');
                } else {
                    $('#password').css("border", "1px solid #FF2B58");
                    $('#messagePwd').css("color", "red");
                    $('#messagePwd').show();
                    $('#messagePwd').text(data.responsePwd);
                }
            },
            error: function() {
                console.error(data);
            }
        });
    } else {
        $('#messagePwd').hide();
        $('#password').css({
            "border": "1px solid",
            "border-color": "#999 #BBB #DDD"
        });
    }

}

// END Login Page - Functions

// ----------------------------- General Functions ---------------------------
function setDefaultDatepicker() {
    $.datepicker.regional['fr'] = {
        minDate: 0,
        dateFormat: "dd-mm-yy",
        showOtherMonths: true,
        selectOtherMonths: true,
        showButtonPanel: true,
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['fr']);
}

/** Dialogs function. Including:
 * addition, edition, deletion
 * of different elements
 * (tasks | folders | titles)
 * */

function setDialog(action, nItem) {
    // Valeur par défaut pour le paramètre precision
    if (typeof(nItem) == 'undefined') {
        nItem = '';
    }
    toggled = false;
    $.post("includes/dialog.php", { action: action, nItem: nItem })
        .done(function(data) {
            $('#dialog').remove();
            $('.tables').after(data);
            var page = $('#page').val();
            var pseudo = $('#pseudo').val();
            var aujourdhui = '';
            switch (page) {
                case 'taches':
                    switch (action) {
                        case 'end':
                            $('#dialog').dialog({
                                resizable: true,
                                draggable: false,
                                modal: true,
                                buttons: {
                                    "Enregistrer": function() {
                                        var newTache = new Object();
                                        newTache.dateF = $('#c_dateF').val();
                                        $.ajax({
                                            type: 'POST',
                                            url: 'calls/tache.php',
                                            data: { action: action, newTache: newTache, nItem: nItem },
                                            dataType: "json",
                                            success: function(data) {
                                                if (data.reponse !== 'ko') {
                                                    $('#message').text(data.reponse);
                                                    $('#dialog').dialog({
                                                        buttons: {
                                                            Fermer: function() {
                                                                $(this).dialog("close");
                                                            }
                                                        }
                                                    })
                                                    rafraichirtable();
                                                    $('#hide').hide();
                                                    $('#toggle_finished').prop('checked', false);
                                                    $('#hide_ongoing').prop('checked', false);
                                                } else {
                                                    $('#message').addClass('error');
                                                    $('#message').text('Veuillez remplir les champs obligatoires.');
                                                }
                                            },
                                            error: function(data) {
                                                console.log(data);
                                                $('#message').replaceWith(data.responseText);
                                            }
                                        });
                                    },
                                    Annuler: function() {
                                        $(this).dialog("close");
                                    }
                                }
                            });
                            $('.c_caches').hide();
                            $('#hidden_dates').show();
                            break;
                        case 'add':
                            $('#dialog').dialog({
                                resizable: false,
                                draggable: false,
                                modal: true,
                                buttons: {
                                    "Enregistrer": function() {
                                        var newTache = new Object();
                                        newTache.tache = $('#c_tache').val();
                                        newTache.priorite = $('#c_priorite option:selected').val();
                                        newTache.dateD = $('#c_dateD').val();
                                        newTache.dateF = $('#toggle_dateFyes').is(':checked') ? $('#c_dateF').val() : '';
                                        newTache.remarque = $('#c_remarque').val();
                                        $.ajax({
                                            type: 'POST',
                                            url: 'calls/tache.php',
                                            data: { action: action, newTache: newTache },
                                            dataType: "json",
                                            success: function(data) {
                                                if (data.reponse !== 'ko') {
                                                    // refreshes UI upon succesful request
                                                    $('#c_tache').val('');
                                                    $('#c_priorite option[value=0]').prop('selected', true);
                                                    $('#c_dateD').val(data.aujourdhui);
                                                    $('#c_dateF').val(data.aujourdhui);
                                                    $('#c_remarque').val('');
                                                    $('#message').fadeIn();
                                                    $('.error').toggleClass('success');
                                                    $('#message').text(data.reponse);
                                                    rafraichirtable();
                                                    $('#hide').hide();
                                                    $('#toggle_finished').prop('checked', false);
                                                    $('#hide_ongoing').prop('checked', false);
                                                    return;
                                                } else {
                                                    $('#message').addClass('error');
                                                    $('#message').fadeIn();
                                                    $('#message').text('Veuillez remplir les champs obligatoires.');
                                                }

                                            },
                                            error: function(data) {
                                                $('#message').replaceWith(data);
                                            }
                                        });
                                    },
                                    Annuler: function() {
                                        $(this).dialog("close");
                                    }
                                }
                            });
                            break;
                        case 'edit':
                            $('#dialog').dialog({
                                resizable: false,
                                draggable: false,
                                modal: true,
                                buttons: {
                                    "Enregistrer": function() {
                                        var newTache = new Object();
                                        newTache.tache = $('#c_tache').val();
                                        newTache.priorite = $('#c_priorite option:selected').val();
                                        newTache.dateD = $('#c_dateD').val();
                                        newTache.dateF = $('#toggle_dateFyes').is(':checked') ? $('#c_dateF').val() : '';
                                        newTache.remarque = $('#c_remarque').val();
                                        $.ajax({
                                            type: 'POST',
                                            url: 'calls/tache.php',
                                            data: { action: action, newTache: newTache, nItem: nItem },
                                            dataType: "json",
                                            success: function(data) {
                                                if (data.reponse !== 'ko') {
                                                    $('#message').fadeIn();
                                                    $('#message').text(data.reponse);
                                                    $('#dialog').dialog({
                                                        buttons: {
                                                            Fermer: function() {
                                                                $(this).dialog("close");
                                                            }
                                                        }
                                                    })
                                                    rafraichirtable();
                                                    $('#hide').hide();
                                                    $('#toggle_finished').prop('checked', false);
                                                    $('#hide_ongoing').prop('checked', false);
                                                } else {
                                                    $('#message').addClass('error');
                                                    $('#message').text('Veuillez remplir les champs obligatoires.');
                                                }
                                            },
                                            error: function(data) {
                                                console.log(data);
                                                $('#message').replaceWith(data.responseText);
                                            }
                                        });
                                    },
                                    Annuler: function() {
                                        $(this).dialog("close");
                                    }
                                }
                            });
                            break;
                        case 'delete':
                            $('#dialog').dialog({
                                resizable: false,
                                draggable: false,
                                modal: true,
                                buttons: {
                                    "Confirmer": function() {
                                        $.ajax({
                                            type: 'POST',
                                            url: 'calls/tache.php',
                                            data: { action: action, nItem: nItem },
                                            dataType: "json",
                                            success: function(data) {
                                                if (data.reponse !== 'ko') {
                                                    $('#message').text('Votre tâche a été supprimée !');
                                                    $('#dialog').dialog({
                                                        buttons: {
                                                            Fermer: function() {
                                                                $(this).dialog("close");
                                                            }
                                                        }
                                                    })
                                                    rafraichirtable();
                                                    $('#hide').hide();
                                                    $('#toggle_finished').prop('checked', false);
                                                    $('#hide_ongoing').prop('checked', true);
                                                } else {
                                                    $('#message').addClass('error');
                                                    $('#message').fadeIn();
                                                    $('#message').text('Veuillez remplir les champs obligatoires.');
                                                }

                                            },
                                            error: function(data) {
                                                console.log(data);
                                                $('#message').replaceWith(data.responseText);
                                            }
                                        });
                                    },
                                    Fermer: function() {
                                        $(this).dialog("close");
                                    }
                                }
                            });
                            $('.c_caches').hide();
                            break;
                    }
                    if ($('#isDateF').val() === 'true') {
                        $('#toggle_dateFyes').prop('checked', true);
                        $('#hidden_dates').show();
                    }
                    toggleDateFin();
                    $("#c_dateD").datepicker({
                        onClose: function() {
                            var dateMin = $('#c_dateD').val().split("-");
                            var d = new Date(dateMin[2], dateMin[1] - 1, dateMin[0]);
                            $("#c_dateF").datepicker(
                                "change", { minDate: d }
                            );
                        }
                    });
                    break;
                case 'classeurs':
                    switch (action) {
                        case 'add':
                            $('#dialog').dialog({
                                top: 100,
                                width: 500,
                                resizable: false,
                                draggable: true,
                                modal: true,
                                position: { my: "bottom", at: "center" },
                                buttons: {
                                    "Enregistrer": function() {
                                        var newClasseur = new Object();
                                        var newTitres = new Object();
                                        var valRep = $('#f_directory').val();
                                        newClasseur.titreClasseur = $('#c_titreClasseur')
                                        newClasseur.nbRepertoire = $('#f_directory option[value=' + valRep + ']').text();
                                        var rep = newClasseur.nbRepertoire;
                                        newClasseur.titreClasseur = $('#c_titreClasseur').val();
                                        newClasseur.image = $('#image').val();
                                        for (i = 1; i <= rep; i++) {
                                            var titre = '#c_titre_' + i + ' option:selected';
                                            var valTitre = $(titre).val();
                                            if (valTitre !== "0") {
                                                newTitres[i] = valTitre;
                                            }
                                        }
                                        $.ajax({
                                            type: 'POST',
                                            url: 'calls/classeur.php',
                                            data: { action: action, newClasseur: newClasseur, newTitres: newTitres },
                                            dataType: "json",
                                            success: function(data) {
                                                if (data.reponse == 'OK') {
                                                    // Vider les champs
                                                    $('#message').fadeIn();
                                                    $('#message').text('Le classeur a été ajouté !');
                                                    rafraichirtable();
                                                    $('#dialog').dialog({
                                                        buttons: {
                                                            Fermer: function() {
                                                                $(this).dialog("close");
                                                            }
                                                        }
                                                    })
                                                } else {
                                                    $('#message').addClass('error');
                                                    $('#message').fadeIn();
                                                    $('#message').text('Veuillez remplir les champs obligatoires.');
                                                }

                                            },
                                            error: function(data) {
                                                console.log(data);
                                                $('#message').replaceWith(data.responseText);
                                            }
                                        });
                                    },
                                    Annuler: function() {
                                        $(this).dialog("close");
                                        var image = $('#image').val();
                                        $.post("plugins/delete.php", { op: "delete", name: image },
                                            function(resp, textStatus, jqXHR) {
                                                //Show Message
                                                $("#status").children().remove();
                                                $("#status").append("<div>Fichier Supprimé</div>");
                                            });
                                    }
                                }
                            });
                            break;
                        case 'edit':
                            $('#dialog').dialog({
                                top: 100,
                                width: 500,
                                resizable: false,
                                draggable: true,
                                modal: true,
                                position: { my: "bottom", at: "center" },
                                buttons: {
                                    "Enregistrer": function() {
                                        var newClasseur = new Object();
                                        var newTitres = new Object();
                                        var valRep = $('#f_directory').val();
                                        newClasseur.nbRepertoire = $('#f_directory option[value=' + valRep + ']').text();
                                        var rep = newClasseur.nbRepertoire;
                                        for (i = 1; i <= rep; i++) {
                                            var titre = '#c_titre_' + i + ' option:selected';
                                            var valTitre = $(titre).val();
                                            if (valTitre !== "0") {
                                                newTitres[i] = valTitre;
                                            }
                                        }
                                        newClasseur.titreClasseur = $('#c_titreClasseur').val();
                                        newClasseur.image = $('#image').val();
                                        $.ajax({
                                            type: 'POST',
                                            url: 'calls/classeur.php',
                                            data: { action: action, newClasseur: newClasseur, newTitres: newTitres, nItem: nItem },
                                            dataType: "json",
                                            success: function(data) {
                                                if (data.reponse === 'OK') {
                                                    // Vider les champs
                                                    $('#status').text('');
                                                    $('#status').hide();
                                                    $('.ajax-file-upload-statusbar').hide();
                                                    $('#message').fadeIn();
                                                    $('#message').text('Le classeur a été modifié avec succès !');
                                                    rafraichirtable();
                                                    $('#dialog').dialog({
                                                        buttons: {
                                                            Fermer: function() {
                                                                $(this).dialog("close");
                                                            }
                                                        }
                                                    })
                                                } else {
                                                    $('#message').fadeIn();
                                                    $('#message').replaceWith(data.reponse);
                                                }

                                            },
                                            error: function(data) {
                                                console.log(data);
                                                $('#message').replaceWith(data.responseText);
                                            }
                                        });
                                    },
                                    Annuler: function() {
                                        $(this).dialog("close");
                                        var image = $('#image').val();
                                        $.post("plugins/delete.php", { op: "delete", name: image },
                                            function(resp, textStatus, jqXHR) {
                                                //Show Message
                                                $("#status").children().remove();
                                                $("#status").append("<div>Fichier Supprimé</div>");
                                            });
                                    }
                                }
                            });
                            break;
                        case 'delete':
                            $('#dialog').dialog({
                                top: 100,
                                width: 500,
                                resizable: false,
                                draggable: true,
                                modal: true,
                                buttons: {
                                    "Confirmer": function() {
                                        $.ajax({
                                            type: 'POST',
                                            url: 'calls/classeur.php',
                                            data: { action: action, nItem: nItem },
                                            dataType: "json",
                                            success: function(data) {
                                                if (data.reponse !== 'ko') {
                                                    $('#message').text('Le classeur a été supprimé !');
                                                    $('#dialog').dialog({
                                                        buttons: {
                                                            Fermer: function() {
                                                                $(this).dialog("close");
                                                            }
                                                        }
                                                    })
                                                    $('#toggle_finished').prop('checked', false);
                                                    $('#hide_ongoing').prop('checked', true);
                                                    rafraichirtable();
                                                } else {
                                                    $('#message').addClass('error');
                                                    $('#message').fadeIn();
                                                    $('#message').text('Veuillez remplir les champs obligatoires.');
                                                }

                                            },
                                            error: function(data) {
                                                console.log(data);
                                                $('#message').replaceWith(data.responseText);
                                            }
                                        });
                                    },
                                    Fermer: function() {
                                        $(this).dialog("close");
                                    }
                                }
                            });
                            $('.c_caches').hide();
                            break;
                        case 'print':
                            $('#dialog').dialog({
                                width: 600,
                                resizable: false,
                                draggable: true,
                                modal: true,
                                position: { my: "bottom", at: "center" },
                                buttons: {
                                    "Imprimer": function() {
                                        var print = new Object();
                                        var titres = new Object();
                                        print.titreClasseur = $('#c_titre')
                                        print.nbRepertoire = $('#f_directory').val();
                                        print.image = $('#image').val();
                                        print.tailleImage = $('input[name=tailleImage]:checked').val();
                                        var rep = print.nbRepertoire;
                                        print.titreClasseur = $('#c_titreClasseur').val();
                                        for (i = 1; i <= rep; i++) {
                                            var titre = '#c_titre_' + i + ' option:selected';
                                            var valTitre = $(titre).val();
                                            if (valTitre !== "0") {
                                                titres[i] = valTitre;
                                            }
                                        }
                                        $.post('print.php', {
                                                data: { print: print, titres: titres }
                                            }).done(function(data) {
                                                var x = window.open();
                                                x.document.open();
                                                x.document.write(data);
                                                x.document.close();
                                            }).fail(function(data) {
                                                alert(data.responseText);
                                            })
                                            .always(function(data) {
                                                $('#dialog').dialog("close");
                                            });
                                    },
                                    Annuler: function() {
                                        $(this).dialog("close");
                                    }
                                }
                            });
                            if (image) {
                                $('#image').val(image);
                            }
                            $('.input').prop('disabled', true);
                            break;
                    }
                    iconesBoutons();
                    refreshDialogTitles(nItem);
                    upload();
                    break;
                case 'images':
                    switch (action) {
                        case 'delete':
                            $('#dialog').dialog({
                                top: 100,
                                width: 500,
                                resizable: false,
                                draggable: true,
                                modal: true,
                                buttons: {
                                    "Confirmer": function() {
                                        $.ajax({
                                            type: 'POST',
                                            url: 'calls/image.php',
                                            data: { nItem: nItem },
                                            dataType: "json",
                                            success: function(data) {
                                                if (data.reponse === 'OK') {
                                                    $('#message').text('L\'image a été supprimée !');
                                                    $('#dialog').dialog({
                                                        buttons: {
                                                            Fermer: function() {
                                                                $(this).dialog("close");
                                                            }
                                                        }
                                                    })
                                                    rafraichirtable();
                                                } else {
                                                    $('#message').fadeIn();
                                                    $('#message').text('Impossible de supprimer');
                                                }

                                            },
                                            error: function(data) {
                                                console.log(data);
                                                $('#message').replaceWith(data.responseText);
                                            }
                                        });
                                    },
                                    Fermer: function() {
                                        $(this).dialog("close");
                                    }
                                }
                            });
                            $('.c_caches').hide();
                            break;
                    }
                    break;
                case 'users':
                    switch (action) {
                        case 'add':
                            $('#dialog').dialog({
                                width: 500,
                                resizable: false,
                                draggable: true,
                                modal: true,
                                buttons: {
                                    "Enregistrer": function() {
                                        var newUtilisateur = new Object();
                                        newUtilisateur.nom = $('#c_nom').val();
                                        newUtilisateur.prenom = $('#c_prenom').val();
                                        newUtilisateur.niveau = $('#c_niveaux option:selected').val();
                                        newUtilisateur.pseudo = $('#c_pseudo').val();
                                        newUtilisateur.mdp = $('#c_mdp').val();
                                        newUtilisateur.mdp2 = $('#c_mdp2').val();
                                        $.ajax({
                                            type: 'POST',
                                            url: 'calls/user.php',
                                            data: { action: action, newUtilisateur: newUtilisateur },
                                            dataType: "json",
                                            success: function(data) {
                                                if (data.reponse === 'OK') {
                                                    $('#c_nom').val('');
                                                    $('#c_prenom').val('');
                                                    $('#c_pseudo').val('');
                                                    $('#c_mdp').val('');
                                                    $('#c_mdp2').val('');
                                                    $('c_caches').val('');
                                                    $('#message').show();
                                                    $('#message').text("L'utilisateur a été ajouté avec succès");
                                                    rafraichirUsers();
                                                    $('#s_users option[value="0"]').prop('selected', true);
                                                    eventsUsers();
                                                    requetesUsers();
                                                } else {
                                                    $('#message').show();
                                                    $('#message').text('');
                                                    $('#message').append(data.reponse);
                                                }

                                            },
                                            error: function(data) {
                                                $('#message').replaceWith(data);
                                            }
                                        });
                                    },
                                    Annuler: function() {
                                        $(this).dialog("close");
                                    }
                                }
                            });
                            break;
                        case 'delete':
                            $('#dialog').dialog({
                                width: 500,
                                resizable: false,
                                draggable: false,
                                modal: true,
                                buttons: {
                                    "Confirmer": function() {
                                        $.ajax({
                                            type: 'POST',
                                            url: 'calls/user.php',
                                            data: { action: action, nItem: nItem },
                                            dataType: "json",
                                            success: function(data) {
                                                if (data.reponse === 'OK') {
                                                    $('#message').text("L'utilisateur a été supprimé !");
                                                    $('#dialog').dialog({
                                                        buttons: {
                                                            Fermer: function() {
                                                                $(this).dialog("close");
                                                            }
                                                        }
                                                    });
                                                    $('#s_users option[value="0"]').prop('selected', true);
                                                    $('#s_niveaux').hide();
                                                    rafraichirUsers();
                                                    eventsUsers();
                                                    $('#s_niveaux').hide();
                                                    $('.save_user').prop('disabled', true);
                                                } else {
                                                    $('#message').fadeIn();
                                                    $('#message').text(data.reponse);
                                                }

                                            },
                                            error: function(data) {
                                                console.log(data);
                                                $('#message').replaceWith(data.responseText);
                                            }
                                        });
                                    },
                                    Fermer: function() {
                                        $(this).dialog("close");
                                    }
                                }
                            });
                            $('.c_caches').hide();
                            break;
                    }
                    break;
            }

            /* ----------------- Evenement relatifs aux boîtes de dialogue ------------- */
            // Activation du calendrier aux champs dates
            $('.dates').datepicker();

            /* Masquage du message de retour à l'écriture dans les champs et
             * au changement d'option dans les listes déroulantes */
            $('.champs').keyup(function() {
                $('#message').text('');
                $('#message').fadeOut();
            });
            $('.champs').change(function() {
                $('#message').fadeOut();
                $('#message').text('');
            });
            /* Texte masquer ou afficher du lien des titres dans les boîtes de dialogues
             * de la page classeurs */

            $(document).on("click", "#toggle_titles", function() {
                if (toggled) {
                    $('#toggle_titles').text('(Afficher)');
                    toggled = false;
                } else {
                    $('#toggle_titles').text('(Masquer)');
                    toggled = true;
                }
                $('#hidden_titles').toggle();
            });
            $('#toggle_titles').click(function() {
                if (toggled) {
                    $('#toggle_titles').text('(Afficher)');
                    toggled = false;
                } else {
                    $('#toggle_titles').text('(Masquer)');
                    toggled = true;
                }
                $('#hidden_titles').toggle();
            });
            $("#hidden_images").hide();
            $("#isImage").click(function() {
                if (document.getElementById('isImage').checked) {
                    $("#image").val('NULL');
                } else {
                    $("#image").val('');
                }
                $(".noImage").toggle();
                $("#boxUpload").toggle();
                $("#hidden_images").hide();
            });
            $("#choisirImage").click(function() {
                $(".ou").toggle();
                $("#hidden_images").toggle();
                $("#boxUpload").toggle();
            });
            $("input[name=choixImage]").click(function() {
                var selected = $(this).val();
                $("#image").val(selected);
            });
            eventsUsers();
        });
}

function toggleDateFin() {
    $('#toggle_dateFyes').click(function() {
        var etat = $('#toggle_dateFyes').is(':checked');
        if (etat) {
            $('#hidden_dates').toggle();
            $('#isDateF').val('true');
        } else {
            $('#hidden_dates').toggle();
            $('#isDateF').val('false');
        }
    });
}
/**
 * Fonction qui met à jour le table des éléments après
 * ajout, modification ou suppression */
function rafraichirtable() {
    $.post("includes/tableau.php")
        .done(function(data) {
            $('.tables tbody').remove();
            $('.tables thead').after(data);
            iconesBoutons();
            $('#toggle_finished').prop('checked', false);
            $('#hide_ongoing').prop('checked', false);
        });
}

function rafraichirUsers() {
    $.post("includes/users.php")
        .done(function(data) {
            $('#s_users').replaceWith(data);
            eventsUsers();
        });
}

/* Transforme les liens ayant les classes mentionnées en bouton (apparence)
 * grâce au plugin jQueryUI */
function iconesBoutons() {
    // Bouton Imprimer grâce à jQuery UI
    $(".imprint").button({
        icons: {
            primary: "ui-icon-print"
        },
        text: false
    });
    // Bouton Finir grâce à jQuery UI
    $(".finir").button({
        icons: {
            primary: "ui-icon-check"
        },
        text: false
    });
    // Bouton Modifier grâce à jQuery UI
    $(".edit").button({
        icons: {
            primary: "ui-icon-wrench"
        },
        text: false
    });
    // Bouton Supprimer grâce à jQuery UI
    $(".del").button({
        icons: {
            primary: "ui-icon-close"
        },
        text: false
    });
    $(".extlink").button({
        icons: {
            primary: "ui-icon-extlink"
        },
        text: true
    });
    $(".info").button({
        icons: {
            primary: "ui-icon-info"
        },
        text: false
    });
}

/* Fonction utilisée pour rafraichir la liste des titres dans les boîtes
 * de dialogue de la page classeurs.php */
function refreshDialogTitles(nItem) {
    if (typeof(nItem) == 'undefined') {
        nItem = '';
    }
    var nbRep = $('#f_directory option:selected').text();
    $.post("includes/d_titres.php", { nbRep: nbRep, nItem: nItem })
        .done(function(data) {
            $('#hidden_titles').children().remove();
            $('#hidden_titles').append(data);
        });
}

/* Réaffiche la liste des titres de la page de gestion des titres
 * (avalez-le-crapaud/titres.php) */
function rafraichirTitres() {
    $.post("includes/titres.php")
        .done(function(data) {
            $('#s_titres').replaceWith(data);
            requetesTitres();
        });
}

/* Transforme l'élément ayant l'id picUpload en input file permettant
 * l'upload d'images grâce au plugin UploadFile
 * (js/plugins/jquery.uploadfile.min.js) */
function upload() {
    var settings = {
        url: "plugins/upload.php",
        multiple: false,
        maxFileSize: 200 * 1024,
        maxFileCount: 1,
        dragDrop: true,
        dragDropStr: "<span><b>Faites glisser et déposez les fichiers</b></span>",
        autoSubmit: true,
        abortStr: "Annuler",
        cancelStr: "Annuler",
        doneStr: "Terminer",
        deletelStr: "Supprimer",
        multiDragErrorStr: "Faire glisser-déposer plusieurs fichiers n'est pas autorisé.",
        extErrorStr: "n'est pas autorisé. Extensions autorisées:",
        sizeErrorStr: "n'est pas autorisé. Taille maximum:",
        fileName: "myfile",
        allowedTypes: "jpg,png",
        returnType: "json",
        onSuccess: function(files, data, xhr) {
            $("#status").children().remove();
            $("#status").append('<div style="color:green">Fichier ajouté</div>');
            $("#image").val((data));
            $('#boxUpload > *:first-child').hide();
            $('.noImage').hide();
            $('#isImage').parent().hide();
        },
        showDelete: true,
        deleteCallback: function(data, pd) {
            for (var i = 0; i < data.length; i++) {
                $.post("plugins/delete.php", { op: "delete", name: data[i] },
                    function(resp, textStatus, jqXHR) {
                        //Show Message
                        $("#status").children().remove();
                        $('#boxUpload > *:first-child').show();
                        $('.noImage').show();
                        $('#isImage').parent().show();
                        $("#image").val('');
                    });
            }
            pd.statusbar.hide(); //You choice to hide/not.
        }
    };
    uploadObj = $("#picUpload").uploadFile(settings);
}

// Requêtes AJAX de la page de gestion des titres
function requetesTitres() {
    $('.btnDisabled').prop('disabled', true);

    $('#s_titres').change(function() {
        var valOption = $('#s_titres option:selected').val();
        if (valOption !== '0') {
            $('#advertise').hide();
            $('#edit_titre').prop('disabled', false);
            $('#add_titre').hide();
        } else {
            $('#add_titre').show();
            $('#advertise').show();
            $('#edit_titre').prop('disabled', true);
            $('#c_edit_titre').hide();
            $('#c_edit_titre').val('');
            $('#edit_titre').fadeIn();
            $('#save_titre').hide();
        }
    });

    $('#edit_titre').click(function() {
        $('#edit_titre').hide();
        $('#c_edit_titre').fadeIn();
        $('#c_edit_titre').focus();
        $('#save_titre').fadeIn();
        $('#messageEdit').text('Le titre doit avoir au minimum 4 caractères');
    });
    $('#add_titre').click(function() {
        $('#edit_titre').fadeOut();
        $('#s_titres').fadeOut();
        $('#c_add_titre').fadeIn();
        $('#c_add_titre').focus();
        $('#save_add_titre').fadeIn();
        $('#messageAdd').text('Le titre doit avoir au minimum 4 caractères');
    });

    // Vérification dd l'ajout de titre (protection de chaîne vide)
    $('#c_add_titre').keyup(function() {
        var titre = $('#c_add_titre').val();
        var tailleTitre = titre.length;
        if (titre !== '' && tailleTitre >= 4) {
            $('#messageAdd').fadeOut();
            $('#save_add_titre').prop('disabled', false);
        } else {
            $('#save_add_titre').prop('disabled', true);
            $('#messageAdd').fadeIn();
            $('#messageAdd').text('Le titre doit avoir au minimum 4 caractères');
        }
    });

    // Vérification du champ modification du titre
    $('#c_edit_titre').keyup(function() {
        var titre = $('#c_edit_titre').val();
        var tailleTitre = titre.length;
        if (titre !== '' && tailleTitre >= 4) {
            $('#messageEdit').hide();
            $('#save_titre').prop('disabled', false);
        } else {
            $('#save_titre').prop('disabled', true);
            $('#messageEdit').show();
            $('#messageEdit').text('Le titre doit avoir au minimum 4 caractères');
        }
    });
    // Modification d'un titre
    $('#save_titre').click(function() {
        var numero = $('#s_titres option:selected').val();
        var newTitre = $('#c_edit_titre').val();
        var action = 'edit';
        $.ajax({
            type: 'POST',
            url: 'calls/titre.php',
            data: { action: action, numero: numero, newTitre: newTitre },
            dataType: "json",
            success: function(data) {
                if (data.reponse === "OK") {
                    $('#messageEdit').text("Le titre a été modifié avec succès !");
                    $('#messageEdit').show();
                    $('#c_edit_titre').val('');
                    $('#edit_titre').show();
                    $('#c_edit_titre').fadeOut();
                    $('#save_titre').fadeOut();
                    $('#edit_titre').prop('disabled', true);
                    rafraichirTitres();
                } else {
                    $('#messageEdit').text(data.reponse);
                    $('#messageEdit').hide();
                }
            },
            error: function(data) {
                $('#messageEdit').replaceWith(data);
            }
        });
    });
    // Ajout d'un titre
    $('#save_add_titre').click(function() {
        var newTitre = $('#c_add_titre').val();
        var action = 'add';
        $.ajax({
            type: 'POST',
            url: 'calls/titre.php',
            data: { action: action, newTitre: newTitre },
            dataType: "json",
            success: function(data) {
                if (data.reponse === "OK") {
                    $('#messageAdd').text("Le titre a été ajouté avec succès !");
                    $('#messageAdd').show();
                    $('#c_add_titre').val('');
                    $('#c_add_titre').fadeOut();
                    $('#save_add_titre').fadeOut();
                    $('#edit_titre').fadeIn();
                    $('#s_titres').fadeIn();
                    rafraichirTitres();
                } else {
                    $('#messageAdd').text(data.reponse);
                    $('#messageAdd').show();
                    $('#c_add_titre').focus();
                }
            },
            error: function() {
                alert('Erreur de l\'application, veuillez contactez le développeur de l\'application');
            }
        });
    });
}

function rafraichirNiveaux() {
    $.post("includes/niveauxUsers.php")
        .done(function(data) {
            $('#s_niveaux').replaceWith(data);
        });
}

// Events page Users
function eventsUsers() {
    $('.save_user').hide();
    $('.del_user').prop('disabled', true);
    $('#s_niveaux').hide();

    $('#s_users').change(function() {
        $('#messageUser').fadeOut();
        $('.save_user').hide();
        $('#s_niveaux').show();
        var nUser = $('#s_users option:selected').val();
        if (nUser !== '0') {
            $('.del_user').prop('disabled', false);
            $('.del_user').attr('id', nUser);
            $('.save_user').attr('id', nUser);
            $.post("includes/niveauxUsers.php", { nUser: nUser })
                .done(function(data) {
                    $('#s_niveaux').replaceWith(data);
                    $('.save_user').show();
                    $('#s_niveaux').change(function() {
                        var id = $(this).attr('id');
                        var niveauIni = $('#' + id).attr('data-niveauIni');
                        var niveauActuel = $('#' + id + ' option:selected').val();
                        var nUser = $('#s_users option:selected').val();
                        if (niveauIni !== niveauActuel) {
                            $('#messageUser').fadeOut();
                            $('.save_user').fadeIn();
                            $('.save_user').prop('disabled', false);
                            $('.del_user').attr('id', nUser);
                            $('.save_user').attr('id', nUser);
                        } else {
                            $('#messageUser').fadeIn();
                            $('.save_user').fadeOut();
                            $('.save_user').prop('disabled', true);
                        }
                    });
                });
        }
    });
    $('#s_niveaux').change(function() {
        $('#messageUser').fadeOut();
        var id = $(this).attr('id');
        var niveauIni = $('#' + id).attr('data-niveauIni');
        var niveauActuel = $('#' + id + ' option:selected').val();
        var nUser = $('#s_users option:selected').val();
        if (niveauIni !== niveauActuel) {
            $('#messageUser').fadeOut();
            $('.save_user').fadeIn();
            $('.save_user').prop('disabled', false);
            $('.del_user').attr('id', nUser);
            $('.save_user').attr('id', nUser);
        } else {
            $('#messageUser').fadeIn();
            $('.save_user').fadeOut();
            $('.save_user').prop('disabled', true);
        }
    });
}