<div class="form-group">
    {!! Form::label('company', 'Empresa:', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('company', null, ['class' => 'form-control', 'type' => 'text', 'id' => 'company', 'placeholder' => 'Nombre del distribuidor...', 'required' => 'required', 'maxlength' => '50']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('email', 'E-mail:', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::email('email', null, ['class' => 'form-control', 'type' => 'text', 'id' => 'email', 'placeholder' => 'E-mail del distribuidor...', 'required' => 'required', 'maxlength' => '100']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('telephone', 'Teléfono:', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('telephone', null, ['class' => 'form-control', 'type' => 'text', 'id' => 'telephone', 'placeholder' => 'Teléfono del distribuidor...', 'required' => 'required', 'maxlength' => '25']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('website', 'Website:', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('website', null, ['class' => 'form-control', 'type' => 'text', 'id' => 'website', 'placeholder' => 'Web del distribuidor...', 'required' => 'required', 'maxlength' => '100']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('lang', 'Idioma:', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::select('lang', ['es' => 'Español', 'en' => 'Inglés', 'fr' => 'Francés', 'de' => 'Alemán', 'it' => 'Italiano'], 'es', ['class' => 'form-control', 'type' => 'text', 'id' => 'lang', 'placeholder' => 'Idioma del distribuidor...', 'required' => 'required']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('upload-file', 'Logotipo:', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-9">
        <input id="upload-file" placeholder="Logotipo del distribuidor..." class="form-control" readonly />
        <div class="file-upload">
            <span class="btn szd-btn-active">Examinar</span>
            <input id="upload-btn" name="logo" type="file" class="upload" required />
        </div>
    </div>
</div>