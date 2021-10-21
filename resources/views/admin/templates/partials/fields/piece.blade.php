<div class="form-group">
    {!! Form::label('name', 'Nombre:', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('name', null, ['class' => 'form-control', 'type' => 'text', 'id' => 'name', 'placeholder' => 'Nombre de la pieza...', 'required' => 'required', 'maxlength' => '25']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('upload-file', 'Imagen:', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-9">
        <input id="upload-file" placeholder="Foto de la pieza..." class="form-control" readonly />
        <div class="file-upload">
            <span class="btn szd-btn-active">Examinar</span>
            <input id="upload-btn" name="image" type="file" class="upload" required />
        </div>
    </div>
</div>