{!! Form::open(array('url' => route('admin.distribuidores.update', ':SHOWROOM_ID'), 'method' => 'PUT', 'name' => 'edit-delete-form', 'id' => 'edit-delete-form', 'class' => 'col-md-10 col-md-offset-1 form-horizontal szd-form', 'files' => true)) !!}
    <div class="row inputs">
        @include('admin.templates.partials.fields.showroom')
        {!! Form::hidden('showroom-id', null, ['id' => 'showroom-id']) !!}
        <img title="" src="" alt="" width="200" height="90" id="chosen-img" />
    </div>
    <div class="row actions">
        <input type="submit" name="edit-btn" id="edit-btn" value="Modificar" class="btn szd-btn-active szd-btn-edit pull-left"/>
        <input type="reset" name="cancel-btn" id="cancel-btn" value="Cancelar" class="btn szd-btn btn-cancel pull-left"/>
        {!! Form::close() !!}
        {!! Form::open(array('url' => route('admin.distribuidores.destroy', ':SHOWROOM_ID'), 'method' => 'DELETE', 'name' => 'delete-form', 'id' => 'delete-form')) !!}
            <input type="button" name="delete-btn" id="delete-btn" value="Eliminar" class="btn szd-btn-delete pull-right btn-danger"/>
        {!! Form::close() !!}
    </div>