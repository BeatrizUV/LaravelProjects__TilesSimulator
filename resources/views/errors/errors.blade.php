<!DOCTYPE html>
<html lang="@yield('lang')">
    <head>
        <title>@yield('title')</title>
        <meta charset="UTF-8">
        @yield('metatags')
        {!! Html::style('/css/errors.css') !!}
    </head>
    <body>
        <div class="container panel panel-default">
            @yield('content')
        </div>
        
        <p class="copyright">© {{ date('Y', time()) }} {{ env('WEB_NAME') }} | <a title="{{ trans('simulator.legal.title') }}" href="{{ trans('simulator.legal.url') }}" target="_blank" rel="nofollow">{{ trans('simulator.legal.title') }}</a></p>
        
        {!! Html::script('/js/jquery.min.js') !!}
        {!! Html::script('/js/bootstrap.min.js') !!}
    </body>
</html>
