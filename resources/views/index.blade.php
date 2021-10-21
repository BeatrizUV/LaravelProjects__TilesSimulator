@extends('simulator.templates.simulator')

{{--*/ $dist = false /*--}}

@section('lang')
    {{ Session::get('lang') }}
@stop

@section('title')
    {{ trans('simulator.meta.title') }} | DEMOSAICA
@stop

@section('metatags')
    <meta name="title" content="{{ trans('simulator.meta.title') }}" />
    <meta name="keywords" content="{{ trans('simulator.meta.keywords') }}" />
    <meta name="description" content="{{ trans('simulator.meta.description') }}" />
@stop

@section('dist-css')
@stop

@section('header')
    <div class="row szd-header">
        @include('simulator.templates.partials.logo')
        @include('simulator.templates.partials.slogan')
        <div class="col-md-2 szd-return">
            @include('simulator.templates.partials.languages')
            @include('simulator.templates.partials.return')
        </div>
    </div>
@stop

@section('content')
    <div class="row-fluid szd-simulator">
        @include('simulator.templates.partials.carousel')
        @include('simulator.templates.partials.colorizer')
        <div class="col-md-5">
            @include('simulator.templates.partials.mosaic')    
            @include('simulator.templates.partials.colours')
        </div>
    </div>
@stop   

@section('budget')
    <div id="budget" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ trans('budget.window.title') }}</h4>
                </div>
                <div class="modal-body">                      
                    {!! Form::open(array('url' => '/solicitar-presupuesto', 'method' => 'POST', 'name' => 'budget-form', 'id' => 'budget-form', 'class' => 'col-md-12 form-horizontal szd-form')) !!}
                        @include('simulator.templates.partials.budget-fields')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default szd-cancel-btn" data-dismiss="modal">{{ trans('budget.buttons.cancel') }}</button>
                    <input type="submit" class="btn btn-primary szd-send-btn" value="{{ trans('budget.buttons.send') }}" name="enviar" id="enviar" />
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop   