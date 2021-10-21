$(document).ready(function() {    
    /**
     * Función para tunear el botón de upload del tab Añadir.
     */
    $('#add-form #upload-btn').change(function() {
        $('#add-form #upload-file').val(this.value.replace('C:\\fakepath\\', '')).css('color', '#999');
    });
        
    /**
     * Función para tunear el botón de upload del tab Editar/Eliminar.
     */
    $('#edit-delete-form #upload-btn').change(function() {
        $('#edit-delete-form #upload-file').val(this.value.replace('C:\\fakepath\\', '')).css('color', '#999');
        $('#chosen-img').attr('src', '/images/waiting-icon.gif');
    });
    
    /**
     * Función para lanzar el evento del navtab de Boostrap.
     */
    $('#forms a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });
    
    /**
     * Función para lanzar el evento del tooltip de Bootstrap.
     * @returns {undefined}
     */
    $(function () {
        $("body").tooltip({ selector: '[data-toggle=tooltip]' });
    });
    
    /**
     * Plugin encargado de cambiar a la pestaña de Editar/Eliminar.
     * @returns
     */
    $.fn.clickEditDeleteTab = function() {
        $('#edit-delete-tab').attr('role', 'presentation');
        $('#edit-delete-tab').attr('aria-controls', 'edit-delete-tab');
        $('#edit-delete-tab').attr('data-toggle', 'tab');
        $('#edit-delete-tab a').attr('href', '#tab-edit-delete-form');
        $('#edit-delete-tab').removeClass('disabled');
        $('#edit-delete-tab').trigger('click');
        $('#tab-add-form').removeClass('active');
        $('#tab-edit-delete-form').addClass('active');
        $('#edit-btn').removeAttr('disabled');
        $('#delete-btn').removeAttr('disabled');
        $('#edit-delete-form #upload-btn').removeAttr('disabled');
        $('#edit-delete-form #upload-btn').removeAttr('required');
    }
    
    /**
     * Plugin encargado de cmabiar a la pestaña de Añadir.
     * @returns
     */
    $.fn.clickAddTab = function() {
        // Activamos el tab de registrar
        $('#edit-delete-tab').removeAttr('role', 'presentation');
        $('#edit-delete-tab').removeAttr('aria-controls', 'edit-delete-tab');
        $('#edit-delete-tab').removeAttr('data-toggle', 'tab');
        $('#edit-delete-tab a').removeAttr('href', '#tab-edit-delete-form');
        $('#edit-delete-tab').addClass('disabled');
        $('#add-tab').trigger('click');
        $('#tab-add-form').addClass('active');
        $('#tab-edit-delete-form').removeClass('active');
        $('#edit-btn').attr('disabled');
        $('#delete-btn').attr('disabled');
        $('#edit-delete-form #upload-btn').attr('disabled');
        $('#edit-delete-form #upload-btn').attr('required');
    }
});