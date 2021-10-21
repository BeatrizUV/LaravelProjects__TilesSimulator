<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('plaque-preview', (trans('budget.labels.plaque') . ':'), ['class' => 'col-sm-3 control-label']) !!}
            <div id="plaque-preview"></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('pieces', (trans('budget.labels.pieces.title') . ':'), ['class' => 'col-sm-12 control-label']) !!}
            <div class="col-sm-12" id="plaque-pieces"></div>
        </div>
        <div class="form-group">
            {!! Form::label('quantity', (trans('budget.labels.quantity') . ':'), ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-8 col-md-offset-1">
                {!! Form::text('quantity', null, ['class' => 'form-control', 'type' => 'number', 'id' => 'quantity', 'placeholder' => trans('budget.placeholders.quantity'), 'required' => 'required']) !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    {!! Form::label('name', (trans('budget.labels.name') . ':'), ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('name', null, ['class' => 'form-control', 'type' => 'text', 'id' => 'name', 'placeholder' => trans('budget.placeholders.name'), 'required' => 'required']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('email', (trans('budget.labels.email') . ':'), ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('email', null, ['class' => 'form-control', 'type' => 'email', 'id' => 'email', 'placeholder' => trans('budget.placeholders.email'), 'required' => 'required']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('telephone', (trans('budget.labels.telephone') . ':'), ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('telephone', null, ['class' => 'form-control', 'type' => 'text', 'id' => 'telephone', 'placeholder' => trans('budget.placeholders.telephone'), 'required' => 'required']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('location', (trans('budget.labels.location') . ':'), ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('location', null, ['class' => 'form-control', 'type' => 'text', 'id' => 'location', 'placeholder' => trans('budget.placeholders.location'), 'required' => 'required']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('country', (trans('budget.labels.quantity') . ':'), ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::text('country', null, ['class' => 'form-control', 'type' => 'text', 'id' => 'country', 'placeholder' => trans('budget.placeholders.country'), 'required' => 'required']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('comments', (trans('budget.labels.comments') . ':'), ['class' => 'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::textarea('comments', null, ['class' => 'form-control', 'id' => 'comments', 'placeholder' => trans('budget.placeholders.comments')]) !!}
    </div>
</div>
<div class="form-group">
    <div class="col-sm-12">
        <input type="checkbox" checked="checked" required="required" name="lopd" id="lopd" value="si" /> {{ trans('budget.labels.lopd') }} <a title="LOPD " href="" target="_blank" rel="nofollow">LOPD</a>.
    </div>
</div>

{!! Form::hidden('thumbnail', null, ['id' => 'thumbnail', 'required' => 'required']) !!}
{!! Form::hidden('pieces-list', null, ['id' => 'pieces-list', 'required' => 'required']) !!}
{!! Form::hidden('plaque-id', null, ['id' => 'plaque-id', 'required' => 'required']) !!}

@if ($dist)
    {!! Form::hidden('dist', $showroom->id, ['id' => 'dist']) !!}
@else
    {!! Form::hidden('dist', null, ['id' => 'dist']) !!}
@endif
