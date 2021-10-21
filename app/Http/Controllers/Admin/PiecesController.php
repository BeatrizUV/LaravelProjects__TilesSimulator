<?php

namespace Simulador\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Simulador\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Simulador\Helpers\ManageImages;
use Simulador\Beans\Piece;
use Validator;

/**
 * Clase encargada de la gestión de piezas del simulador.
 * Algunos de los métodos de esta clase sólo son accesibles desde peticiones AJAX.
 * 
 * @author Beatriz Urbano Vega
 */
class PiecesController extends Controller
{
    /**
     * Path en el que se guardan las imágenes de las piezas.
     * 
     * @var string
     */
    private $_PATH = 'piezas';
    
    /**
     * Método encargado de mostrar la página de gestión de piezas.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.pieces');
    }

    /**
     * Método encargado de registrar nuevas piezas en el sistema.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $message = array('message' => '', 'input' => '');
        
        // Arreglamos el nombre de la imagen para que no tenga espacios
        $imageName = str_replace(' ', '-', $request['name']);
        // Pasamos el nombre de la imagen y las reglas de validación al validador
        $validator = Validator::make(['name' => $imageName], ['name'  =>  'required|min:3|unique:pieces,name']);
        
        // Si la validación falla
        if ($validator->fails()) {
            // Establecemos el mensaje de error para mandarlo al panel de administración
            $message['message'] = 'Problemas al intentar validar el nombre de la pieza.';
            $message['input'] = '#name';
        }
        else {
            // Si la validación es correcta
            // Subimos la imagen de la pieza al servidor
            if (ManageImages::upload($request, $imageName, 'image', $this->_PATH)) {
                // Si se sube correctamente
                // Recopilamos los datos de la pieza
                $piece = new Piece(['name' => $imageName, 'image' => $imageName . '.' . $request->file('image')->getClientOriginalExtension()]);            
                // Los grabamos en la base de datos
                $piece->save();
                // Establecemos el mensaje con el resultado de la operación para enviarlo al panel de administración
                $message['message'] = 'La pieza ' . $piece->name . ' ha sido registrada correctamente.';
            }
            else {
                // Si la subida de la imagen falla establecemos el mensaje de error correspondiente
                $message['message'] = 'Problemas al intentar subir la imagen seleccionada.';
                $message['input'] = '#upload-file';
            }
        }
        
        // Y enviamos el resultado de la operación en formato JSON al panel de administración
        return response()->json(['message' => $message['message'], 'input' => $message['input']]);
    }

    /**
     * Método encargado de mostrar una pieza identificada por su nombre.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @param  int  $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {   
        // Buscamos en la base de datos los datos de la pieza indicada
        // Y los mandamos al panel de administración en formato JSON
        return response()->json(Piece::where('name', $name)->first());
    }
    
    /**
     * Método encargado de listar todas las piezas.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @return \Illuminate\Http\Response
     */
    public function showList()
    {
        // Obtenemos todos los datos de todas la piezas registradas en el sistema
        // Y los mandamos al panel de administración en formato JSON
        return response()->json(Piece::all()->sortBy('name', SORT_NATURAL)->values());
    }

    /**
     * Método encargado de actualizar los datos de las piezas.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $message = array('message' => '', 'input' => '');
        $ok = false;
        
        // Buscamos si existe alguna pieza que coincida con la ID recibida
        if (Piece::find($id)->exists()) {
            // Si existe la recogemos de la base de datos
            $piece = Piece::find($id);
            // Limpiamos el nombre de la imagen de espacios en blanco
            $name = str_replace(' ', '-', $request['name']);
            
            // Validamos el nuevo nombre
            if (!$this->validateForUpdate($piece->name, $name)) {
                // Si el formulario trae una imagen nueva
                if ($request->hasFile('image')) {
                    // Borramos la imagen antigua
                    if (ManageImages::delete($this->_PATH, $piece->image)) {
                        // Subimos la imagen nueva
                        if (ManageImages::upload($request, $name, 'image', $this->_PATH)) {
                            // Y actualizamos los datos de la pieza
                            Piece::where('id', $id)->update(array('name' => $name, 'image' => $name . '.' . $request->file('image')->getClientOriginalExtension()));
                            $ok = true;
                        }
                        else {
                            // Si no se sube la imagen indicamos que ha fallado el campo de la imagen del formulario
                            $message['input'] = '#upload-file';
                        }
                    }
                }
                else {
                    // Si no hay imagen que actualizar
                    // Cambiamos el nombre a la imagen actual
                    if (ManageImages::renameFiles($this->_PATH, $piece->image, $name)) {
                        // Dividimos el nombre de la imagen para extraer el formato
                        $tokens = explode('.', $piece->image);
                        // Actualizamos los datos de la pieza
                        Piece::where('id', $id)->update(array('name' => $name, 'image' => $name . '.' . $tokens[1])); 
                        $ok = true;
                    }
                }
                
                // Establecemos el mensaje con el resultado de la operación
                $message['message'] = 'La pieza ' . $name . ' ha sido actualizada correctamente.';
            }
            else {
                // Si el nombre no se valida bien lo indicamos
                $message['input'] = '#name';
            }
            
            // Si ha ocurrido algún error durante la ejecución del proceso
            if (!$ok) {
                // Establecemos el mensaje de error
                $message['message'] = 'Problemas al intentar modificar la pieza "' . $piece->name . '".';
            }
        }
        else {
            // Si la pieza no existe lo indicamos
            $message['message'] = 'Problemas al intentar modificar la pieza. Inténtelo de nuevo más tarde';
        }
       
        // Y devolvemos el resultado de la operación en formato JSON
        return response()->json(['message' => $message['message'], 'input' => $message['input']]);
    }
    
    /**
     * Método encargado de validar los datos de la pieza para su actualización.
     *
     * @param string $oldName
     * @param string $newName
     * @return boolean
     */
    private function validateForUpdate($oldName, $newName) {
        $ok = true;
        
        if ($oldName != $newName) {
            // Miramos si el nombre no existe aún en la base de datos y validamos que esté bien escrito
            $validator = Validator::make(['name' => $newName], ['name'  =>  'required|min:3|unique:pieces,name']);
            $ok = $validator->fails();
        }
        else {
            // si no validamos sólo que esté bien escrito
            $validator = Validator::make(['name' => $newName], ['name'  =>  'required|min:3']);
            $ok = $validator->fails();
        }
        
        return $ok;
    }

    /**
     * Método encargado de eliminar una pieza registrada en el sistema.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Obtenemos los datos de la pieza a eliminar
        $piece = Piece::where('id', $id)->first();
        // Establecemos el mensaje con el resultado del proceso
        $message = 'Pieza "' . $piece->name . '" eliminada correctamente';
        // Eliminamos la imagen de la pieza
        if (ManageImages::delete($this->_PATH, $piece->image)) {
            // Y si se borra eliminamos la placa
            // Hacemos esto para evitar tener imágenes huérfanas en el servidor
            $piece->destroy($id);
        }
        else {
            // Si el proceso falla establecemos el mensaje de error
            $message = 'Imposible eliminar la pieza "' . $piece->name . '"';
        }
        
        // Y devolvemos el resultado de la operación
        return $message;
    }
}