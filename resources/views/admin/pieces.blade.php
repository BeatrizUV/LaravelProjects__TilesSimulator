@extends('admin.templates.manage')

{{-- Página actual --}}
{{--*/ $current = 'piece' /*--}}

@section('title')
    Simulador | Gestión de piezas
@stop

@section('menu')
    @include('admin.templates.partials.menu')
@stop

@section('section-title')
    <div class="page-header">
        <h1>Gestión de piezas</h1>
    </div>
@stop

@section('description-left')
    <div class="container col-md-7">
        <div class="alert alert-warning col-md-12">
            Para modificar o eliminar alguna de las piezas existentes, pinche sobre la miniatura correspondiente y los datos de dicha pieza aparecerán en el formulario de la derecha.
        </div>
    </div>
@stop

@section('description-right')
    <div class="container col-md-5 pull-right">
        <div class="alert alert-warning col-md-12 text-right pull-right">
            Con este formulario puede añadir, editar o eliminar piezas del simulador. No cree piezas que contengan espacios en el nombre.
        </div>
    </div>
@stop

@section('content-left')
    <div class="container col-md-7 tabbable" id="data-list">
        <ul class="nav nav-tabs gris">
            <li role="presentation" class="active"><a>Listado de piezas</a></li>
        </ul>
        <div class="tab-content container-fluid">
            <div class="tab-pane panel panel-default active" id="list" class="active">
                <div class="container szd-display-none" id="pieces-list"></div>
            </div>
        </div>
    </div>
@stop

@section('content-right-forms')
            <div class="tab-pane panel panel-default active container" id="tab-add-form" class="active" role="tabpanel">
                @include('admin.templates.partials.forms.add-piece')
            </div>
            <div class="tab-pane panel panel-default container" id="tab-edit-delete-form" role="tabpanel">
                @include('admin.templates.partials.forms.edit-delete-piece')
            </div>
@stop

@content('content-right-bottom')
        </div>
    </div>
@stop

@section('ajax-scripts')
    {!! "<script>var uploadDir = '/" . env('UPLOAD_DIR') . "/piezas';</script>" !!}
    {!! Html::script('/js/backend/scripts.min.js') !!}
    {!! Html::script('/js/backend/partials/pieces.min.js') !!}
@stop
