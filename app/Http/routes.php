<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * #################################################
 *                     FRONTEND
 * #################################################
 */
// URL de la página principal del simulador
Route::get('/', ['as' => 'index', function() {
    return view('index');
}]);

// URL de acceso a los simuladores de los distribuidores
Route::get('dist/{slug}', 'DistributionsController@index');
// URL para el envío de los presupuestos (se accede sólo por AJAX)
Route::post('/solicitar-presupuesto', 'BudgetsController@send');

/**
 * #################################################
 *                     BACKEND
 * #################################################
 */
// URL de la página principal del panel de administración
Route::get('/admin', ['as' => 'admin', function() {
    return view('admin.index');
}]);

// URL para solicitar el listado de colores
Route::get('/admin/colores/listar', 'Admin\ColoursController@showList', ['names' => ['listar' => 'colores.showList']]);
// URL para solicitar el listado de piezas
Route::get('/admin/piezas/listar', 'Admin\PiecesController@showList', ['names' => ['listar' => 'piezas.showList']]);
// URL para solicitar el listado de placas
Route::get('/admin/placas/listar', 'Admin\PlaquesController@showList', ['names' => ['listar' => 'placas.showList']]);
// URL para solicitar el listado de distribuidores
Route::get('/admin/distribuidores/listar', 'Admin\ShowRoomsController@showList', ['names' => ['listar' => 'distribuidores.showList']]);

// Resto de URLs del panel de administración
Route::group(['prefix' => 'admin', 'namespace' => '\Admin'], function() {    
    // URLs para la gestión de los colores
    Route::resource('colores', 'ColoursController');
    // URLs para la gestión de las piezas
    Route::resource('piezas', 'PiecesController');
    // URLs para la gestión de las placas
    Route::resource('placas', 'PlaquesController');
    // URLs para la gestión de los distribuidores
    Route::resource('distribuidores', 'ShowRoomsController');
    
    // URL de la página de ayuda del panel de administración
    Route::get('ayuda', ['as' => 'admin.help', function() {
        return view('admin.help');
    }]);
});