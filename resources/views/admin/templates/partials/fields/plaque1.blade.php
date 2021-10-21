<div class="form-group">
    {!! Form::label('name', 'Nombre:', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('name', null, ['class' => 'form-control', 'type' => 'text', 'id' => 'name', 'placeholder' => 'Nombre de la placa...', 'required' => 'required', 'maxlength' => '25']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('format', 'Formato:', ['class' => 'col-sm-3 control-label']) !!}
    <div class="col-sm-9">
        {!! Form::text('format', null, ['class' => 'form-control', 'type' => 'text', 'id' => 'format', 'placeholder' => 'Formato de la placa...', 'required' => 'required', 'maxlength' => '15']) !!}
    </div>
</div>