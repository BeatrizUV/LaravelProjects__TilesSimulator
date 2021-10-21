@extends('admin.templates.home')

@section('title')
    Simulador | Panel de administración
@stop

@section('content')
            <div class="page-header">
                <h1>Panel de administración</h1>
            </div>
            
            <div class="alert alert-warning text-center" role="alert">
                <h3 class="panel-title">Bienvenido al panel de administración del simulador</h3>
                <p>A continuación podrá acceder a las diferentes herramientas disponibles en esta aplicación.</p>
                <small>(Si tiene alguna duda consulte la <a title="Ayuda" href="/admin/ayuda" class="alert-link">ayuda</a> en el botón azul del menú superior)</small>
            </div>
            
            <div class="row home">
                <div class="panel panel-default col-md-4 szd-margin-left">
                    <div class="page-header">
                        <a title="Colores" href="/admin/colores" aria-hidden="true"><span class="glyphicon icon-eyedropper" aria-hidden="true"></span> <h2>Colores
                            <br /><small>Añade, modifica y elimina la paleta de colores</small></h2></a>
                    </div>
                </div>
                <div class="panel panel-default col-md-4 col-md-offset-1">
                    <div class="page-header">
                        <a title="Piezas" href="/admin/piezas" aria-hidden="true"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> <h2>Piezas
                            <br /><small>Añade, modifica y elimina piezas para formar placas</small></h2></a>
                    </div>
                </div>
            </div>
            
            <div class="row home">
                <div class="panel panel-default col-md-4 szd-margin-left">
                    <div class="page-header">
                        <a title="Placas" href="/admin/placas" aria-hidden="true"><span class="glyphicon glyphicon-th" aria-hidden="true"></span> <h2>Placas
                            <br /><small>Añade, modifica y elimina placas</small></h2></a>
                    </div>
                </div>
                <div class="panel panel-default col-md-4 col-md-offset-1">
                    <div class="page-header">
                        <a title="Distribuidores" href="/admin/distribuidores" aria-hidden="true"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <h2>Distribuidores
                            <br /><small>Añade, modifica y elimina simuladores personalizados</small></h2></a>
                    </div>
                </div>
            </div>
@stop        



