{!! Form::open(array('url' => route('admin.distribuidores.store'), 'method' => 'POST', 'name' => 'add-form', 'id' => 'add-form', 'class' => 'col-md-10 col-md-offset-1 form-horizontal szd-form', 'files' => true)) !!}
    <div class="row inputs">
        @include('admin.templates.partials.fields.showroom')
    </div>
    <div class="row actions">
        <input type="submit" name="add-btn" id="add-btn" value="Añadir" class="btn szd-btn-active pull-left"/>
        <input type="reset" name="cancel-btn" id="cancel-btn" value="Cancelar" class="btn szd-btn btn-cancel pull-right"/>
    </div>
{!! Form::close() !!}