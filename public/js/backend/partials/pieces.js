$(document).ready(function() {    
    /**
     * Función para mostrar la lista de piezas.
     */
    $('#pieces-list').ready(function() {
        $(this).loadPieces(false);
    });
    
    /**
     * Función para añadir piezas con el formulario y mostrarlos a la izquierda.
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
                    $('#add-form ' + result.input).css('border', '1px solid #F00');
                }
                else {
                    // Si no reseteamos el formulario y recargamos la lista de piezas actualizada
                    $('#add-form input').css('border', '1px solid #6C8B81'); 
                    $('#add-form').trigger('reset');
                    $('#pieces-list').loadPieces(true);
                }
                // Y mostramos el resultado de la operación por pantalla
                alert(result.message);
            },
            error:function() { 
                // Si falla la petición o salta alguna excepción se notifica por pantalla
                alert('Imposible registrar nuevas piezas en estos momentos. Inténtenlo de nuevo más tarde.');
            }
        });
    }));
    
    /**
     * Función para modificar piezas.
     */
    $('#edit-delete-form').on('submit',(function(e) {
        // Cancelamos el submit
        e.preventDefault();
        // Obtenemos el ID de la pieza seleccionada
        var pieceId = $('#piece-id').val();
        // Obtenemos el action del formnulario
        var action = $('#edit-delete-form').attr('action').replace(':PIECE_ID', pieceId);
        // Obtenemos el nombre de la pieza seleccionada
        var pieceName = $('#edit-delete-form').data('pieceName');
        // Mandamos la petición por AJAX
        $.ajax({
            url: action,
            data:new FormData(this),
            type: "POST",
            contentType: false,
            cache: false,
            processData:false,
            success:function(result) {
                if (result.input !== '') {
                    // Si hay campos con errores los resaltamos
                    $('#edit-delete-form ' + result.input).css('border', '1px solid #F00');
                }
                else {
                    // Si no reseteamos el formulario, cambiamos de pestaña y recargamos la lista de piezas actualizada
                    $('#edit-delete-form input').css('border', '1px solid #6C8B81'); 
                    $('#edit-delete-form').trigger('reset');
                    $('#pieces-list').loadPieces(false);
                    $('#forms').clickAddTab();                
                }
                // Y mostramos el resultado de la operación por pantalla
                alert(result.message);
            },
            error:function() { 
                // Si falla la petición o salta alguna excepción lo notificamos por pantalla
                alert('Imposible editar la pieza "' + pieceName + '" seleccionada en estos momentos. Inténtenlo de nuevo más tarde.');
            }
        });
    }));
    
    /**
     * Función para activar la pestaña de Editar/Eliminar al hacer click en un pieza de la lista de la izquierda.
     */
    $('#pieces-list').click(function(e) {
        // Obtenemos la ID de la pieza seleccionada
        var pieceId = $(e.target).parent().attr('id');
        // Se la pasamos al formulario de Editar/Eliminar
        $('#edit-delete-form').data('pieceName', pieceId);
        var currentId = '#' + pieceId;
        
        // Modificamos el CSS de la lista de piezas para resaltar la seleccionada
        if (pieceId !== 'list') {
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
        
        // Obtenemos el ID del elemento contenedor de la pieza seleccionada
        var parentId = $(currentId).parent().attr('id');
        // Si el contenedor es la lista de piezas
        if (parentId === 'pieces-list') {
            // Cancelamos el evento del enlace
            e.preventDefault();
            
            // Cargamos los datos de la pieza seleccionada
            $.get('/admin/piezas/'+pieceId, function(piece) {
                // Cambiamos el CSS y los atributos para activar el tab Editar/Eliminar
                $('#forms').clickEditDeleteTab();                
                // Cargamos los datos de la pieza en el formulario
                $('#edit-delete-form #name').val(piece.name);
                $('#edit-delete-form #piece-id').val(piece.id);
                $('#edit-delete-form #upload-file').val(uploadDir + '/' + piece.image);
                $('#chosen-img').attr('src', uploadDir + '/' + piece.image + '?' + Math.random());
            }).fail(function() {
                // Si falla la petición o salta alguna excepción lo notificamos por pantalla
                alert('Imposible obtener los datos de la pieza seleccionada en estos momentos');
            });
        }
    });   
    
    /**
     * Función para eliminar piezas.
     */
    $('#delete-btn').click(function() {
        // Obtenemos la ID de la pieza seleccionada
        var pieceId = $('#piece-id').val();
        // Obtenemos el action del formulario
        var action = $('#delete-form').attr('action').replace(':PIECE_ID', pieceId);
        // Obtenemos el nombre de la pieza seleccionada
        var pieceName = $('#edit-delete-form').data('pieceName');
        // Confirmamos la operación
        if (confirm('¿Está seguro de que quiere eliminar la pieza "' + pieceName + '"?')) {
            // Enviamos la petición por AJAX
            $.post(action, $('#delete-form').serialize(), function(result) {
                // Confirmamos el resultado de la operación
                alert(result);
                // Reseteamos los formularios, cambiamos de pestaña y recargamos la lista de piezas actualizada
                $('#edit-delete-form').trigger('reset');
                $('#delete-form').trigger('reset');
                $('#edit-btn').attr('disabled', true);
                $('#delete-btn').attr('disabled', true);
                $('#edit-delete-form #upload-btn').attr('disabled', true);
                $('#pieces-list').fadeOut('slow', function() {
                $('#pieces-list').loadPieces(false);
                });
            }).fail(function() {
                // Si falla la petición o salta alguna excepción lo notificamos por pantalla
                alert('Imposible eliminar la pieza "' + pieceName + '" seleccionada en estos momentos. Inténtenlo de nuevo más tarde.');
            });
        }
    });
    
    /**
     * Función que cancela el Editar/Eliminar y vuelve a la pestaña de Añadir.
     */
    $("#edit-delete-form #cancel-btn").click(function() {
        // Reseteamos los formularios
        $('#add-form').trigger('reset');
        $('#edit-delete-form').trigger('reset');
        // Ocultamos la imagen de la pieza seleccionada
        $('#chosen-img').remove();
        // Y cambiamos a la pestaña Añadir
        $('#forms').clickAddTab();   
    });
    
    /**
     * Plugin encargado de cargar la lista de piezas.
     * @param {boolean} reload
     * @returns
     */
    $.fn.loadPieces = function(reload) {
        // Mandamos la petición de listar piezas por AJAX
        $.get('/admin/piezas/listar', function(pieces) {
            var size = pieces.length;
            var piece;
            var html = '';
            var pieceTag;
            
            // Imprimimos la lista de piezas
            $('#pieces-list').fadeOut('slow', function(){
                if (size > 0) {
                    var cont = 0;

                    for (cont = 0; cont < size; cont++) {
                        piece = pieces[cont];
                        pieceTag = '<a title="Pieza ' + piece.name + '" href="#!" class="thumbnail" id="' + piece.name + '"><img title="Pieza ' + piece.name + '" src="/_data/piezas/' + piece.image + '?' + Math.random() + '" alt="Pieza ' + piece.name + '" width="105" height="105" /><span>' + piece.name + '</span></a>';
                        html += pieceTag;
                    }

                    $('#pieces-list').html(html);
                    // Si reload es true mandamos a visualizar la última pieza registrada
                    if (reload) {
                        var lastId = '#'+piece.name;
                        $(location).attr('href',lastId); 
                    }
                }
                else {
                    // si no hay piezas mostramos un mensaje
                    $('#pieces-list').html('<h3 class="alert alert-danger text-center">Aún no hay piezas registradas<br /><small class="alert-danger">Añada piezas para poder visualizarlas aquí</small><h3>');
                }

                $('#pieces-list').fadeIn('slow');                
            });
        }).fail(function() {
            // Si falla la petición o salta alguna excepción lo notificamos por pantalla
            alert('Imposible obtener la lista de piezas en estos momentos');
        });
    }
});