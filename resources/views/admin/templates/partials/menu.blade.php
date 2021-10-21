<div class="navbar-header col-md-12 szd-bg-header">
        <a title="Simulador" class="navbar-brand" href="{{ route('admin') }}"><img title="Simulador" src="/images/logo.png" alt="Simulador" width="32" height="32" /> Simulador</a>
        <ul class="nav nav-pills pull-right">
            <li class="szd-help-btn">
                <a title="Ayuda" href="{{ route('admin.help') }}"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a>
            </li>
            <li>
                <a title="Colores" href="{{ route('admin.colores.index') }}" 
                   @if ($current == 'colour')
                        class="current"
                   @endif
                ><span class="glyphicon icon-eyedropper" aria-hidden="true"></span> Colores</a>
            </li>
            <li>
                <a title="Piezas" href="{{ route('admin.piezas.index') }}" 
                   @if ($current == 'piece')
                        class="current"
                   @endif
                ><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span> Piezas</a>
            </li>
            <li>
                <a title="Placas" href="{{ route('admin.placas.index') }}" 
                   @if ($current == 'plaque')
                        class="current"
                   @endif
                ><span class="glyphicon glyphicon-th" aria-hidden="true"></span> Placas</a>
            </li>
            <li>
                <a title="Distribuidores" href="{{ route('admin.distribuidores.index') }}" 
                   @if ($current == 'showroom')
                        class="current"
                   @endif
                ><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Distribuidores</a>
            </li>
            <li>
                <a title="Ir al simulador" href="{{ route('index') }}" target="_blank">Ir al simulador <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></a>
            </li>
        </ul>  
    </div>
