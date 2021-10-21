<!DOCTYPE html>
<html manifest="/nocache.appcache" lang="es">
    <head>
        <title>@yield('title')</title>
        <meta charset="UTF-8">
        {!! Html::style('/css/backend.css') !!}
    </head>
    <body>
        @yield('menu')

        <div class="container panel panel-default">
            @yield('section-title')
            <div class="row">
                @yield('description-left')
                @yield('description-right')
            </div>
            <div class="row">
                @yield('content-left')
                @include('admin.templates.partials.tabs')
                    @yield('content-right-forms')
                @yield('content-right-bottom')
            </div>
        </div>
        
        {!! Html::script('/js/jquery.min.js') !!}
        {!! Html::script('/js/bootstrap.min.js') !!}
        
        @yield('ajax-scripts')  
    </body>
</html>