$(document).ready(function() {        
    /**
     * Función que ejecuta parámetros de inicio.
     */
    $(function() {
        $('body').tooltip({ selector: '[data-toggle=tooltip]' });
        $('#budget').modal('hide');
        $('.szd-budget-btn').css('pointer-events', 'all');
        $('#colorizer').height('456px');
        $('#colorizer').width('456px');
        $('.szd-preview div').height($('.szd-preview div').width());
        $('body').data('random', Math.random());
        $('#mosaic-right').data('flippedSVG', [28, 32]);
        $('#cookies-alert').checkCookies();
    });
    
    /**
     * Función que desactiva el click del botón derecho del ratón.
     */
    $('body').bind('contextmenu',function(e) {
        e.preventDefault();
        return false;
    });
    
    /**
     * Función para mostrar la lista de colores.
     */
    $('#colours-palette').ready(function() {
        $(this).loadColours();
    });
    
    /**
     * Función para mostrar la lista de placas.
     */
    $('#plaques-carousel').ready(function() {
        $(this).loadPlaques();
    });
    
    /**
     * Función para cargar el color seleccionado.
     */
    $('#colours-palette').on('click', 'div', function() {
        $('#selected-colour').removeClass($('#selected-colour').data('currentColour'));
        $('#selected-colour').addClass(this.id);
        $('#selected-colour').data('currentColour', this.id);
        $('#selected-colour').attr('title', this.id);
        $('#selected-colour').attr('data-toggle', 'tooltip');
        $('#selected-colour').attr('data-placement', 'left');
        $('#colorizer').addClass('szd-cursor-paint-bucket');
    });
    
    /**
     * Función para cargar la placa seleccionada en el editor.
     */
    $('#plaques-carousel').on('click', 'img', function() {
        // Mostramos gifs animados para mostrar el tiempo de carga
        $('#colorizer').waiting('colorizer');
        $('.szd-preview').waiting('mosaic');
        // Resaltamos la placa seleccionada en el carrusel
        $('#plaques-carousel').markup(this.id);
        // Cargamos la placa seleccionada por AJAX en el editor
        $('#colorizer').loadPlaque(this.id);
        $('.szd-budget-btn').attr('data-toggle', 'modal');
        $('.szd-budget-btn').attr('data-target', '#budget');
        $('.szd-budget-btn').removeClass('disabled');
        $('.szd-budget-btn').css('cursor', 'pointer');
    });
    
    /**
     * Función que mueve el carrusel de placas hacia arriba.
     */
    $('#carousel-up').click(function() {
        var interval = 110;
        var maxMargin = 0;
        var currentMarginTop = parseInt($('#first').css('margin-top').replace('px', ''));        
        var newMarginTop = (currentMarginTop + interval) + 'px';   
        
        if (currentMarginTop < maxMargin) {
            $('#first').animate({'margin-top': newMarginTop}, 250, "linear");
        }
    });
    
    /**
     * Función que mueve el carrusel de placas hacia abajo.
     */
    $('#carousel-down').click(function() {
        var interval = 110;
        var shownPlaques = 4;
        var totalPlaques = $('#plaques-carousel').data('plaques');
        var totalWidth = interval * totalPlaques;
        var frame = interval * shownPlaques;
        var maxMargin = (totalWidth - frame) * -1;
        var currentMarginTop = parseInt($('#first').css('margin-top').replace('px', ''));        
        var newMarginTop = (currentMarginTop - interval) + 'px';        
        
        if (currentMarginTop > maxMargin) {
            $('#first').animate({'margin-top': newMarginTop}, 250, "linear");
        }
    });
    
    /**
     * Función que resetea el editor, el mosaico y el color seleccionado.
     */
    $('.szd-reset-btn').click(function() {
        $('#colorizer').empty();
        $('#colorizer').css('background-color', '#EBEBEB');
        $('#selected-colour').removeClass($('#selected-colour').data('currentColour'));
        $('#colorizer').removeData('currentPlaque');
        $('#colorizer').removeData('currentColour');
        $('#mosaic-left').empty();
        $('#mosaic-right').empty();
        $('#mosaic-left').css('background-color', '#EBEBEB');
        $('#mosaic-right').css('background-color', '#EBEBEB');
    });
    
    /**
     * Función que colorea las piezas de la placa en el editor con el color seleccionado.
     */
    $('#colorizer').on('click', '#map .nodes', function() {
        // Chequeamos que hay un color seleccionado previamente
        if (typeof $("#selected-colour").data('currentColour') !== 'undefined') {
            var pieceId = '#' + this.id;
            var lockedPieces = $('#colorizer').data('lockedPieces');
            var lockedPieceId = parseInt(this.id.replace('piece_', '').split('-')[0]);
            // Chequeamos que la pieza seleccionada no esté bloqueada
            if ($.inArray(lockedPieceId, lockedPieces) === -1) {
                // Coloreamos la pieza
                var currentColour = $('#selected-colour').data('currentColour');
                var patternId = 'colour_' + currentColour + '_colorizer';        
                var random = $('body').data('random');
                $(pieceId).attr('fill', 'url(#' + patternId + ')');

                var pathsList = $('#map .nodes').get();
                var paths = '';
                var path;
                var patterns = '';

                var cont = 0;
                var size = pathsList.length;
                var pathId;
                var patternId;
                var colour;
                var patternFill;
                
                // Recogemos las piezas de la placa y los patrones de relleno
                for (cont = 0; cont < size; cont++) {
                    path = pathsList[cont];
                    pathId = '#' + path.id;
                    patternFill = $(pathId).attr('fill');
                    patternId = patternFill.replace('url(#', '').replace('_colorizer)', '_preview');
                    colour = patternId.replace('colour_', '').replace('_preview', '');
                    paths += '<path id="' + path.id.replace('_colorizer', '_preview') + '" d="' + $(pathId).attr('d') + '" class="nodes" fill="' + patternFill + '" style="stroke: #000; stroke-width: 5px;" />';                
                    patterns += '<pattern id="' + patternId + '" patternUnits="userSpaceOnUse" x="0" y="0" width="100%" height="100%"><image xlink:href="/_data/colores/rellenos/' + colour + '.jpg?' + random + '" x="0" y="0" width="100%" height="100%"></image></pattern>';
                }
                // y los mostramos en el mosaico
                $('.szd-preview').loadPreview(patterns, paths);
            }
        }
        else {
            // Si no hay color seleccionado avisamos por pantalla
            alert(alertColours);
        }        
    });
    
    /**
     * Función que carga la ventana del formulario para los presupuestos.
     */
    $('.szd-budget-btn').click(function() {
        // Cargamos la ventana y sus elementos
        $('.modal-footer').css('display', 'block');
        $('#budget-form').css('display', 'block');
        $('.modal-body').css('height', 'auto');
        $('#budget-sent').remove();
        // Cargamos la placa modificada
        $('#plaque-preview').html($('#mosaic-left').html().replace('mosaic-left-svg', 'plaque-preview-svg'));
        var plaque = $('#colorizer').data('currentPlaque');
        var pieces = plaque.piecesList;
        var random = $('body').data('random');
        var piece;
        var pieceId;
        var cont = 0;
        var size = pieces.length;
        var currentColour;
        var currentPiecesList = [];
        var lockedPieces = $('#colorizer').data('lockedPieces');
        
        // Obtenemos la lista de piezas y sus colores
        var table = '<table class="table">';
        table += '<thead><tr><th>' + piecesTableName +  '</th><th>' + piecesTableColour +  '</th></tr></thead>';
        for (cont = 0; cont < size; cont++) {
            piece = pieces[cont];
            pieceId = 'piece_' + piece.id + '-' + cont;
            currentColour = $('#plaque-preview #' + pieceId).attr('fill').replace('url(#colour_', '').replace('_preview)', '');
            table += '<tr';
            if ($.inArray(piece.id, lockedPieces) !== -1) {
                table += ' class="disabled"';
            }
            table += '><td><img title="' + piece.name + '" src="/_data/piezas/' +  piece.image + '?' + random + '" alt="' + piece.name + '" width="20" height="20" class="szd-budget-pieces" /> ' + piece.name + '</td>';
            table += '<td><img title="' + currentColour + '" src="/_data/colores/' + currentColour + '.jpg?' + random + '" alt="' + currentColour + '" width="20" height="20" class="szd-budget-colours" /> ' + currentColour + '</td></tr>';
            currentPiecesList.push([piece.name, piece.image, currentColour]);
        }
        table += '</table>';
        
        // Imprimimos la lista de piezas de la placa
        $('#plaque-pieces').html(table);
        $('#budget-form').data('piecesList', currentPiecesList);
        // Cargamos los datos de los campos ocultos
        $('#budget-form').loadHiddens();
    });
    
    /**
     * Función que envía el formulario de presupuestos.
     */
    $('#budget-form').on('submit',function(e) {
        e.preventDefault();
        $('.modal-content').waiting('budget');
         
        $.ajax({
            url: $('#budget-form').attr('action'),
            data:new FormData(this),
            type: "POST",
            contentType: false,
            cache: false,
            processData:false,
            success:function(result) {
                if (result.input !== '') {
                    $('#budget-form ' + result.input).css('border', '1px solid #F00');
                    alert(result.message);
                }
                else {
                    $('#waiting-budget').remove();
                    $('.modal-body').prepend('<h2 id="budget-sent"><img src="/images/ok.png" width="100" height="100" /><br /><br />' + result.message + '</h2<'); 
                    $('#budget-form').trigger('reset');
                }
            },
            error:function() { 
                alert(fatalError);
            }
        });
    });
    
    /**
     * Función que guarda una cookie para saber que el usuario acepta la política de cookies.
     */
    $('#cookies-alert .close').click(function() {
        // Para estas gestiones usamos el plugin jquery.cookie.js descargable desde internet
        $.cookie('cookies', 'si', { expires: 7, path: '/' });
        $('#cookies-alert').checkCookies();
    });
    
    /**
     * Plugin para mostrar la paleta de colores.
     * @returns
     */
    $.fn.loadColours = function() {
        // Enviamos la petición de listar los colores por AJAX
        $.get('/admin/colores/listar', function(colours) {
            var size = colours.length;
            var colour;
            var html = '';
            var colourTag;
            
            // Imprimimos la lista de colores
            if (size > 0) {
                var cont = 0;

                for (cont = 0; cont < size; cont++) {
                    colour = colours[cont];
                    colourTag = '<div class="' + colour.name + ' szd-colour_' + cont + ' szd-colour" title="' + colour.name + '" data-toggle="tooltip" id="' + colour.name + '"></div>';
                    html += colourTag;
                }

                $('#colours-palette').html(html);
                $('#colours-palette').data('colours', colours);
            }
            else {
                // Si no hay colores muestra un error por pantalla
                alert(fatalError);
            }
        }).fail(function() {
            // Si la petición falla o salta alguna excepción lo mostramos por pantalla
            alert(fatalError);
        });
    }
    
    /**
     * Plugin que carga la lista de placas en el carrusel.
     * @returns
     */
    $.fn.loadPlaques = function() {
        // Enviamos la petición de listar placas por AJAX
        $.get('/admin/placas/listar', function(plaques) {
            var size = plaques.length;
            var plaque;
            var html = '';
            var plaqueTag;
            var random = $('body').data('random');
            
            // Imprimimos la lista de placas
            if (size > 0) {
                var cont = 0;

                for (cont = 0; cont < size; cont++) {
                    plaque = plaques[cont];                    
                    plaqueTag = '<li class="item"';
                    
                    if (cont == 0) {
                        plaqueTag += ' id="first" ';
                    }
                    
                    plaqueTag +='><img id="' + plaque.id + '" title="' + plaqueLabel + ' ' + plaque.name + ' (' + plaque.format + ')" src="/_data/placas/' + plaque.thumbnail + '?' + random + '" alt="' + plaqueLabel + ' ' + plaque.name + '"  width="95" height="95" data-toggle="tooltip" data-placement="right" /></li>';
                    html += plaqueTag;
                }

                $('#plaques-carousel').html(html);
                $('#plaques-carousel').data('plaques', size);
            }
            else {
                // Si no hay placas mostramos el error por pantalla
                alert(fatalError);
            }
        }).fail(function() {
            // Si falla la petición o salta alguna exepción lo indicamos por pantalla
            alert(fatalError);
        });
    }
    
    /**
     * Plugin encargado de cargar la placa seleccionada en el editor.
     * @param {int} plaqueId
     * @returns
     */
    $.fn.loadPlaque = function(plaqueId) {
        // Cargamos los datos de la placa seleccionada por AJAX
        $.get('/admin/placas/'+plaqueId, function(plaque) {
            $('#colorizer').data('currentPlaque', plaque);
            var size = 0;
            var piece;
            var html = '';
            var cont = 0;
            var paths = '';
            var patterns = '';
            var colour;
            var random = $('body').data('random');
            var defaultPatterns = '';
            var lockedPieces = [];
            
            var coloursPalette = $('#colours-palette').data('colours');
            // Imprimimos en el SVG un patrón de relleno para la placa por cada color de la paleta
            size = coloursPalette.length;
            for (cont = 0; cont < size; cont++) {
                colour = coloursPalette[cont];
                patterns += '<pattern id="colour_' + colour.name + '_colorizer" patternUnits="userSpaceOnUse" x="0" y="0" width="100%" height="100%"><image xlink:href="/_data/colores/rellenos/' + colour.name + '.jpg?' + random + '" x="0" y="0" width="100%" height="100%"></image></pattern>';
            }
            // Imprimimos en el SVG la lista de piezas con sus nodos y sus rellenos por defecto
            size = plaque.piecesList.length;
            for (cont = 0; cont < size; cont++) {
                piece = plaque.piecesList[cont];
                paths += '<path id="piece_' + piece.id + '-' + cont + '" d="' + piece.nodes + '" class="nodes';
                if (piece.isLocked == 1) {
                    paths += ' disabled';
                    lockedPieces.push(piece.id);
                }
                paths += '" fill="url(#colour_' + piece.colour + '_colorizer)" style="stroke: #000; stroke-width: 5px;" />';                
                defaultPatterns += '<pattern id="colour_' + piece.colour + '_preview" patternUnits="userSpaceOnUse" x="0" y="0" width="100%" height="100%"><image xlink:href="http://localhost:8080/_data/colores/rellenos/' + piece.colour + '.jpg?' + random + '" x="0" y="0" width="100%" height="100%"></image></pattern>';
            }
            // Almacenamos la lista de piezas bloqueadas de la placa
            $('#colorizer').data('lockedPieces', lockedPieces);
            
            // Montamos el html de la placa SVG
            html =  '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="map" height="456" width="456" viewBox="0 0 4240 4240">';
            html += '<defs>';
            html += patterns;
            html += '</defs>';            
            html += paths;
            html += '</svg>';          
            // La imprimimos por pantalla
            $('#colorizer').html(html);
            // Y cargamos la placa en el mosaico
            $('.szd-preview').loadPreview(defaultPatterns, paths);
        }).fail(function() {
            // Si falla la petición o salta alguna excepción lo notificamos por pantalla
            alert(fatalError);
        });
    }
    
    /**
     * Plugin que restalta la placa seleccionada en el carrusel.
     * @param {int} currentId
     * @returns
     */
    $.fn.markup = function(currentId) {
        $('.item img').css({'box-shadow' : 'none'});
        $('#' + currentId).css({'box-shadow' : '0 0 5px #000'});
    }
    
    /**
     * Plugin que muestra un gif animado para mostrar el tiempo de carga de los procesos.
     * @param {string} element
     * @returns
     */
    $.fn.waiting = function(element) {
        // Se puede cargar el gif en el editor
        if (element == 'colorizer') {
            $('#colorizer').css('background-color', '#FFF');
            $('#colorizer').html('<img src="/images/waiting-icon.gif" id="waiting-plaque"/>');
            $('#waiting-plaque').css({'display': 'block', 'margin': 'auto', 'margin-top': '27%'});
        }
        // en los bloques del mosaico
        if (element == 'mosaic') {
            $('#mosaic-left').css('background-color', '#FFF');
            $('#mosaic-left').html('<img src="/images/waiting-icon.gif" id="waiting-plaque-preview-left"/>');
            $('#mosaic-right').css('background-color', '#FFF');
            $('#mosaic-right').html('<img src="/images/waiting-icon.gif" id="waiting-plaque-preview-right"/>');
            $('#waiting-plaque-preview-left').css({'display': 'block', 'margin': 'auto', 'margin-top': '8%'});
            $('#waiting-plaque-preview-right').css({'display': 'block', 'margin': 'auto', 'margin-top': '8%'});
        }
        // o en el cuerpo del formulario de presupuestos una vez se envía
        if (element == 'budget') {
            $('.modal-footer').css('display', 'none');
            $('#budget-form').css('display', 'none');
            $('.modal-body').css('height', '50%');
            $('.modal-body').prepend('<img src="/images/waiting-icon.gif" id="waiting-budget"/>');
            $('#waiting-budget').css({'display': 'block', 'margin': 'auto'});
        }
    }
    
    /**
     * Plugin que carga una miniatura del SVG del editor en cada bloque del mosaico
     * @param {type} patterns
     * @param {type} paths
     * @returns {undefined}
     */
    $.fn.loadPreview = function(patterns, paths) {
        // Obtenemos la placa
        var plaque = $('#colorizer').data('currentPlaque');
        // Preparamos el html del SVG de la izquierda
        var svgLeft = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="preview" id="mosaic-left-svg" height="237" width="237" viewBox="0 0 4240 4240">';
        // Preparamos el html del SVG de la derecha
        var svgRight = svgLeft;
        if ($.inArray(plaque.id, $('#mosaic-right').data('flippedSVG')) !== -1) {
            // Reflejamos horizontalmente las placas que así lo necesiten para mostrar bien el dibujo del mosaico
            svgRight =  '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="preview szd-flip-horizontal" id="mosaic-right-svg" height="237" width="237" viewBox="0 0 4240 4240">';
        }            
        
        // Montamos los SVG
        var svg = '<defs>';
        svg += patterns;
        svg += '</defs>';            
        svg += ('' + paths).replace(/_colorizer/g, '_preview');
        svg += '</svg>';

        // Y los mostramos por pantalla
        $('#mosaic-left').html(svgLeft + svg);            
        $('#mosaic-right').html(svgRight + svg);
    }
    
    /**
     * Plugin que carga los datos de los campos ocultos del formulario de presupuestos.
     * @returns
     */
    $.fn.loadHiddens = function() {
        // Obtenemos la lista de piezas de la placa
        var piecesList = $('#budget-form').data('piecesList');
        var size = piecesList.length;
        var cont = 0;
        var json = '';
        
        // La pasamos a JSON
        json += '[';
        for (cont = 0; cont < size; cont++) {
            json += '{"name": "' + piecesList[cont][0] + '", "image": "' + piecesList[cont][1] + '", "colour": "' + piecesList[cont][2] + '"}, ';
        }
        json += ']';
        
        // Asignamos el JSON de la lista de piezas
        $('#budget-form #pieces-list').val(json.replace('}, ]', '}]'));
        // Asignamos el ID de la placa
        $('#budget-form #plaque-id').val($('#colorizer').data('currentPlaque').id);
        // Transformamos el SVG dinámico en un archivo PNG temporal
        svgAsPngUri(document.getElementById('plaque-preview-svg'), {scale: 0.05}, function(uri) {
            // Asignamos los datos codificados del PNG temporal
            $('#thumbnail').val(uri);
        });
    }
    
    /**
     * Plugin que muestra o esconde el aviso de cookies.
     * @returns {undefined}
     */
    $.fn.checkCookies = function() {
        // Para este plugin utilizamos el plugin jquery.cookies.js descargable desde internet
        if ($.cookie('cookies') === 'si') {
            $('#cookies-alert').remove();
        }
        else {
            $('#cookies-alert').animate({'bottom': '0'}, 1000);
        }
    }
});
