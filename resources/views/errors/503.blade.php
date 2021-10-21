@extends('errors.errors')

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

@section('content')
    <div class="alert alert-warning" role="alert">
        <img title="{{ trans('errors.503.title') }}" src="/images/warning.png" alt="{{ trans('errors.503.title') }}" witdh="190" height="170" />
        <h1>{{ trans('errors.503.h1') }}</h1>
        <h3>{{ trans('errors.503.h3') }}</h3>
        <p>{{ trans('errors.503.text.0') }}</p>
        <p>{{ trans('errors.503.text.1') }}</p>
        <a title="{{ trans('errors.exit-btn') }}" class="btn btn-lg btn-danger" href="{{ route('index') }}">{{ trans('errors.exit-btn') }}</a>
    </div>
@stop     