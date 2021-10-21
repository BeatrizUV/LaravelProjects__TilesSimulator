@extends('admin.templates.home')

@section('title')
    Simulador | AYUDA
@stop

@section('content')
            <div class="page-header">
                <h1>Instrucciones de uso</h1>
            </div>
            
            <div class="alert alert-warning text-center" role="alert">
                <h3 clas="panel-title">Bienvenido al manual de instrucciones del simulador</h3>
                <p>A continuación podrá consultar el funcionamiento de cada sección dispuesta en este panel.</p>
                <small>(Si tiene alguna duda envíe un email a <a title="Email de consulta" href="mailto:" class="alert-link"></a>)</small>
            </div>
            
            <div class="row container-fluid help-panel col-md-10 col-md-offset-1">
                <div class="alert alert-info" role="alert">
                    <strong>SECCIONES:</strong>
                    <ul>
                        <li><a href="#colores" title="Colores">Colores</a></li>
                        <li><a href="#piezas" title="Piezas">Piezas</a></li>
                        <li><a href="#placas" title="Placas">Placas</a></li>
                        <li><a href="#distribuidores" title="Distribuidores">Distribuidores</a></li>
                    </ul>
                </div>
                <h3><a name="colores">1. COLORES:</a></h3>
                <p>La paleta de colores nos permitirá asignar el color que queramos a las diferentes piezas que conformen nuestras placas.</p>
                <p>Para registrar un color en la paleta de colores tan sólo es necesario indicar el nombre del color y adjuntar una imagen de una placa de piezas de 3x3 del color correspondiente.
                    La imagen del color debe ser de <strong>500x500px</strong> y estar en formato </strong>JPG</strong>.</p>
                <p>Para modificar y/o eliminar un color de la paleta de colores tan sólo se debe hacer click en el color correspondiente y sus datos aparecerán en el formulario de la derecha.</p>
                <p><img title="Panel de gestión de colores" src="/images/admin/colores-01.png" alt="Panel de gestión de colores" class="center-block" /></p>
                <hr />
                <h3><a name="piezas">2. PIEZAS:</a></h3>
                <p>El listado de piezas nos permitirá asignar las piezas que queramos a las diferentes placas que tengamos registradas en el sistema.</p>
                <p>Para registrar una pieza en el sistema tan sólo es necesario indicar el nombre de la pieza y adjuntar una imagen de la misma con fondo blanco y líneas en negro.
                    La imagen de la pieza debe ser de <strong>250x250px</strong> y estar en formato <strong>JPG</strong>.</p>
                <p>Para modificar y/o eliminar una pieza del sistema tan sólo se debe hacer click en la pieza correspondiente y sus datos aparecerán en el formulario de la derecha.</p>
                <p><img title="Panel de gestión de piezas" src="/images/admin/piezas-01.png" alt="Panel de gestión de piezas" /></p>
                <hr />
                <h3><a name="placas">3. PLACAS:</a></h3>
                <p>El listado de placas gestionar la configuración de cada placa registrada en el sistema.</p>
                <p>Para registrar una placa hace falta indicar:
                    <ol>
                        <li>Nombre</li>
                        <li>Formato (cm)</li>
                        <li>Miniatura (jpg/png)</li>
                        <li>Lista de piezas (tipo de pieza, color por defecto, nodos y estado)</li>
                    </ol>
                </p>
                <p>El formato de la miniatura debe ser de <strong>250x250px</strong> y el formato <strong>JPG o PNG</strong>.</p>
                <p>Para asignar piezas a la placa que se quiera registrar, primero se debe hacer click sobre el icono de la pieza elegida y entonces
                    aparecerá un serie de campos con información sobre la pieza.
                <p>Para asignarle un color por defecto hay que hacer click en la miniatura de la pieza escogida.</p>
                <p><img title="Abrir las placas vectorizadas en Inkscape" src="/images/admin/placas-01.png" alt="Abrir las placas vectorizadas en Inkscape" /></p>
                <p>Para asignar los nodos con los que se dibujarán las piezas en el simulador hay que abrir la placa, previamente vectorizada, en un programa vectorial (recomendamos Inkscape). 
                    Una vez abierta la placa en el programa, deberemos ir a <i>Ver/Editor XML...</i>. Seleccionamos la herramienta de "Nodos" y hacemos click en la pieza que queremos añadir a la placa. 
                    En la ventana de la derecha nos aparecerán las coordenadas que el simulador interpretará para dibujar la pieza posteriormente.</p>
                <p><img title="Obtener los nodos de cada pieza para agregarla al simulador" src="/images/admin/placas-02.png" alt="Obtener los nodos de cada pieza para agregarla al simulador" /></p>
                <p>Por último, podemos decidir si el usuario puede cambiar el color por defecto de cada pieza de las placas registradas. Para bloquear y/o desbloquear el color de una pieza, tan sólo tenemos que hacer click
                 en el botón del candado que aparece a la derecha de cada pieza agregada a la placa. Si está azul es que está desbloqueada y si está en amarillo es que está bloqueada y no se podrá modificar su color desde el simulador.</p>
                <p>Como último detalle, en caso de querer borrar una pieza de la placa seleccionada, tan sólo se debe hacer click en el botón rojo con la x que aparece a la derecha de cada pieza.</p>
                <p><img title="Gestionar una placa" src="/images/admin/placas-03.png" alt="Gestionar una placa" /></p>
                <hr />
                <h3><a name="distribuidores">4. DISTRIBUIDORES:</a></h3>
                <p>El listado de distribuidores sirve para crear una serie de simuladores personalizados para los clientes que así lo soliciten.</p>
                <p>Para registrar un distribuidor en el sistema hay que indicar:
                    <ol>
                        <li>Nombre de la empresa</li>
                        <li>E-mail para la recepción de los presupuestos</li>
                        <li>Teléfono de contacto</li>
                        <li>URl de su página web</li>
                        <li>Idioma por defecto del distribuidor</li>
                        <li>Logotipo de la empresa (png)</li>
                    </ol>
                </p>
                <p>Todos estos datos aparecerán en el simulador del distribuidor si se accede a la url del mismo, por ejemplo, <i>http://localhost:8080/nombre-distribuidor</i>. Además, se podrán añadir estilos al simulador del distribuidor 
                mediante una hoja de estilos propia.</p>
                <p>Los presupuestos que se envíen desde el simulador personalizado llegarán al email indicado por el distribuidor y el simulador aparecerá, por defecto, en el idioma nativo del distribuidor.</p>
                <p>Por último, el logotipo de la empresa deberá ser en formato <strong>PNG</strong> y el formato de <strong>150x90px</strong>.</p>
                <p><img title="Gestión de distribuidores" src="/images/admin/distribuidores-01.png" alt="Gestión de distribuidores" /></p>
            </div>
@stop        



