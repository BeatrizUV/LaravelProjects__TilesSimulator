<?php

namespace Simulador\Http\Controllers;

use Illuminate\Http\Request;
use Simulador\Http\Controllers\Controller;
use Simulador\Beans\Plaque;
use Simulador\Beans\Showroom;
use Simulador\Helpers\ManageImages;
use Validator;
use Mail;

/**
 * Clase encargada de gestionar los presupuestos enviados desde el simulador.
 * Todos los métodos de esta clase se acceden sólo desde peticiones AJAX.
 * 
 * @author Beatriz Urbano Vega
 */
class BudgetsController extends Controller
{
    /**
     * Path en el que se guardan las imágenes de los colores.     
     * @var string
     */
    private $coloursPath = 'colores';
    
    /**
     * Path en el que se guardan las imágenes de las piezas.     
     * @var string 
     */
    private $piecesPath = 'piezas';
    
    /**
     * Path en el que se guardarán las imágenes temporales de las placas.
     * @var type 
     */
    private $tempPath = '_tmp';
    
    /**
     * E-mail por defecto la empresa.
     * @var string
     */
    private $salesEmail;
    
    /**
     * Nombre por defecto de la empresa.
     * @var string
     */
    private $salesName;
    
    /**
     * Método encargado de enviar los presupuestos.
     * Método accesible sólo desde peticiones AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
	$this->salesEmail = env('MAIL_USERNAME');
	$this->salesName = env('WEB_NAME');

        $message = array('message' => '', 'input' => '');
        $error = true;

        // Establecemos los patrones de validación del presupuesto
        $rules = array('name' => 'required', 'email' => 'required|email', 'telephone' => 'required', 'location' => 'required', 'country' => 'required',
                       'lopd' => 'required', 'pieces-list' => 'required|json', 'thumbnail' => 'required', 'plaque-id' => 'required|exists:plaques,id',
                       'quantity' => 'required|integer|min:1');        
        // Validamos el presupuesto
        $validator = Validator::make($request->all(), $rules);
        
        // Si no valida correctamente
        if ($validator->fails()) {
            // Establecemos el mensaje de error
            $message['message'] = trans('messages.errors.budget-validation');
            $errors = $validator->errors()->toArray();
            $ers = ''; 
            // Y añadimos los campos que han fallado
            foreach($errors as $key => $error) {
                $ers .= '#'.$key.',';
            }            
            $message['input'] = substr($ers, 0, (strlen($ers)-1));
        }
        else {
            // Si valida correctamente
            // Obtenemos los datos de la placa
            $plaque = Plaque::find($request['plaque-id']);
            // Establecemos el nombre de la imagen temporal
            $imageName = $plaque->name . '@' . time() . '.png';
            // Obtenemos la imagen temporal de la placa modificada
            $thumbnail = ManageImages::saveDynamicImage(str_replace('data:image/png;base64,', '', $request['thumbnail']), $imageName, $this->tempPath);
            // Si se carga la imagen correctamente
            if ($thumbnail != false) {
                // Cargamos la lista de piezas
                $plaque->piecesList = json_decode($request['pieces-list']);
                // Y asignamos la imagen temporal de la placa
                $plaque->thumbnail = $thumbnail;

                // Recogemos los datos del cliente
                $customer = array('name' => $request['name'], 'email' => $request['email'], 'telephone' => $request['telephone'], 'location' => $request['location'], 'country' => $request['country']);

                // Recogemos todos los datos del formulario para mostrarlos en el email
                $budget = array('customer' => $customer, 'plaque' => $plaque, 'quantity' => $request['quantity'], 'comments' => $request['comments'], 'piecesPath' => $this->piecesPath,
                                'coloursPath' => $this->coloursPath, 'appUrl' => env('APP_URL'));
                
                // Obtenemos el distribuidor en caso de acceder a un simulador personalizado
                $showroomId = $request['dist'];

                // Si el valor no está vacío 
                if ($showroomId != null) {
                    // Obtenemos los datos del distribuidor
                    $showroom = Showroom::find($showroomId);
                    // Asignamos el email y el nombre del distribuidor seleccionado
                    $emails = array('sales' => ['email' => $showroom->email, 'name' => $showroom->company],
                                'customer' => ['email' => $customer['email'], 'name' => $customer['name']]);
                    // Y añadimos el distribuidor a los datos a mostrar en el email
                    $budget['showroom'] = $showroom;
                }
                else {
                    // Si el distribuidor no existe asignamos el email y el nombre de la empresa por defecto
                    $emails = array('sales' => ['email' => $this->salesEmail, 'name' => $this->salesName],
                                'customer' => ['email' => $customer['email'], 'name' => $customer['name']]);
                    // Y establecemos que no hay datos de distribuidores que mostrar en el email
                    $budget['showroom'] = false;
                }
                
                // Guardamos los emails de los destinatarios y la imagen a adjuntar
                $data = array('emails' => $emails, 'image' => ['file' => $thumbnail, 'name' => $imageName, 'mime' => 'image/png']);

                // Email para el comercial
                if (Mail::send('emails.budget', $budget, function ($email) use ($data) {
                    $email->from($data['emails']['customer']['email'], $data['emails']['customer']['name']);
                    $email->to($data['emails']['sales']['email'], $data['emails']['sales']['name'])->subject(trans('email.subject'));
                    $email->attach($data['image']['file'], ['as' => $data['image']['name'], 'mime' => $data['image']['mime']]); }) > 0) {
                        // Email para el cliente
                        if (Mail::send('emails.budget', $budget, function ($email) use ($data) {
                            $email->from($data['emails']['sales']['email'], $data['emails']['sales']['name']);
                            $email->to($data['emails']['customer']['email'], $data['emails']['customer']['name'])->subject(trans('email.subject'));
                            $email->attach($data['image']['file'], ['as' => $data['image']['name'], $data['image']['mime']]); }) > 0) {
                                // Establecemos el mensaje con el resultado de la operación
                                $message['message'] = trans('messages.success.budget-sent');
                                // Y eliminamos la imagen temporal de la placa personalizada
                                ManageImages::deleteTempImage($thumbnail);
                                $error = false;
                        }
                }
            }
            
            // Si hay errores durante el proceso
            if ($error) {
                // Establecemos el mensaje de error
                $message['message'] = trans('messages.errors.budget-shipment');
            }
        }
        
        // Y devolvemos el resultado de la operación al usuario
        return response()->json(['message' => $message['message'], 'input' => $message['input']]);
    }
}
