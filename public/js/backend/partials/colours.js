$(document).ready(function() {    
    /**
     * Función para mostrar la lista de colores.
     */
    $('#colours-list').ready(function() {
        $(this).loadColours(false);
    });
    
    /**
     * Función para añadir colores
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
                // Si hay campos con errores los resaltamos
                if (result.input !== '') {
                    $('#add-form ' + result.input).css('border', '1px solid #F00');
                }
                else {
                    // Si no, reseteamos el formulario
                    $('#add-form input').css('border', '1px solid #6C8B81'); 
                    $('#add-form').trigger('reset');
                    // Cargamos la lista de colores actualizada
                    $('#colours-list').loadColours(true);
                }
                // Y mostramos el resultado de la operación por pantalla
                alert(result.message);
            },
            error:function() { 
                // Si la petición falla o hay alguna excepción se muestra un mensaje de error por pantalla
                alert('Imposible registrar nuevos colores en estos momentos. Inténtenlo de nuevo más tarde.');
            }
        });
    }));
    
    /**
     * Función para editar colores.
     */
    $('#edit-delete-form').on('submit',(function(e) {
        // Cancelamos el submit
        e.preventDefault();
        // Recogemos el ID del color seleccionado
        var colourId = $('#colour-id').val();
        // Recogemos el action del formulario
        var action = $('#edit-delete-form').attr('action').replace(':COLOUR_ID', colourId);
        // Recogemos el nombre del color seleccionado
        var colourName = $('#edit-delete-form').data('colourName');
        // Mandamos la petición por AJAX
        $.ajax({
            url: action,
            data:new FormData(this),
            type: "POST",
            contentType: false,
            cache: false,
            processData:false,
            success:function(result) {
                // Si hay campos con errores los resaltamos
                if (result.input !== '') {
                    $('#edit-delete-form ' + result.input).css('border', '1px solid #F00');
                }
                else {
                    // Si no resteamos el formulario, cambiamos de pestaña y cargamos la lista de colores actualizada
                    $('#edit-delete-form input').css('border', '1px solid #6C8B81'); 
                    $('#edit-delete-form').trigger('reset');
                    $('#forms').clickAddTab();                
                    $('#colours-list').loadColours(false);
                }
                // y mostramos el resultado por pantalla
                alert(result.message);
            },
            error:function() { 
                // si falla la petición o salta alguna excepción mostramos el error por pantalla
                alert('Imposible editar el color "' + colourName + '" seleccionado en estos momentos. Inténtenlo de nuevo más tarde.');
            }
        });
    }));
    
    /**
     * Función para activar la pestaña de Editar/Eliminar al hacer click en un color de la lista de la izquierda.
     */
    $('#colours-list').click(function(e) {
        // Obtenemos el ID del color seleccionado
        var colourId = $(e.target).parent().attr('id');
        // Se lo pasamos al formulario de Editar/Eliminar
        $('#edit-delete-form').data('colourName', colourId);
        var currentId = '#' + colourId;
        
        // Modificamos el CSS de la lista de colores para resaltar el color seleccionado
        if (colourId !== 'list') {
            $('.thumbnail').css({
                'box-shadow' : 'none',
                'border'     : '1px solid #DDD'
            });
            
            $('.thumbnail img').css({
                'opacity'    : '1'
            });
            
            $(currentId).css({
                'box-shadow' : '0 0 10px #000',
                'border'     : '1px solid #000'
            });
            
            $(currentId + ' img').css({
                'opacity'    : '0.50'
            });
        }
        
        // Obtenemos el ID del elemento contenedor del color
        var parentId = $(currentId).parent().attr('id');
        // Si el contenedor es la lista de colores
        if (parentId === 'colours-list') {
            // Cancelamos el evento del enlace
            e.preventDefault();
            
            // Cargamos los datos del color seleccionado por AJAX
            $.get('/admin/colores/'+colourId, function(colour) {
                // Cambiamos el CSS y los atributos para activar el tab Editar/Eliminar
                $('#forms').clickEditDeleteTab();                
                // Y cargamos los datos del color en el formulario
                $('#edit-delete-form #name').val(colour.name);
                $('#edit-delete-form #colour-id').val(colour.id);
                $('#edit-delete-form #upload-file').val(uploadDir + '/' + colour.image);
                $('#chosen-img').attr('src', uploadDir + '/' + colour.image + '?' + Math.random());
            }).fail(function() {
                // Si falla la petición o salta alguna excepción mostramos el error por pantalla
                alert('Imposible obtener los datos del color seleccionado en estos momentos');
            });
        }
    });   
    
    /**
     * Función que elimina un color seleccionado.
     */
    $('#delete-btn').click(function() {
        // Obtenemos el ID del color seleccionado
        var colourId = $('#colour-id').val();
        // Obtenemos el action del formulario
        var action = $('#delete-form').attr('action').replace(':COLOUR_ID', colourId);
        // Obtenemos el nombre del color seleccionado
        var colourName = $('#edit-delete-form').data('colourName');
        
        // Confirmamos la operación
        if (confirm('¿Está seguro de que quiere eliminar el color "' + colourName + '"?')) {
            // Y enviamos la petición por AJAX
            $.post(action, $('#delete-form').serialize(), function(result) {
                // Mostramos el mensaje de confirmación por pantalla
                alert(result);
                // Y reseteamos el formulario, cambiamos de pestaña y recargamos la lista de colores actualizada
                $('#edit-delete-form').trigger('reset');
                $('#delete-form').trigger('reset');
                $('#edit-btn').attr('disabled', true);
                $('#delete-btn').attr('disabled', true);
                $('#edit-delete-form #upload-btn').attr('disabled', true);
                $('#colours-list').fadeOut('slow', function() {
                $('#colours-list').loadColours(false);
                });
            }).fail(function() {
                // Si la petición falla o salta alguna excepción mostramos el error por pantalla
                alert('Imposible eliminar el color "' + colourName + '" seleccionado en estos momentos. Inténtenlo de nuevo más tarde.');
            });
        }
    });
    
    /**
     * Función para cancelar la opción de Editar/Eliminar y volver a la pestaña de Añadir.
     */
    $("#edit-delete-form #cancel-btn").click(function() {
        // Resetamos los formularios
        $('#add-form').trigger('reset');
        $('#edit-delete-form').trigger('reset');
        // Ocultamos la imagen del color elegido
        $('#chosen-img').remove();
        // Y cambiamos de pestaña
        $('#forms').clickAddTab();   
    });
    
    /**
     * Plugin encargado de listar todos los colores a la izquierda.
     * @param {boolean} reload
     * @returns
     */
    $.fn.loadColours = function(reload) {
        // Envía la peticiókn por AJAX para mostrar la lista de colores
        $.get('/admin/colores/listar', function(colours) {
            var size = colours.length;
            var colour;
            var html = '';
            var colourTag;
            // Imprime la lista de colores
            $('#colours-list').fadeOut('slow', function(){
                if (size > 0) {
                    var cont = 0;

                    for (cont = 0; cont < size; cont++) {
                        colour = colours[cont];
                        colourTag = '<a title="Color ' + colour.name + '" href="#!" class="thumbnail" id="' + colour.name + '"><img title="Color ' + colour.name + '" src="/_data/colores/' + colour.image + '?' + Math.random() + '" alt="Color ' + colour.name + '" width="105" height="105" /><span>' + colour.name + '</span></a>';
                        html += colourTag;
                    }

                    $('#colours-list').html(html);
                    
                    // Si reload es true se manda visualizar el último color registrado
                    if (reload) {
                        var lastId = '#'+colour.name;
                        $(location).attr('href',lastId); 
                    }
                }
                else {
                    // Si no hay colores se muestra un aviso
                    $('#colours-list').html('<h3 class="alert alert-danger text-center">Aún no hay colores registrados<br /><small class="alert-danger">Añada colores a la paleta para poder visualizarlos aquí</small><h3>');
                }

                $('#colours-list').fadeIn('slow');                
            });
        }).fail(function() {
            // Si falla la petición o salta alguna excepción se avisa por pantalla
            alert('Imposible obtener la lista de colores en estos momentos');
        });
    }
});