<?php

namespace Simulador\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Simulador\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Simulador\Helpers\ManageImages;
use Simulador\Beans\ShowRoom;
use Validator;

/**
 * Clase encargada de la gestión de los distribuidores del simulador.
 * Algunos métodos de esta clase son accesibles sólo desde peticiones AJAX.
 * 
 * @author Beatriz Urbano Vega
 */
class ShowRoomsController extends Controller
{
    /**
     * Path en el que se guardan las imágenes de los distribuidores.
     * @var type 
     */
    private $_PATH = 'distribuidores';
    
    /**
     * Método encargado de mostrar la página de gestión de distribuidores.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('admin.showrooms');
    }

    /**
     * Método encargado de registrar nuevos distribuidores en el sistema.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $message = array('message' => '', 'input' => '');        
        // Establecemos los patrones de validación de los datos del distribuidor
        $validator = Validator::make($request->all(), ['company'    => 'required|max:50|unique:showrooms,company',
                                                        'email'     => 'required|max:100|unique:showrooms,email',
                                                        'telephone' => 'required|max:25|unique:showrooms,telephone',
                                                        'logo'      => 'required|max:250',
                                                        'website'   => 'required|max:100|unique:showrooms,website',
                                                        'lang'      => 'required|max:2']);        
        // Si la validación falla
        if ($validator->fails()) {
            // Establecemos el mensaje de error
            $message['message'] = 'Problemas al intentar validar los datos del distribuidor.';            
            $errors = $validator->errors()->toArray();
            $ers = '';            
            // Y asignamos los campos que han fallado en el formulario
            foreach($errors as $key => $error) {
                $ers .= '#'.$key.',';
            }            
            $message['input'] = substr($ers, 0, (strlen($ers)-1));
        }
        else {
            // Si la validación es correcta
            // Establecemos el nombre del logotipo
            $imageName = strtolower(str_replace(' ', '-', $request['company']));
            // Subimos la imagen del logotipo al servidor
            if (ManageImages::upload($request, $imageName, 'logo', $this->_PATH)) {
                // Si se sube correctamente recogemos todos los datos del distribuidor
                $showroom = new ShowRoom(['company' => $request['company'], 
                                          'email' => $request['email'], 
                                          'telephone' => $request['telephone'], 
                                          'logo' => $imageName . '.' . $request->file('logo')->getClientOriginalExtension(),
                                          'website' => $request['website'],
                                          'lang' => $request['lang'],
                                          'slug' => str_replace(' ', '', strtolower($request['company']))
                ]);            
                // Guardamos los datos en la base de datos
                $showroom->save();
                // Y establecemos el mensaje con el resultado de la operación
                $message['message'] = 'El distribuidor ' . $showroom->company . ' ha sido registrado correctamente.';
            }
            else {
                // Si no se sube la imagen establecemos el mensaje de error
                $message['message'] = 'Problemas al intentar subir la imagen seleccionada.';
                $message['input'] = '#upload-file';
            }
        }
        
        // Y devolvemos el resultado de la operación en formato JSON
        return response()->json(['message' => $message['message'], 'input' => $message['input']]);
    }

    /**
     * Método encargado de obtener los datos de un distribuidor con una ID dada.
     * Método accesible sólo a través de peticiones AJAX.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Recogemos los datos del distribuidor y los devolvemos en formato JSON
        return response()->json(ShowRoom::find($id));
    }
    
    /**
     * Método encargado de listar todas los distribuidores.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @return \Illuminate\Http\Response
     */
    public function showList()
    {
        // Obtenemos la lista de distribuidores y la devolvemos en formato JSON
        return response()->json(ShowRoom::all()->sortBy('company', SORT_NATURAL)->values());
    }

    /**
     * Método encargado de modificar los datos de los distribuidores.
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
 
        // Buscamos si existe el distribuidor     
        if (ShowRoom::find($id)->exists()) {
            // Obtenemos sus datos 
            $showroom = ShowRoom::find($id);            
            // Validamos los datos
            $validator = $this->validateForUpdate($showroom->company, $showroom->email, $showroom->telephone, $showroom->website, $request);
            // Si validan correctamente
            if (!$validator->fails()) {
                // Establecemos el nombre de la imagen
                $imageName = strtolower(str_replace(' ', '-', $request['company']));
                // Si el formulario adjunta imagen
                if ($request->hasFile('logo')) {
                    // Borramos la imagen antigua
                    if (ManageImages::delete($this->_PATH, $showroom->logo)) {
                        // Subimos la imagen nueva
                        if (ManageImages::upload($request, $imageName, 'logo', $this->_PATH)) {
                            // Y actualizamos los datos del distribuidor
                            ShowRoom::where('id', $id)->update([
                                'company' => $request['company'], 
                                'email' => $request['email'], 
                                'telephone' => $request['telephone'], 
                                'logo' => $imageName . '.' . $request->file('logo')->getClientOriginalExtension(),
                                'website' => $request['website'],
                                'lang' => $request['lang'],
                                'slug' => str_replace(' ', '', strtolower($request['company']))
                            ]);
                            $ok = true;
                        }
                        else {
                            // Si no se sube la imagen nueva establecemos el error
                            $message['input'] = '#upload-file';
                        }
                    }
                }
                else {
                    // Si el formulario no trae imagen adjunta renombramos la imagen antigua con el nombre nuevo
                    if (ManageImages::renameFiles($this->_PATH, $showroom->logo, $imageName)) {
                        // Obtenemos el formato de la imagen
                        $tokens = explode('.', $showroom->logo);
                        // Y actualizamos el distribuidor
                        ShowRoom::where('id', $id)->update([
                            'company' => $request['company'], 
                            'email' => $request['email'], 
                            'telephone' => $request['telephone'], 
                            'logo' => $imageName . '.' . $tokens[1],
                            'website' => $request['website'],
                            'lang' => $request['lang'],
                            'slug' => str_replace(' ', '', strtolower($request['company']))
                        ]);
                        $ok = true;
                    }
                }
                
                // Asignamos el mensaje con el resultado del proceso
                $message['message'] = 'El distribuidor ' . $showroom->company . ' ha sido actualizado correctamente.';
            }
            else {
                // Si falla la validación establecemos el mensaje de error
                $errors = $validator->errors()->toArray();
                $ers = '';            
                // Y los campos que han fallado
                foreach($errors as $key => $error) {
                    $ers .= '#'.$key.',';
                }            
                $message['input'] = substr($ers, 0, (strlen($ers)-1));
            }
            
            // Si sigue habiendo errores
            if (!$ok) {
                // Establecemos el mensaje de error
                $message['message'] = 'Problemas al intentar modificar el distribuidor "' . $showroom->company . '".';
            }
        }
        else {
            // Si el distribuidor no existe establecemos el mensaje de error
            $message['message'] = 'Problemas al intentar modificar el distribuidor. Inténtelo de nuevo más tarde';
        }
        
        // Y devolvemos el resultado de la operación en formato JSON
        return response()->json(['message' => $message['message'], 'input' => $message['input']]);
    }
    
    /**
     * Método encargado de validar los datos del distribuidor para su actualización.
     * 
     * @param string $oldCompany
     * @param string $oldEmail
     * @param string $oldTelephone
     * @param string $oldWebsite
     * @param string $request
     * @return Validator
     */
    private function validateForUpdate($oldCompany, $oldEmail, $oldTelephone, $oldWebsite,  $request) {   
        $rules = array();
        
        // Establecemos los patrones de validación
        // del nombre del distribuidor
        if ($oldCompany != $request['company']) {
            $rules[] = ['company' => 'required|min:50|unique:showrooms,company'];
        }
        else {
            $rules[] = ['company' => 'required|min:50'];
        }
        // del email del distribuidor
        if ($oldEmail != $request['email']) {
            $rules[] = ['email' => 'required|max:100|unique:showrooms,email'];
        }
        else {
            $rules[] = ['email' => 'required|max:100'];
        }
        // del teléfono del distribuidor
        if ($oldTelephone != $request['telephone']) {
            $rules[] = ['telephone' => 'required|max:25|unique:showrooms,telephone'];
        }
        else {
            $rules[] = ['telephone' => 'required|max:25'];
        }
        // de la web del distribuidor
        if ($oldWebsite != $request['website']) {
            $rules[] = ['website' => 'required|max:100|unique:showrooms,website'];
        }
        else {
            $rules[] = ['website' => 'required|max:100'];
        }
        
        // y el idioma
        $rules[] = ['lang' => 'required|max:2'];
        // Validamos todo
        $validator = Validator::make($request->all(), $rules);
        // Y devolvemos el resultado de la validación
        return $validator;
    }

    /**
     * Método encargado de eliminar distribuidores del sistema.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Obtenemos los datos del distribuidor
        $showroom = ShowRoom::where('id', $id)->first();
        // Establecemos el mensaje con el resultado de la operación
        $message = 'Distribuidor "' . $showroom->company . '" eliminado correctamente';
        // Eliminamos el logotipo del distribuidor
        if (ManageImages::delete($this->_PATH, $showroom->logo)) {
            // Y si se elimina correctamente eliminamos también el distribuidor
            // Hacemos esto para evitar tener imágnees huérfanas en el servidor
            $showroom->destroy($id);
        }
        else {
            // Si no se borra la imagen establecemos el mensaje de error
            $message = 'Imposible eliminar la pieza "' . $showroom->name . '"';
        }
        
        // Y devolvemos el resultado de la operación
        return $message;
    }
}