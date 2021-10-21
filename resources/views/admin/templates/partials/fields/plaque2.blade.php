<div class="form-group">
    {!! Form::label('upload-file', 'Miniatura:', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-9">
        <input id="upload-file" placeholder="Miniatura de la placa..." class="form-control" readonly />
        <div class="file-upload">
            <span class="btn szd-btn-active">Examinar</span>
            <input id="upload-btn" name="thumbnail" type="file" class="upload" required />
        </div>
    </div>
</div>
{!! Form::hidden('pieces', '0', ['id' => 'pieces']) !!}