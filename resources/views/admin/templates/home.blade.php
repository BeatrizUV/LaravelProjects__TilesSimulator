<!DOCTYPE html>
<html lang="es">
    <head>
        <title>@yield('title')</title>
        <meta charset="UTF-8">
        {!! Html::style('/css/backend.css') !!}
    </head>
    <body>
        <div class="navbar-header col-md-12 szd-bg-header">
            <a title="Simulador" class="navbar-brand" href="/admin"><img title="Simulador" src="/images/logo.png" alt="Simulador" width="32" height="32" /> Simulador</a>
            <ul class="nav nav-pills pull-right">
                <li class="szd-help-btn">
                    <a title="Ayuda" href="/admin/ayuda"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a>
                </li>
                <li>
                    <a title="Colores" href="/admin/colores"><span class="glyphicon icon-eyedropper" aria-hidden="true"></span> Colores</a>
                </li>
                <li>
                    <a title="Piezas" href="/admin/piezas"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> Piezas</a>
                </li>
                <li>
                    <a title="Placas" href="/admin/placas"><span class="glyphicon glyphicon-th" aria-hidden="true"></span> Placas</a>
                </li>
                <li>
                    <a title="Distribuidores" href="/admin/distribuidores"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Distribuidores</a>
                </li>
                <li>
                    <a title="Ir al simulador" href="/" target="_blank">Ir al simulador <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></a>
                </li>
            </ul>  
        </div>

        <div class="container panel panel-default">
            @yield('content')
        </div>
    </body>
</html>
