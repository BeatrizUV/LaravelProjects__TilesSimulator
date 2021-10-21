{!! Form::open(array('url' => route('admin.placas.store'), 'method' => 'POST', 'name' => 'add-form', 'id' => 'add-form', 'class' => 'col-md-10 col-md-offset-1 form-horizontal szd-form', 'files' => true)) !!}
    <div class="row inputs">
        @include('admin.templates.partials.fields.plaque1')
        <div class="form-group" id="pieces-panel">
            {!! Form::label('nodes', 'Piezas:', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-9" class="add-pieces">
                <div class="list add-list"></div>
                <div class="colours add-colours"></div>
                <div class="nodes"></div>
            </div>
        </div>
        @include('admin.templates.partials.fields.plaque2')
    </div>
    <div class="row actions">
        <input type="submit" name="add-btn" id="add-btn" value="Añadir" class="btn szd-btn-active pull-left"/>
        <input type="reset" name="cancel-btn" id="cancel-btn" value="Cancelar" class="btn szd-btn btn-cancel pull-right"/>
    </div>
{!! Form::close() !!}