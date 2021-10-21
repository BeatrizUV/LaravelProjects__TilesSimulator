$(document).ready(function() {    
    /**
     * Función para mostrar la lista de distribuidores.
     */
    $('#showrooms-list').ready(function() {
        $(this).loadShowrooms(false);
    });
    
    /**
     * Función para añadir distribuidores con el formulario y mostrarlos a la izquierda
     */
    $('#add-form').on('submit',(function(e) {
        // Cancelamos el submit
        e.preventDefault();
        // Enviamos la petición por AJAX
        $.ajax({
            url: $('#add-form').action,
            data:new FormData(this),
            type: "POST",
            contentType: false,
            cache: false,
            processData:false,
            success:function(result) {
                if (result.input !== '') {
                    // Si hay campos con errores los resaltamos
                    var errors = result.input.split(',');                      
                    for (var cont = 0; cont < errors.length; cont++) {
                        $('#add-form ' + errors[cont]).css('border', '1px solid #F00'); 
                    }
                }
                else {
                    // Si no, reseteamos los formularios y recargamos la lista de distribuidores actualizada
                    $('#add-form input').css('border', '1px solid #6C8B81'); 
                    $('#showrooms-list').loadShowrooms(true);
                    $('#add-form').trigger('reset');
                }
                // Y mostramos el resultado de la operación por pantalla
                alert(result.message);
            },
            error:function() { 
                // Si falla la petición o salta alguna excepción lo notificamos por pantalla
                alert('Imposible registrar nuevos distribuidores en estos momentos. Inténtenlo de nuevo más tarde.');
            }
        });
    }));
    
    /**
     * Función para editar distribuidores.
     */
    $('#edit-delete-form').on('submit',(function(e) {
        // Cancelamos el submit
        e.preventDefault();
        // Obtenemos el ID del distribuidor seleccionado
        var showroomId = $('#showroom-id').val();
        // Obtenemos el action del formulario
        var action = $('#edit-delete-form').attr('action').replace(':SHOWROOM_ID', showroomId);
        // Obtenemos el nombre del distribuidor
        var showroomName = $('#edit-delete-form').data('showroomName');
        // Enviamos la petición por AJAX
        $.ajax({
            url: action,
            data:new FormData(this),
            type: "POST",
            contentType: false,
            cache: false,
            processData:false,
            success:function(result) {
                if (result.input !== '') {
                    // si hay campos con errores los resaltamos
                    var errors = result.input.split(',');                      
                    for (var cont = 0; cont < errors.length; cont++) {
                        $('#edit-delete-form ' + errors[cont]).css('border', '1px solid #F00'); 
                    }
                }
                else {
                    // Si no, reseteamos los formularios, cambiamos de pestaña y recargamos la lista de distribuidores actualizada
                    $('#edit-delete-form input').css('border', '1px solid #6C8B81'); 
                    $('#showrooms-list').loadShowrooms(false);
                    $('#edit-delete-form').trigger('reset');
                    $('#forms').clickAddTab();                
                }
                // Y mostramos el resultado de la operación por pantalla
                alert(result.message);
            },
            error:function() { 
                // Si falla la petición o salta alguna excepción lo notificamos por pantalla
                alert('Imposible editar la pieza "' + showroomName + '" seleccionada en estos momentos. Inténtenlo de nuevo más tarde.');
            }
        });
    }));
   
    /**
     * Función para activar la pestaña de editar/eliminar al hacer click en un distribuidor de la lista de la izquierda.
     */
    $("#showrooms-list").on('click', 'table > tbody > tr', function(){
        // Obtenemos el ID del distribuidor seleccionado
        var showroomId = $(this).attr('id');
        var currentId = '#' + showroomId;
        
        // Resaltamos el distribuidor seleccionado de la lista
        $('table tbody tr').removeClass('alert btn-warning');
        $(currentId).addClass('alert btn-warning');       
        $(currentId).css('color', '#333');
            
        // Cargamos los datos del distribuidor seleccionado por AJAX
        $.get('/admin/distribuidores/'+showroomId, function(showroom) {
            // Cambiamos el CSS y los atributos para activar el tab Editar/Eliminar
            $('#forms').clickEditDeleteTab();
            $('#edit-delete-form').data('showroomName', showroom.company);

            // Y cargamos los datos del distribuidor en el formulario
            $('#edit-delete-form #company').val(showroom.company);
            $('#edit-delete-form #email').val(showroom.email);
            $('#edit-delete-form #telephone').val(showroom.telephone);
            $('#edit-delete-form #website').val(showroom.website);
            $('#edit-delete-form #lang').val(showroom.lang).prop('selected', true);
            $('#edit-delete-form #upload-file').val(uploadDir + '/' + showroom.logo);
            $('#chosen-img').attr('src', uploadDir + '/' + showroom.logo + '?' + Math.random());
            $('#edit-delete-form #showroom-id').val(showroom.id);
        }).fail(function() {
            // Si falla la petición o salta alguna excepción lo notificamos por pantalla
            alert('Imposible obtener los datos del distribuidor seleccionado');
        });
    });  
    
    /**
     * Función para eliminar distribuidores.
     */
    $('#delete-btn').click(function() {
        // Obtenemos el ID del distribuidor seleccionado
        var showroomId = $('#showroom-id').val();
        // Obtenemos el action del formulario
        var action = $('#delete-form').attr('action').replace(':SHOWROOM_ID', showroomId);
        // Obtenemos el nombre del distribuidor
        var showroomName = $('#edit-delete-form').data('showroomName');
        // Confirmamos la operación
        if (confirm('¿Está seguro de que quiere eliminar el distribuidor "' + showroomName + '"?')) {
            // Enviamos la petición por AJAX
            $.post(action, $('#delete-form').serialize(), function(result) {
                // Confirmamos el resultado de la operación por pantalla
                alert(result);
                // Reseteamos los formularios, cambiamos de pestaña y recargamos la lista de distribuidores actualizada
                $('#edit-delete-form').trigger('reset');
                $('#delete-form').trigger('reset');
                $('#edit-btn').attr('disabled', true);
                $('#delete-btn').attr('disabled', true);
                $('#edit-delete-form #upload-btn').attr('disabled', true);
                $('#showrooms-list').fadeOut('slow', function() {
                $('#showrooms-list').loadShowrooms(false);
                });
            }).fail(function() {
                // Si falla la petición o salta alguna exepción lo notificamos por pantalla
                alert('Imposible eliminar el distribuidor "' + showroomName + '" seleccionada en estos momentos. Inténtenlo de nuevo más tarde.');
            });
        }
    });
    
    /**
     * Función que resetea el formulario de Editar/Eliminar y cambia a la pestaña Añadir.
     */
    $("#edit-delete-form #cancel-btn").click(function() {
        $('#add-form').trigger('reset');
        $('#edit-delete-form').trigger('reset');
        $('#chosen-img').remove();
        $('#forms').clickAddTab();   
    });
    
    /**
     * Plugin para listar todos los distribuidores a la izquierda.
     * @param {boolean} reload
     * @returns
     */
    $.fn.loadShowrooms = function(reload) {
        // Mandamos la petición de listar distribuidores por AJAX
        $.get('/admin/distribuidores/listar', function(showrooms) {
            var size = showrooms.length;
            var showroom;
            var html = '';
            var showroomTag;
            
            // Imprimimos la lista de distribuidores por pantalla
            $('#showrooms-list').fadeOut('slow', function(){
                if (size > 0) {
                    var cont = 0;

                    html += '<table class="table table-condensed table-bordered szd-table-shadow"><thead><tr class="alert-success"><th>LOGOTIPO</th><th>EMPRESA</th><th>DATOS DE CONTACTO</th><th>IDIOMA</th></tr></thead><tbody>';
                    for (cont = 0; cont < size; cont++) {
                        showroom = showrooms[cont];
                        showroomTag = '<tr id="' + showroom.id + '">\n\
                                            <td><img title="Distribuidor ' + showroom.company + '" src="/_data/distribuidores/' + showroom.logo + '?' + Math.random() + '" alt="Distribuidor ' + showroom.company + '" width="50" height="50" /></td>\n\
                                            <td>' + showroom.company + '</td>\n\
                                            <td>' + showroom.telephone + '<br />' + showroom.email + '<br />' + showroom.website + '</td>\n\
                                            <td><img title="' + showroom.lang + '" src="/images/lang/' + showroom.lang + '.png" alt="' + showroom.lang + '" width="25" height="15" /></td>\n\
                                       </tr>';
                        html += showroomTag;
                    }
                    html += '</tbody></table>';

                    $('#showrooms-list').html(html);
                    // si reload es true mandamos visualizar el último distribuidor registrado
                    if (reload) {
                        var lastId = '#'+showroom.id;
                        $(location).attr('href',lastId); 
                    }
                }
                else {
                    // Si no hay distribuidores se notifica por pantalla
                    $('#showrooms-list').html('<h3 class="alert alert-danger text-center">Aún no hay distribuidores registrados<br /><small class="alert-danger">Añada distribuidores para poder visualizarlos aquí</small><h3>');
                }

                $('#showrooms-list').fadeIn('slow');                
            });
        }).fail(function() {
            // Si falla la petición o salta alguna excepción se notifica por pantalla
            alert('Imposible obtener la lista de distribuidores');
        });
    }
});