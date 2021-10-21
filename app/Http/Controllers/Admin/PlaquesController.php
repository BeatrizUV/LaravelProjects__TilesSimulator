<?php

namespace Simulador\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Simulador\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Simulador\Helpers\ManageImages;
use Simulador\Beans\Plaque;
use Simulador\Beans\Piece;
use Validator;
use DB;

/**
 * Clase encargada de la gestión de las placas del simulador.
 * Clase con algunos de sus métodos accesibles sólo desde peticiones AJAX.
 * 
 * @author Beatriz Urbano Vega
 */
class PlaquesController extends Controller
{
    /**
     * Path en el que se guardan las imágenes de las placas.
     * 
     * @var string 
     */
    private $_PATH = 'placas';
    
    /**
     * Lista de piezas de la placa.
     * 
     * @var array 
     */
    private $piecesList = array();
    
    /**
     * Método encargado de mostrar la página principal de la gestión de placas.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.plaques');
    }

    /**
     * Método encargado de registrar nuevas placas en el sistema.
     * Métod accesible sólo desde peticiones AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       
        $message = array('message' => '', 'input' => '');
        $error = false;
        $ok = true;
        
        // Validamos la placa
        $validator = $this->validatePlaque(null, $request);
        
        // Si la validación es correcta
        if ($validator == true) {
            // Establecemos el nombre de la imagen de la placa
            $imageName = strtolower(str_replace(' ', '-', $request['name']));
            // Subimos la imagen de la placa al servidor
            if (ManageImages::upload($request, $imageName, 'thumbnail', $this->_PATH)) {
                // Si se sube correctamente
                // Obtenemos los datos de la placa
                $plaque = new Plaque(['name'      => $request['name'], 
                                      'format'    => $request['format'], 
                                      'thumbnail' => $imageName . '.' . $request->file('thumbnail')->getClientOriginalExtension()
                                    ]); 
                // Guardamos los datos en la base de datos
                if ($plaque->save()) {                    
                    // Si se guardan correctamente
                    // Obtenemos el listado de piezas asignadas a la placa
                    foreach($this->piecesList as $piece) {
                        $pieceArray = ['piece_id'       => $piece->id,
                                       'plaque_id'      => $plaque->id,
                                       'nodes'          => $piece->nodes,
                                       'default_colour' => $piece->colour,
                                       'locked'         => $piece->isLocked
                                      ];
                        // Y las registramos una por una en la base de datos
                        if (!DB::table('pieces_into_plaques')->insert($pieceArray)) {
                            $error = true;
                        }
                    }
                    
                    // Si ha habido errores durante la ejecución del proceso
                    if ($error) {
                        // Borramos la placa recién registrada
                        $plaqueId = $plaque->id;
                        $plaque->destroy($plaqueId);
                        $ok = false;
                    }
                }
                
                // Si ya no hay errores
                if ($ok) {
                    // Establecemos el mensaje con el resultado de la operación
                    $message['message'] = 'La placa ' . $plaque->name . ' ha sido registrada correctamente';
                }
                else {
                    // Y si sigue habiendo errores
                    // Establecemos el mensaje de error
                    $message['message'] = 'La placa ' . $plaque->name . ' no ha sido registrada';
                }
            }
            else {
                // Si la imagen no se sube establecemos el mensaje de error
                $message['message'] = 'Problemas al intentar subir la imagen seleccionada.';
                $message['input'] = '#upload-file';
            }
        }
        else {
            // Si los datos de la placa no se validan bien
            // Recogemos los errores del formulario
            $errors = $validator->errors()->toArray();
            $ers = '';            
            // Guardamos los campos erróneos
            foreach($errors as $key => $error) {
                $ers .= '#'.$key.',';
            }            
            // Establecemos el mensaje de error
            $message['input'] = substr($ers, 0, (strlen($ers)-1));
            $message['message'] = 'Problemas al intentar validar los datos de la placa';
        }
        
        // Y devolvemos el resultado de la operación en formato JSON
        return response()->json(['message' => $message['message'], 'input' => $message['input']]);
    }

    /**
     * Método encargado de mostrar los datos de una placa según su ID.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {        
        // Buscamos si la placa existe en la base de datos
        if (Plaque::find($id)->exists()) {
            // Si existe recogemos sus datos
            $plaque = Plaque::find($id); 
            
            // Recogemos la lista de piezas relacionadas con esta placa
            $pieces = DB::table('pieces_into_plaques')->where('plaque_id', $id)->get();            
            if ($pieces != false) {
                // Si hay piezas las recogemos
                $piecesList = array();
                foreach($pieces as $p) {
                    $piece = Piece::find($p->piece_id);                   
                    $piece->colour = $p->default_colour;
                    $piece->isLocked = $p->locked;
                    $piece->nodes = $p->nodes;
                    $piecesList[] = $piece;
                } 
                
                // Y se las asignamos a la placa
                $plaque->piecesList = $piecesList;
            }
        }
        
        // Y devolvemos los datos de la placa en formato JSON
        return response()->json($plaque);
    }
    
    /**
     * Método encargado de listar todas las placas.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @return \Illuminate\Http\Response
     */
    public function showList()
    {
        // Obtenemos todas las placas registradas en el sistema
        // Y las mandamos al panel de administración en formato JSON
        return response()->json(Plaque::all()->sortBy('company', SORT_NATURAL)->values());
    }

    /**
     * Método encargado de realizar actualizaciones en una placa seleccionada.
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
 
        // Buscamos si la placa seleccionada existe en la base de datos              
        if (Plaque::find($id)->exists()) {
            // Si existe obtenemos sus datos
            $plaque = Plaque::find($id); 
            
            // Validamos los nuevos datos de la placa
            $validator = $this->validatePlaque($plaque->name, $request);
            
            if ($validator == true) { 
                // Si validan correctamente
                // Establecemos el nombre de la nueva imagen
                $imageName = strtolower(str_replace(' ', '-', $request['name']));
                if ($request->hasFile('thumbnail')) {
                    // Si el formulario incluye una imagen
                    // Si hay una imagen nueva borramos la imagen antigua del servidor
                    if (ManageImages::delete($this->_PATH, $plaque->image)) {
                        // Si se borra correctamente subimos la imagen nueva
                        if (ManageImages::upload($request, $imageName, 'thumbnail', $this->_PATH)) {
                            // Y montamos el query para la actualización de la placa
                            $query = ['name'      => $request['name'], 
                                      'format'    => $request['format'], 
                                      'thumbnail' => $imageName . '.' . $request->file('thumbnail')->getClientOriginalExtension()];
                            $ok = true;
                        }
                        else {
                            // Si no se sube la imagen nueva establecemos el mensaje de error
                            $message['message'] = 'Problemas al intentar subir la imagen seleccionada.';
                            $message['input'] = '#upload-file';
                        }
                    }
                }
                else {
                    // Si el formulario no incluye una nueva imagen para la placa
                    // Renombramos la imagen actual con el nuevo nombre
                    if (ManageImages::renameFiles($this->_PATH, $plaque->thumbnail, $imageName)) {
                        // Extraemos el formato de la imagen actual
                        $tokens = explode('.', $plaque->thumbnail);
                        // Y montamos el query para la actualización de la placa
                        $query = ['name'      => $request['name'], 
                                  'format'    => $request['format'], 
                                  'thumbnail' => $imageName . '.' . $tokens[1]];
                        $ok = true;
                    }
                    else {
                        // Si no se puede renombrar la imagen establecemos el mensaje de error
                        $message['message'] = 'Problemas al intentar modificar la imagen de la placa.';
                        $message['input'] = '#upload-file';
                    }
                }
                
                // Si no ha habido errores durante la ejecución del proceso
                if ($ok) {
                    // Actualizamos los datos de la placa y sus piezas
                    if ($this->updatePlaque($id, $query)) {
                        // Si se actualiza correctamente establecemos el mensaje con el resultado de la operación
                        $message['message'] = 'La placa ' . $plaque->name . ' ha sido modificada correctamente';
                    }
                    else {
                        // Si no se actualiza
                        // Cambiamos el nombre de la imagen al nombre antiguo
                        ManageImages::renameFiles($this->_PATH, $query['thumbnail'], $plaque->thumbnail);
                        // Y establecemos el mensaje de error
                        $message['message'] = 'La placa ' . $plaque->name . ' no ha sido modificada';
                    }
                }
            }
            else {
                // Si los datos de la placa no se validan correctamente
                // Recogemos los errores
                $errors = $validator->errors()->toArray();
                $ers = '';            
                // Los campos erróneos
                foreach($errors as $key => $error) {
                    $ers .= '#'.$key.','.implode($error);
                }            
                // Y establecemos el mensaje de errores en el formulario
                $message['input'] = substr($ers, 0, (strlen($ers)-1));
                $message['message'] = 'Problemas al intentar validar los datos de la placa';
            }
        }
        else {
            // Si la placa no existe establecemos el mensaje de error
            $message['message'] = 'Problemas al intentar modificar la placa. La placa indicada no existe';
        }
       
        // Y devolvemos el resultado de la operación en formato JSON
        return response()->json(['message' => $message['message'], 'input' => $message['input']]);
    }
    
    /**
     * Método encargado de actualizar los datos de la placa y sus piezas.
     * 
     * @param int $id
     * @param array $query
     * @return boolean
     */
    private function updatePlaque($id, $query) {
        $ok = true;
        
        // Actualizamos los datos de la placa
        Plaque::where('id', $id)->update($query);
        
        // Eliminamos las anteriores relaciones con las piezas
        if (DB::table('pieces_into_plaques')->where(['plaque_id' => $id])->delete()) {                
            foreach($this->piecesList as $piece) {
                $pieceArray = ['piece_id'       => $piece->id,
                               'plaque_id'      => $id,
                               'nodes'          => $piece->nodes,
                               'default_colour' => $piece->colour,
                               'locked'         => $piece->isLocked
                              ];
                // Y registramos las nuevas relaciones con las nuevas piezas
                if (!DB::table('pieces_into_plaques')->insert($pieceArray)) {
                    $ok = false;
                }
            }
        }
        
        // Y devolvemos el resultado de la operación
        return $ok;
    }
    
    /**
     * Método encargado de validar los datos de la placa.
     * 
     * @param string $oldName
     * @param \Illuminate\Http\Request $request
     * @return boolean
     */
    private function validatePlaque($oldName, $request) {        
        $errors = 0;
        // Establecemos el patrón de valicación del nombre de la placa
        $nameRules = 'required|max:25|unique:plaques,name';
        
        if ($oldName != null) {
            if ($oldName == $request['name']) {
                $nameRules = 'required|max:25';
            }
        }
        
        // Establecemos el resto de los datos de validación de la placa
        $plaqueValidator = ['name'      => $nameRules,
                            'format'    => 'required|max:15'];
        
        if ($oldName == null) {
            $plaqueValidator['thumbnail'] = 'required|max:250';
        }
        
        // Establecemos los datos de validación de las piezas
        $piecesValidator = ['id'        => 'required|exists:pieces,id',
                            'colour'    => 'required|exists:colours,name',
                            'nodes'     => 'required',
                            'locked'    => 'required'];
        
        // Validamos los datos de la placa
        $validator = Validator::make($request->all(), $plaqueValidator);
        
        // Si la validación es correcta
        if (!$validator->fails()) {
            // Obtenemos el número de placas del formulario
            $size = $request['pieces'];
            $cont = 1;
            
            if ($size > 0) {
                $pieces = 0;
                for($cont = 1; $cont <= $size; $cont++) {
                    // Obtenemos los datos de cada pieza
                    if ($request['piece_' . $cont . '_id']) {
                        $pieceArray = ['id'     => $request['piece_' . $cont . '_id'],
                                       'colour' => $request['piece_' . $cont . '_colour'],
                                       'nodes'  => $request['piece_' . $cont . '_nodes'],
                                       'locked' => $request['piece_' . $cont . '_locked']];
                        
                        // Validamos cada pieza
                        $validator = Validator::make($pieceArray, $piecesValidator);

                        // Si valida correctamente
                        if (!$validator->fails()) {
                            // Recopilamos los datos de cada pieza
                            $piece = new Piece();
                            $piece->id = $pieceArray['id'];
                            $piece->colour = $pieceArray['colour'];
                            $piece->nodes = $pieceArray['nodes'];
                            $piece->isLocked = $pieceArray['locked'];                    
                            $this->piecesList[] = $piece;
                        }
                        else {
                            // Si falla la validación recopilamos los errores generados
                            $validator->errors()->add('pieces-panel', 'Problemas al validar las piezas de la placa');
                            $errors++;
                        }
                        
                        // Se controla cada pieza que se manda desde el formulario
                        $pieces++;
                    }
                }
                // Si el número de piezas recogidas es 0 
                if ($pieces == 0) {
                    // Se establece el mensaje de error correspondiente
                    $validator->errors()->add('pieces-panel', 'No se puede dejar una placa sin piezas');
                    $errors++;
                }
            }
            else {
                // Si no hay piezas para recoger del formulario
                // Se establece el mensaje de error correspondiente
                $validator->errors()->add('pieces-panel', 'No se puede dejar una placa sin piezas');
                $errors++;
            }
        }
        else {
            // Controlamos si falla la validación de la placa
            $errors++;
        }
        
        // Revisamos si hay errores
        if ($errors == 0) {
            $validator = true;
        }
        
        // Y devolvemos el resultado de la validación
        return $validator;
    }

    /**
     * Método encargado de eliminar una placa de la base de datos.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Obtenemos los datos de la placa seleccionada
        $plaque = Plaque::where('id', $id)->first();
        // Establecemos le mensaje con el resultado de la operación
        $message = 'Placa "' . $plaque->name . '" eliminada correctamente';
        // Eliminamos la imagen
        if (ManageImages::delete($this->_PATH, $plaque->thumbnail)) {
            // Y eliminamos la placa
            $plaque->destroy($id);
        }
        else {
            // Si no se elimina la imagen establecemos el mensaje de error
            $message = 'Imposible eliminar la placa "' . $plaque->name . '"';
        }        
        
        // Y devolvemos el resultado de la operación
        return $message;
    }
}