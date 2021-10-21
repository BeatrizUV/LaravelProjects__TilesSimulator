<!DOCTYPE html>
<html lang="@yield('lang')">
    <head>
        <title>@yield('title')</title>
        <meta charset="UTF-8">
        @yield('metatags')
        {!! Html::style('/css/frontend.css') !!}
        @yield('dist-css')
    </head>
    <body>
        <div class="container panel panel-default">
            @yield('header')
            @yield('content')
        </div>
        
        <p class="copyright">© {{ date('Y', time()) }} {{ env('WEB_NAME') }} | <a title="{{ trans('simulator.legal.title') }}" href="{{ trans('simulator.legal.url') }}" target="_blank" rel="nofollow">{{ trans('simulator.legal.title') }}</a></p>
        
        @yield('budget')
        {!! Html::script('/js/jquery.min.js') !!}
        {!! Html::script('/js/bootstrap.min.js') !!}
        {!! Html::script('/js/frontend/cookies/jquery.cookie.min.js') !!}
        
        @include('simulator.templates.partials.scripts')
        @include('simulator.templates.partials.cookies')
    </body>
</html>
