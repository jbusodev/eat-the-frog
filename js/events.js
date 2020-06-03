// Menu Animations
$(document).ready(function() {
    /* ------------------------ Login Page Events ------------------------ */
    // Credentials Check
    checkCredentials();

    // Triggers login when pressing Enter in pwd field
    $('#password').keydown(function(event) {
        if (event.keyCode === 13) {
            $('#btnLogin').click();
        }
    });

    // Triggers login when pressing Enter in user field
    $('#user').keydown(function(event) {
        if (event.keyCode === 13) {
            $('#btnLogin').click();
        }
    });
    /* -------------------------- END Login Page Events */


    /* -------------------------- Tables Events ------------------- */

    /* --------- Tasks Page : Filter ----------------------------- */

    let tableCell = document.querySelector('td');

    // Default states - Filters
    if (!$(tableCell).hasClass('empty')) {
        $('#parent-toggle_finished').css("display", "inline-block"); // enables filters if users has tasks
    }
    $('#hide_ongoing').prop('checked', false); // unchecks hide ingoing
    $('#hide').hide();

    // Toggles finished tasks - Filter Checkbox
    $('#toggle_finished').click(function() {
        if ($(this).is(':checked')) {
            $('.finished').show();
            $('#hide').css("display", "inline-block");
        } else {
            $('.finished').hide();
            $('#hide').hide();
        }
    });

    // Hide ongoing tasks - Filter Checkbox
    $('#hide_ongoing').click(function() {
        $('.ongoing').toggle();
    });

    iconesBoutons();
    /* -------------------------- END Tables Events ------------------- */


    /* -------------------------- Dialog Events ------------------- */

    // datepicker Default Settings
    if (window.location.href.match('/taches|classeurs/g')) {
        setDefaultDatepicker();
    }

    // Mark a task as finished
    $(document).on("click", ".finir", function() {
        var nItem = $(this).attr('data-id');
        setDialog('end', nItem);
    });

    // Add a task
    $(document).on("click", ".add", function() {
        alert('TEST');
        setDialog('add');
    });

    // Edit a task
    $(document).on("click", ".edit", function() {
        var nItem = $(this).attr('data-id');
        setDialog('edit', nItem);
    });

    // Delete a task
    $(document).on("click", ".del", function() {
        var nItem = $(this).attr('data-id');
        setDialog('delete', nItem);
    });

    // Print function
    $(document).on("click", ".imprint", function() {
        var nItem = $(this).attr('data-id'); // item's ID is set in nItem
        image = $(this).attr('data-image'); // item's image is set in image
        console.clear();
        console.log(image + ' | ' + nItem);
        if (nItem) {
            setDialog('print', nItem);
        } else {
            window.print();
        }
    });
    /**
     * Titles list refreshed when number of directories changed
     * in classeurs.php - dialog
     * */
    $(document).on("change", "#f_directory", function() {
        var nItem = $('#item').val();
        refreshDialogTitles(nItem);
    });

    // Toggle titles container
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



    /* -------------------------- END Dialog Events -------------- */


    /* -------------------------- Title Management Page -  Events -------------- */
    requetesTitres();
    $('#advertise').show();
    /* -------------------------- END Title Management Page - Events -------------- */


    /* -------------------------- Users Page - Events -------------- */
    eventsUsers();
    $('.save_user').click(function() {
        var nItem = $(this).attr('id');
        var action = 'edit';
        var newUtilisateur = new Object();
        newUtilisateur.niveau = $('#s_niveaux option:selected').val();
        $.ajax({
            type: 'POST',
            url: 'calls/user.php',
            data: { action: action, nItem: nItem, newUtilisateur: newUtilisateur },
            dataType: "json",
            success: function(data) {
                if (data.reponse === 'OK') {
                    $('#s_niveaux[value="0"]').prop('selected', true);
                    $('#messageUser').show();
                    $('#messageUser').text("Les modifications ont été enregistrées");
                    $('#s_users option[value="0"]').prop('selected', true);
                    $('.del_user').prop('disabled', true);
                    eventsUsers();
                    $('#s_niveaux').hide();
                    $('.save_user').prop('disabled', true);
                } else {
                    $('#messageUser').show();
                    $('#messageUser').text('');
                    $('#messageUser').append(data.reponse);
                }
            },
            error: function(data) {
                $('#messageUser').replaceWith(data);
            }
        });
    });
    /* -------------------------- END Users Page - Events-------------- */


    /* -------------------------- General Events -------------- */

    // Mobile menu Toggle
    $("#menu_toggle img").click(function() {
        $("#menu").slideToggle();
    });

    // Menu toggle
    $(".liens").click(function() {
        $("#menu ul").toggle();
    });
});

/* -------------------------- END General Events -------------- */