{!! Form::open(array('url' => route('admin.placas.update', ':PLAQUE_ID'), 'method' => 'PUT', 'name' => 'edit-delete-form', 'id' => 'edit-delete-form', 'class' => 'col-md-10 col-md-offset-1 form-horizontal szd-form', 'files' => true)) !!}
    <div class="row inputs">
        @include('admin.templates.partials.fields.plaque1')
        <div class="form-group" id="pieces-panel">
            {!! Form::label('nodes', 'Piezas:', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-9" class="edit-delete-pieces">
                <div class="list edit-delete-list"></div>
                <div class="colours edit-delete-colours"></div>
                <div class="nodes"></div>
            </div>
        </div>
        @include('admin.templates.partials.fields.plaque2')
        {!! Form::hidden('plaque-id', null, ['id' => 'plaque-id']) !!}
        <img title="" src="" alt="" width="200" height="200" id="chosen-img" />
    </div>
    <div class="row actions">
        <input type="submit" name="edit-btn" id="edit-btn" value="Modificar" class="btn szd-btn-active szd-btn-edit pull-left"/>
        <input type="reset" name="cancel-btn" id="cancel-btn" value="Cancelar" class="btn szd-btn btn-cancel pull-left"/>
        {!! Form::close() !!}
        {!! Form::open(array('url' => route('admin.placas.destroy', ':PLAQUE_ID'), 'method' => 'DELETE', 'name' => 'delete-form', 'id' => 'delete-form')) !!}
            <input type="button" name="delete-btn" id="delete-btn" value="Eliminar" class="btn szd-btn-delete pull-right btn-danger"/>
        {!! Form::close() !!}
    </div>