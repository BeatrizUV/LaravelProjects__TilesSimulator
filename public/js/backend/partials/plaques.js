$(document).ready(function() {            
    /**
     * Función para mostrar la lista de placas.
     */
    $('#plaques-list').ready(function() {
        $(this).loadPlaques(false);
    });
    
    /**
     * Función para resetear los formularios y cambiar a la pestaña Añadir.
     */
    $('[data-toggle=tab]').click(function(e){
        if (this.id == 'add-tab') {
            $('#edit-delete-form').trigger('reset');
            $('.add-tab').clickAddTabPlaque(false);
            $('.thumbnail').css({
                    'box-shadow' : 'none',
                    'border'     : '1px solid #DDD'
            });

            $('.thumbnail img').css({
                'opacity'    : '1'
            });
            $('#edit-delete-tab a').attr('href', '#!');
        }
        else {
            $('#add-form').trigger('reset');
            $('.edit-delete-tab').clickEditDeleteTabPlaque(false);            
        }
        
        $('.nodes').data('cont', 0);
    });
    
    /**
     * Función para mostrar la lista de piezas.
     */
    $('.add-pieces .add-list').ready(function() {
        // Cargamos la lista de piezas
        $(this).loadPieces('add');
        // Cargamos la lista de colores
        $('.add-colours').loadColours('add');
        // Y establecemos el contador de piezas a 0
        $('.nodes').data('cont', 0);
    });
    
    /**
     * Función para añadir piezas a la placa seleccionada.
     */
    $('.list').on('click', 'a', function(e) {
        // Incrementamos el contador de piezas
        var cont = $('.nodes').data('cont') + 1;        
        
        // Establecemos el formulario activo
        var form = '#edit-delete-form .nodes';          
        var parentTag = $(e.target).parent();
        if ($(parentTag).parent().hasClass('add-list')) {
            form = '#add-form .nodes';
        }
        
        // Añadimos la pieza al formulario activo
        var html = $('.nodes').appendPieces('add', this.id, cont, null);        
        $(form).append(html);
        
        // Almacenamos el contador de piezas
        $('.nodes').data('cont', cont);
        // Y el número actual de la pieza añadida
        $('.nodes').data('current', cont);
    });
    
    /**
     * Función para establecer el color de la pieza recién añadida
     */
    $('.colours').on('click', 'a', function(e) {
        // Obtenemos el ID del color seleccionado
        var colourId = '#' + this.id;
        // Obtenemos el nombre del color seleccionado
        var colourName = $(colourId).attr('title');
        // Obtenemos el src de la imagen del color seleccionado
        var colourSrc = $(colourId + ' img').attr('src');
        
        // Establecemos el formulario activo
        var form = '#edit-delete-form';
        var parentTag = $(e.target).parent();        
        if ($(parentTag).parent().hasClass('add-colours')) {
            form = '#add-form';
        }
        
        // Establecemos las IDs del color y la imagen de la nueva pieza
        var colourPieceImgId = form + ' #piece_' + $('.nodes').data('current') + '_colour_img';
        var colourPieceId = form + ' #piece_' + $('.nodes').data('current') + '_colour';
        
        // Y cargamos el color en la pieza
        $(colourPieceImgId).attr('src', colourSrc);
        $(colourPieceImgId).attr('title', colourName);
        $(colourPieceImgId).attr('alt', colourName);
        $(colourPieceId).val(this.id);
    });
    
    /**
     * Función que activa la pieza sobre la que asignar un color.
     */
    $('.nodes').on('click', '.thumb', function() {
        var id = this.id.replace('thumb_', '');
        $('.nodes').data('current', id);
    });
    
    /**
     * Función que elimina o bloquea una pieza añadida a la placa.
     */
    $('.nodes').on('click', 'button', function() {
        if (this.id.search('_delete') != -1) {
            // Si el botón presionado es para eliminar borramos la pieza y todos sus campos ocultos
            var pieceInfoId = '#' + this.id.replace('_delete', '_info');
            $(pieceInfoId).remove(pieceInfoId);
        }
        else {
            // Si el botón es para bloquear alternamos el valor del campo oculto correspondiente entre 0 y 1
            var pieceLockId = '#' + this.id.replace('_lock', '_locked');
            var lockButton = '#' + this.id;
            if ($(pieceLockId).val() == 0) {
                // Pieza bloqueada
                $(pieceLockId).val(1);
                $(lockButton).removeClass('btn-primary');
                $(lockButton).addClass('btn-warning');
            }
            else {
                // Pieza desbloqueada
                $(pieceLockId).val(0);
                $(lockButton).removeClass('btn-warning');
                $(lockButton).addClass('btn-primary');
            }
        }
    });
    
    /**
     * Función para añadir placas con el formulario y mostrarlos a la izquierda.
     */
    $('#add-form').on('submit',(function(e) {
        // Cancelamos el submit
        e.preventDefault();
        // Obtenemos el contador total de piezas
        var cont = $('.nodes').data('cont');
        // Y asignamos su valor a un campo oculto del formulario
        $('#add-form #pieces').val(cont);
        // Enviamos la petición por AJAX
        $.ajax({
            url: $('#add-form').action,
            data:new FormData(this),
            type: "POST",
            contentType: false,
            cache: false,
            processData:false,
            success:function(result) {
                if (result.input != '') {
                    // Si hay campos con errores los resaltamos
                    var errors = result.input.split(',');                      
                    for (var cont = 0; cont < errors.length; cont++) {
                        $('#add-form ' + errors[cont]).css('border', '1px solid #F00'); 
                    }
                }
                else {
                    // Si no, resetamos los formularios, cambiamos de pestaña y recargamos la lista de placas actualizada
                    $('#add-form input').css('border', '1px solid #6C8B81'); 
                    $('#add-form .nodes').empty(); 
                    $('#plaques-list').loadPlaques(true);
                    $('#add-form').trigger('reset');
                }
                // Y mostramos el resultado de la operación por pantalla
                alert(result.message);
            },
            error:function() { 
                // Si falla la petición o salta alguna excepción lo notificamos por pantalla
                alert('Imposible registrar nuevas placas en estos momentos. Inténtenlo de nuevo más tarde.');
            }
        });
    }));
    
    /**
     * Función que resetea los formularios de Editar/Eliminar y cambia a la pestaña Añadir.
     */
    $("#edit-delete-form #cancel-btn").click(function() {
        // Eliminamos los nodos de las piezas del formulario de Añadir
        $('#add-form .nodes').empty(); 
        // Reseteamos el formulario de Añadir
        $('#add-form').trigger('reset');
        // Reseteamos el formulario de Editar/Eliminar
        $('#edit-delete-form').trigger('reset');
        // Eliminamos los nodos de las piezas del formnulario de Editar/Eliminar
        $('#edit-delete-form .nodes').empty(); 
        // Ocultamos la imagen de la placa
        $('#chosen-img').remove();
        // Y cambiamos a la pestaña Añadir
        $('#forms').clickAddTabPlaque(true);   
    });
    
    /**
     * Función para editar o eliminar placas con el formulario y mostrarlos a la izquierda.
     */
    $('#edit-delete-form').on('submit',(function(e) {
        // Cancelamos el submit
        e.preventDefault();
        // Obtenemos el contador total de piezas
        var cont = $('.nodes').data('cont');
        // y lo asignamos a un campo oculto
        $('#edit-delete-form #pieces').val(cont);
        // Obtenemos el ID de la placa seleccionada
        var plaqueId = $('#plaque-id').val();
        // Obtenemos el action del formulario
        var action = $('#edit-delete-form').attr('action').replace(':PLAQUE_ID', plaqueId);
        // Obtenemos el nombre de la placa seleccionada
        var plaqueName = $('#edit-delete-form').data('plaqueName');
        // Enviamos la petición por AJAX
        $.ajax({
            url: action,
            data:new FormData(this),
            type: "POST",
            contentType: false,
            cache: false,
            processData:false,
            success:function(result) {
                if (result.input != '') {
                    // Si hay campos con errores los resaltamos
                    var errors = result.input.split(',');                      
                    for (var cont = 0; cont < errors.length; cont++) {
                        $('#edit-delete-form ' + errors[cont]).css('border', '1px solid #F00'); 
                    }
                }
                else {
                    // Si no, reseteamos los formularios, cambiamos de pestaña y recargamos la lista de placas actualizada
                    $('#edit-delete-form input').css('border', '1px solid #6C8B81'); 
                    $('#plaques-list').loadPlaques(false);
                    $('#edit-delete-form').trigger('reset');
                    $('#edit-delete-form .nodes').empty(); 
                    $('#chosen-img').remove();
                    $('#forms').clickAddTabPlaque(true);                
                }
                // Y mostramos el resultado de la operación por pantalla
                alert(result.message);
            },
            error:function() { 
                // Si falla la petición o salta alguna excepción lo notificamos por pantalla
                alert('Imposible editar la placa "' + plaqueName + '" seleccionada en estos momentos. Inténtenlo de nuevo más tarde.');
            }
        });
    }));
    
    /**
     * Función para activar la pestaña de Editar/Eliminar al hacer click en una placa de la lista de la izquierda.
     */
    $('#plaques-list').click(function(e) {
        // Cargamos la lista de piezas por AJAX
        $('.edit-delete-pieces').loadPieces('edit-delete');
        // Cargamos la lista de placas por AJAX
        $('.edit-delete-colours').loadColours('edit-delete');
        // Reseteamos todos los datos antiguos de cualquier pieza
        $('.nodes').resetNodes();
        // Obtenemos el ID de la placa seleccionada
        var plaqueId = $(e.target).parent().attr('id');
        // Almacenamos el ID de la placa en el formulario
        $('#edit-delete-form').data('plaqueName', plaqueId);
        var currentId = '#' + plaqueId;
        
        if (plaqueId != 'list') {
            // Resaltamos la placa seleccionada
            $(currentId).markup(currentId);
        }
        // Obtenemos la ID del elemento contenedor
        var parentId = $(currentId).parent().attr('id');
        // Si el contenedor es la lista de placas
        if (parentId == 'plaques-list') {
            // Cancelamos el evento del enlace
            e.preventDefault();
            
            // Cargamos los datos de la placa seleccionada por AJAX
            $.get('/admin/placas/'+plaqueId, function(plaque) {
                // Cambiamos el CSS y los atributos para activar el tab Editar/Eliminar
                $('#forms').clickEditDeleteTabPlaque(true);
                // Cargamos la placa en el formulario
                $('#edit-delete-form').loadPlaque(plaque);
            }).fail(function() {
                // Si falla la petición o salta una excepción lo notificamos por pantalla
                alert('Imposible obtener los datos de la placa seleccionada');
            });
        }
    });   
    
    /**
     * Función que elimina una placa.
     */
    $('#delete-btn').click(function() {
        // Obtenemos el ID de la placa seleccionada
        var plaqueId = $('#plaque-id').val();
        // Obtenemos el action del formulario
        var action = $('#delete-form').attr('action').replace(':PLAQUE_ID', plaqueId);
        // Obtenemos el nombre de la placa seleccionada
        var plaqueName = $('#edit-delete-form').data('plaqueName');
        // Confirmamos la operación
        if (confirm('¿Está seguro de que quiere eliminar la placa "' + plaqueName + '"?')) {
            // Enviamos la petición por AJAX
            $.post(action, $('#delete-form').serialize(), function(result) {
                // Confirmamos el resultado de la operación
                alert(result);
                // Reseteamos los formularios, cambiamos de pestaña y recargamos la lista de placas actualizada
                $('#edit-delete-form').trigger('reset');
                $('#delete-form').trigger('reset');
                $('#edit-btn').attr('disabled', true);
                $('#delete-btn').attr('disabled', true);
                $('#edit-delete-form #upload-btn').attr('disabled', true);
                $('#forms').clickAddTabPlaque(true);       
                $('#plaques-list').fadeOut('slow', function() {
                    $('#plaques-list').loadPlaques(false);
                });
            }).fail(function() {
                // Si falla la petición o salta alguna excepción lo notificamos por pantalla
                alert('Imposible eliminar la placa "' + plaqueName + '" seleccionada en estos momentos. Inténtenlo de nuevo más tarde.');
            });
        }
    });
    
    /**
     * Plugin para cargar una placa en el formulario
     * @param {Object} plaque
     * @returns
     */
    $.fn.loadPlaque = function(plaque) {
        // Cargamos los datos de la placa en el formulario
        $('#edit-delete-form #name').val(plaque.name);
        $('#edit-delete-form #format').val(plaque.format);
        $('#edit-delete-form #plaque-id').val(plaque.id);
        $('#edit-delete-form #upload-file').val(uploadDir + '/' + plaque.thumbnail);
        $('#chosen-img').attr('src', uploadDir + '/' + plaque.thumbnail + '?' + Math.random());
        $('#edit-delete-form').data('plaqueName', plaque.name);

        // Cargamos la lista de piezas
        var cont = 0;
        if (plaque.piecesList != null) {
            var size = plaque.piecesList.length;
            var piece;
            var html = '';

            for (cont = 0; cont < size; cont++) {
                piece = plaque.piecesList[cont];
                html = $('#edit-delete-form .nodes').appendPieces('edit-delete', piece.id, cont+1, piece);
                $('#edit-delete-form .nodes').append(html);                            
            }
            // Incrementamos el número total de piezas
            $('.nodes').data('cont', cont+1);
        }
    }
    
    /**
     * Plugin que restalta la placa seleccionada de la lista de placas.
     * @param {int} currentId
     * @returns
     */
    $.fn.markup = function(currentId) {
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
    
    /**
     * Plugin que añade piezas nuevas a la placa del formulario.
     * @param {string} form
     * @param {int} id
     * @param {int} cont
     * @param {Object} piece
     * @returns {jQuery.fn.appendPieces.html|String}
     */
    $.fn.appendPieces = function(form, id, cont, piece) {
        var pieceId = '#' + id;
        // Obtenemos el nombre de la pieza
        var pieceName = $(pieceId).attr('title');
        // Obtenemos el src de la imagen de la pieza
        var pieceSrc = $(pieceId + ' img').attr('src');
        var html = '';
        
        // Y construimos todo el html
        html = '<span id="piece_' + cont + '_info"><img title="Click para seleccionar ' + pieceName + '" src="' + pieceSrc + '" alt="' + pieceName + '" class="thumb" id="thumb_' + cont + '" data-toggle="tooltip" data-placement="left" /> en ';
    
        if (form == 'add') {
            html += '<img title="Sin color" src="" alt="??" id="piece_' + cont + '_colour_img" />: ';
            html += '<button type="button" name="Eliminar pieza ' + pieceName + '" id="piece_' + cont + '_delete" class="button btn-danger" data-toggle="tooltip" data-placement="right" title="Click para eliminar"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>';
            html += '<button type="button" name="Bloquear pieza ' + pieceName + '" id="piece_' + cont + '_lock" class="button btn-primary" data-toggle="tooltip" data-placement="right" title="Click para bloquear/desbloquear"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></button>';
            html += '<input type="text" id="piece_' + cont + '_nodes" name="piece_' + cont + '_nodes" class="form-control" placeholder="Nodos del trazado de la pieza..." required />';
            html += '<input type="hidden" name="piece_' + cont + '_colour" id="piece_' + cont + '_colour" value="" />';
            html += '<input type="hidden" name="piece_' + cont + '_locked" id="piece_' + cont + '_locked" value="0" />';
        }
        else {
            html += '<img title="' + piece.colour + '" src="/_data/colores/' + piece.colour + '.jpg" alt="' + piece.colour + '" id="piece_' + cont + '_colour_img" />: ';
            html += '<button type="button" name="Eliminar pieza ' + pieceName + '" id="piece_' + cont + '_delete" class="button btn-danger" data-toggle="tooltip" data-placement="right" title="Click para eliminar"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>';
            if (piece.isLocked == 0) {
                html += '<button type="button" name="Bloquear pieza ' + pieceName + '" id="piece_' + cont + '_lock" class="button btn-primary" data-toggle="tooltip" data-placement="right" title="Click para bloquear/desbloquear"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></button>';
            }
            else {
                html += '<button type="button" name="Bloquear pieza ' + pieceName + '" id="piece_' + cont + '_lock" class="button btn-warning" data-toggle="tooltip" data-placement="right" title="Click para bloquear/desbloquear"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></button>';
            }
            html += '<input type="text" id="piece_' + cont + '_nodes" name="piece_' + cont + '_nodes" value="' + piece.nodes + '" class="form-control" placeholder="Nodos del trazado de la pieza..." required />';
            html += '<input type="hidden" name="piece_' + cont + '_colour" id="piece_' + cont + '_colour" value="' + piece.colour + '" />';
            html += '<input type="hidden" name="piece_' + cont + '_locked" id="piece_' + cont + '_locked" value="' + piece.isLocked + '" />';
        }
        
        html += '<input type="hidden" name="piece_' + cont + '_id" id="piece_' + cont + '_id" value="'+ id +'" /></span>';
        
        return html;
    }
    
    /**
     * Plugin que elimina toda la información almacenada de las piezas antiguas.
     * @returns
     */
    $.fn.resetNodes = function() {
        $('.nodes').empty();
        $('.nodes').removeData();
        $('.nodes').data('cont', 0);
    }
    
    /**
     * Plugin que activa la pestaña Editar/Eliminar para la gestión de placas.
     * @param {boolean} click
     * @returns
     */
    $.fn.clickEditDeleteTabPlaque = function(click) {
        $('#edit-delete-tab').attr('role', 'presentation');
        $('#edit-delete-tab').attr('aria-controls', 'edit-delete-tab');
        $('#edit-delete-tab').attr('data-toggle', 'tab');
        $('#edit-delete-tab a').attr('href', '#tab-edit-delete-form');
        $('#edit-delete-tab').removeClass('disabled');
        
        if (click) {
            $('#edit-delete-tab').trigger('click');
        }
        
        $('#tab-add-form').removeClass('active');
        $('#tab-edit-delete-form').addClass('active');
        $('#edit-btn').removeAttr('disabled');
        $('#delete-btn').removeAttr('disabled');
        $('#edit-delete-form #upload-btn').removeAttr('disabled');
        $('#edit-delete-form #upload-btn').removeAttr('required');
    }
    
    /**
     * Plugin que activa la pestaña Añadir para la gestión de placas.
     * @param {boolean} click
     * @returns
     */
    $.fn.clickAddTabPlaque = function(click) {
        // Activamos el tab de registrar
        $('#edit-delete-tab').removeAttr('role', 'presentation');
        $('#edit-delete-tab').removeAttr('aria-controls', 'edit-delete-tab');
        $('#edit-delete-tab').removeAttr('data-toggle', 'tab');
        $('#edit-delete-tab a').removeAttr('href', '#tab-edit-delete-form');
        $('#edit-delete-tab').addClass('disabled');
        
        if (click) {
            $('#add-tab').trigger('click');
        }
        
        $('#tab-add-form').addClass('active');
        $('#tab-edit-delete-form').removeClass('active');
        $('#edit-btn').attr('disabled');
        $('#delete-btn').attr('disabled');
        $('#edit-delete-form #upload-btn').attr('disabled');
        $('#edit-delete-form #upload-btn').attr('required');
    }
    
    /**
     * Plugin para listar todas las placas.
     * @param {boolean} reload
     * @returns
     */
    $.fn.loadPlaques = function(reload) {
        // Enviamos la petición de listar las placas por AJAX
        $.get('/admin/placas/listar', function(plaques) {
            var size = plaques.length;
            var plaque;
            var html = '';
            var plaqueTag;
            
            // Imprimimos la lista de placas
            $('#plaques-list').fadeOut('slow', function(){
                if (size > 0) {
                    var cont = 0;

                    for (cont = 0; cont < size; cont++) {
                        plaque = plaques[cont];
                        plaqueTag = '<a title="Placa ' + plaque.name + '" href="#!" class="thumbnail" id="' + plaque.id + '"><img title="Placa ' + plaque.name + '" src="/_data/placas/' + plaque.thumbnail + '?' + Math.random() + '" alt="Placa ' + plaque.name + '" width="105" height="105" /><span>' + plaque.name + '</span></a>';
                        html += plaqueTag;
                    }

                    $('#plaques-list').html(html);
                    // Si reaload es true movemos la pantalla hasta la última placa registrada
                    if (reload) {
                        var lastId = '#'+plaque.id;
                        $(location).attr('href',lastId); 
                    }
                }
                else {
                    // Si no hay placas avisamos por pantalla
                    $('#plaques-list').html('<h3 class="alert alert-danger text-center">Aún no hay placas registradas<br /><small class="alert-danger">Añada placas para poder visualizarlas aquí</small><h3>');
                }

                $('#plaques-list').fadeIn('slow');                
            });
        }).fail(function() {
            // Si falla la petición o salta alguna excepción lo notificamos por pantalla
            alert('Imposible obtener la lista de placas en estos momentos');
        });
    }
    
    /**
     * Función para listar todos los piezas en los formularios.
     * @param {string} form
     * @returns
     */
    $.fn.loadPieces = function(form) {
        // Mandamos la petición de listar piezas por AJAX
        $.get('/admin/piezas/listar', function(pieces) {
            var size = pieces.length;
            var piece;
            var html = '';
            var pieceTag;
            var tag = '#' + form + '-form .' + form + '-list';
            
            // Imprimimos la lista de piezas
            if (size > 0) {
                var cont = 0;

                for (cont = 0; cont < size; cont++) {
                    piece = pieces[cont];
                    pieceTag = '<a title="Click para añadir pieza ' + piece.name + '" href="#!" class="thumbnail" id="' + piece.id + '" data-toggle="tooltip" data-placement="top"><img title="Pieza ' + piece.name + '" src="/_data/piezas/' + piece.image + '" alt="Pieza ' + piece.name + '" width="105" height="105" /><span>' + piece.name + '</span></a>';
                    html += pieceTag;
                }

                $(tag).html(html);
            }
            else {
                // Si no hay piezas lo mostramos por pantalla
                $(tag).html('<h3 class="alert alert-danger text-center">Aún no hay piezas registradas<br /><small class="alert-danger">Añada piezas para poder visualizarlas aquí</small><h3>');
            }
        }).fail(function() {
            // Si falla la petición o salta una excepción lo notificamos por pantalla
            alert('Imposible obtener la lista de piezas en estos momentos');
        });
    }
    
    /**
     * Plugin para mostrar la lista de colores en los formularios.
     * @param {string} form
     * @returns
     */
    $.fn.loadColours = function(form) {
        // Mandamos la petición de listar colores por AJAX
        $.get('/admin/colores/listar', function(colours) {
            var size = colours.length;
            var colour;
            var html = '';
            var colourTag;
            var tag = '#' + form + '-form .' + form + '-colours';            
            
            // Imprimimos la lista de colores por pantalla
            if (size > 0) {
                var cont = 0;

                for (cont = 0; cont < size; cont++) {
                    colour = colours[cont];
                    colourTag = '<a title="Asignar color ' + colour.name + '" href="#!" class="thumbnail" id="' + colour.name + '" data-toggle="tooltip" data-placement="top"><img title="Color ' + colour.name + '" src="/_data/colores/' + colour.image + '" alt="Color ' + colour.name + '" width="105" height="105" /><span>' + colour.name + '</span></a>';
                    html += colourTag;
                }

                $(tag).html(html);
            }
            else {
                // Si no hay colores imprimimos un aviso
                $(tag).html('<h3 class="alert alert-danger text-center">Aún no hay colores registrados<br /><small class="alert-danger">Añada colores a la paleta para poder visualizarlos aquí</small><h3>');
            }
        }).fail(function() {
            // Si falla la petición o salta alguna excepción lo notificamos por pantalla
            alert('Imposible obtener la lista de colores en estos momentos');
        });
    }
});