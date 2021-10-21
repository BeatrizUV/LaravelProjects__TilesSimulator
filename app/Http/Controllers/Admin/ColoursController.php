<?php

namespace Simulador\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Simulador\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Simulador\Helpers\ManageImages;
use Simulador\Beans\Colour;
use Validator;

/**
 * Clase encargada de la gestión de los colores del simulador.
 * Clase con algunos métodos accesibles sólo desde peticiones AJAX.
 * 
 * @author Beatriz Urbano Vega
 */
class ColoursController extends Controller
{
    /**
     * Path en el que se guardan las imágenes de los colores.
     * 
     * @var string
     */
    private $_PATH = 'colores';
    
    /**
     * Método encargado de mostrar la página de gestión de colores.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.colours');
    }

    /**
     * Método encargado de registrar nuevos colores en el sistema.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $message = array('message' => '', 'input' => '');
        
        // Creamos el nombre para la imagen del color
        $imageName = str_replace(' ', '-', $request['name']);
        // Pasamos los valores del formulario y el patrón de validación al validador
        $validator = Validator::make(['name' => $imageName], ['name'  =>  'required|min:3|unique:colours,name']);
        
        // Si la validación falla
        if ($validator->fails()) {
            // Montamos el mensaje de error a mostrar en el panel de administración.
            $message['message'] = 'Problemas al intentar validar el nombre del color.';
            $message['input'] = '#name';
        }
        else {
            // Si la validación es correcta
            // Subimos la imagen del color al servidor
            if (ManageImages::upload($request, $imageName, 'image', $this->_PATH)) {
                // Recopilamos los datos del nuevo color
                $colour = new Colour(['name' => $imageName, 'image' => $imageName . '.' . $request->file('image')->getClientOriginalExtension()]);            
                // Lo grabamos en la base de datos
                $colour->save();
                // Y montamos el mensaje a mostrar en el panel de administración
                $message['message'] = 'El color ' . $colour->name . ' ha sido registrado correctamente.';
            }
            else {
                // Si la subida de la imagen falla
                // Montamos el mensaje de error a mostrar en el panel de administración
                $message['message'] = 'Problemas al intentar subir la imagen seleccionada.';
                $message['input'] = '#upload-file';
            }
        }
        
        // Mandamos el mensaje con el resultado de la operación en formato JSON al panel de administración
        return response()->json(['message' => $message['message'], 'input' => $message['input']]);
    }
    
    /**
     * Muestra los datos de un color específico mediante su nombre.
     * Método accesible sólo desde peticiones AJAX.
     * 
     * @param  int  $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        // Recogemos el color que coincida con el nombre recibido de la base de datos
        // Y lo mandamos en formato JSON al panel de administración
        return response()->json(Colour::where('name', $name)->first());
    }
    
    /**
     * Método encargado de listar toda la paleta de colores.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @return \Illuminate\Http\Response
     */
    public function showList()
    {
        // Recogemos la lista de colores registrados en la base de datos
        // Y los enviamos en formato JSON al panel de administración.
        return response()->json(Colour::all()->sortBy('name', SORT_NATURAL)->values());
    }

    /**
     * Método encargado de actualizar los datos de un color especificado por su ID.
     * Método accesible sólo mediante peticiones AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $message = array('message' => '', 'input' => '');
        $ok = false;
        
        // Buscamos si existe algún color que corresponda con la ID recibida             
        if (Colour::find($id)->exists()) {
            // Si existe, lo extraemos de la base de datos
            $colour = Colour::find($id);
            // Limpiamos de espacios en blanco el nombre del color para evitar problemas con la subida de la imagen nueva
            $name = str_replace(' ', '-', $request['name']);
            
            // Si se valida correctamente el nombre recibido
            if (!$this->validateForUpdate($colour->name, $name)) {
                // Miramos si se ha mandado una nueva imagen para el color o no
                if ($request->hasFile('image')) {
                    // Si hay una imagen nueva borramos la imagen antigua del servidor
                    if (ManageImages::delete($this->_PATH, $colour->image)) {
                        // Si se borra correctamente subimos la nueva imagen con el nombre nuevo
                        if (ManageImages::upload($request, $name, 'image', $this->_PATH)) {
                            // Si se sube correctamente guardamos actualizamos los datos del color en la base de datos
                            Colour::where('id', $id)->update(array('name' => $name, 'image' => $name . '.' . $request->file('image')->getClientOriginalExtension()));
                            $ok = true;
                        }
                        else {
                            // Si no especificamos que ha fallado el campo del formulario correspondiente a la imagen del color
                            $message['input'] = '#upload-file';
                        }
                    }
                }
                else {
                    // Si no hay imagen que actualizar
                    // Cambiamos el nombre de la imagen por el posible nuevo nombre del color
                    if (ManageImages::renameFiles($this->_PATH, $colour->image, $name)) {
                        // Separamos el nombre de la imagen del color en segmentos para obtener el formato
                        $tokens = explode('.', $colour->image);
                        // Y actualizamos los datos del color en la base de datos
                        Colour::where('id', $id)->update(array('name' => $name, 'image' => $name . '.' . $tokens[1])); 
                        $ok = true;
                    }
                }
                
                // Montamos el mensaje para indicar que todo se ha realizado correctamente
                $message['message'] = 'El color ' . $name . ' ha sido actualizado correctamente.';
            }
            else {
                // Si el nombre no se ha validado correctamente indicamos que el campo del nombre en el formulario tiene errores
                $message['input'] = '#name';
            }
            
            // Si ha habido algún problema durante la ejecución del proceso de actualización
            if (!$ok) {
                // Montamos el mensaje de error para enviarlo al panel de administración
                $message['message'] = 'Problemas al intentar modificar el color "' . $colour->name . '".';
            }
        }
        else {
            // Si la ID recibida no corresponde con ningún color en la base de datos avisamos del error
            $message['message'] = 'Problemas al intentar modificar el color. Inténtelo de nuevo más tarde';
        }
       
        // Y mandamos el resultado de la operación al panel de administración en formato JSON
        return response()->json(['message' => $message['message'], 'input' => $message['input']]);
    }
    
    /**
     * Método encargado de validar el nombre del color cuando se realiza una actualización.
     * Método privado al que sólo se accede a través del método update().
     * 
     * @param string $oldName
     * @param string $newName
     * @return boolean
     */
    private function validateForUpdate($oldName, $newName) {
        $ok = true;
        
        // Compramos el nombre antiguo con el nuevo nombre
        if ($oldName != $newName) {
            // Miramos si el nombre no existe aún en la base de datos y validamos que esté bien escrito
            $validator = Validator::make(['name' => $newName], ['name'  =>  'required|min:3|unique:colours,name']);
            $ok = $validator->fails();
        }
        else {
            // Si el nombre coincide, sólo revisamos que esté bien escrito
            $validator = Validator::make(['name' => $newName], ['name'  =>  'required|min:3']);
            $ok = $validator->fails();
        }
        
        return $ok;
    }

    /**
     * Método encargado de eliminar colores del sistema.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Obtenemos los datos del color a eliminar
        $colour = Colour::where('id', $id)->first();
        // Montamos el mensaje a mostrar en el panel de administración
        $message = 'Color "' . $colour->name . '" eliminado correctamente';
        // Eliminamos primero la imagen del color
        if (ManageImages::delete($this->_PATH, $colour->image)) {
            // Y si se borra correctamente, entonces borramos el color de la base de datos
            // Esto se hace así para evitar que queden imágenes muertas en el servidor
            $colour->destroy($id);
        }
        else {
            // Si no se borra la imagen avisamos del error en el panel de administración
            $message = 'Imposible eliminar el color "' . $colour->name . '"';
        }
        
        // Mandamos el resultado de la operación al panel de administración
        return $message;
    }
}