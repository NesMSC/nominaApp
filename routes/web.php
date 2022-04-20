<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware'=>['guest']], function () {
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::get('/recuperar', 'Auth\LoginController@getRecover')->name('recuperar');
    Route::post('/sendRecover', 'Auth\LoginController@postRecover')->name('sendRecover');
    Route::get('/reset', 'Auth\LoginController@getReset')->name('reset');
    Route::get('/passwordReset', 'Auth\LoginController@passwordReset')->name('passwordReset');
    Route::post('/updateNewPassword', 'Auth\LoginController@updateNewPassword')->name('updateNewPassword');


	//Rutas de prueba

});

Route::group(['middleware'=>['auth']], function(){
	Auth::routes();
	Route::get('/home', 'HomeController@index')->name('home')->middleware(['checkUserStatus']);

	Route::get('/empleados', 'empleadoController@index');
	Route::get('/empleados/obreros', 'empleadoController@indexObreros');
	Route::post('/empleados/agregarNuevo', 'empleadoController@store');
	Route::post('/empleados/salarioTabla', 'empleadoController@salarioTabla');
	Route::get('/empleados/beneficios', 'empleadoController@beneficios');
	Route::get('/empleados/primaProfesional/{instruccion}/{salario}', 'empleadoController@primaProfesional');
	Route::get('/empleados/primaAntiguedad', 'empleadoController@primaAntiguedad');
	Route::get('/empleados/deducciones', 'empleadoController@deducciones');
	Route::get('/empleados/descuentos', 'empleadoController@descuentos');

	Route::get('/empleados/editarEmpleado/{id}', 'empleadoController@edit');
	Route::get('/empleados/editarObrero/{id}', 'empleadoController@editObreros');

	Route::get('/empleados/editarSalarios/{id}', 'empleadoController@editarSalarios');

	Route::put('/empleados/actualizarEmpleado', 'empleadoController@update');

	Route::get('/empleados/contar', 'empleadoController@contar');
	Route::get('empleado/banco/{id}', 'empleadoController@cuentaBancaria');
	Route::delete('/empleados/delete/{id}', 'empleadoController@destroy');
	Route::get('empleados/restore/{id}', 'empleadoController@restore');
	Route::get('/empleados/departamentos/', 'empleadoController@departamentos');

	Route::get('/docentes', 'docenteController@index');
	Route::post('/docentes/agregarNuevo', 'docenteController@store');
	Route::get('/docentes/mostrarDocente/{id}', 'docenteController@show');
	Route::put('/docentes/actualizarDocente', 'docenteController@update');
	Route::get('/docentes/aggAdmin', 'docenteController@buscarPadmin');
	Route::post('/docentes/registrarAdmin', 'docenteController@registrarAdmin');
	Route::put('/docentes/actualizarDocenteAdmin', 'docenteController@actualizarDocenteAdmin');
	Route::get('/docentes/retirarDocenteAdmin/{id}', 'docenteController@retirarDocenteAdmin');

	//Pagos
	Route::get('/empleados/historialPagos/{id}', 'pagosController@historialPagos');
	Route::get('/pagos/pdf/{id_empleado}/{id}', 'pagosController@pdf');
	Route::get('/pagos/primaProfesional/{id}/{sueldo}', 'nominaController@primaProfesional');
	Route::get('/pagos/buscar/{start}/{end}/{id}', 'pagosController@buscarPagos');
	Route::get('/pagos/{start}/{end}/{id_empleado}/{id}', 'pagosController@pagosIntervaloPDF');

	//Indicadores
	Route::get('/indicadores', 'tabuladorController@indicadores');
	Route::put('/indicadores/editar', 'tabuladorController@editarIndicador');

	Route::middleware(['Admin'])->group(function(){
		//Beneficios
		Route::get('/beneficios', 'beneficioController@index');
		Route::post('/beneficios/agregarNuevo', 'beneficioController@store');
		Route::get('/beneficios/show/{id}', 'beneficioController@show');
		Route::delete('/beneficios/delete/{id}', 'beneficioController@destroy');
		Route::get('/beneficios/editar/{id}', 'beneficioController@edit');
		Route::put('/beneficios/actualizar', 'beneficioController@update');
		Route::get('/beneficios/primaProfesional', 'beneficioController@primaProfesional');
		Route::put('/beneficios/primaProfesional/update', 'beneficioController@primaProUpdate');
		//Descuentos
		Route::get('/descuentos', 'descuentoController@index');
		Route::post('/descuentos/agregarNuevo', 'descuentoController@store');
		Route::get('/descuentos/show/{id}', 'descuentoController@show');
		Route::delete('/descuentos/delete/{id}', 'descuentoController@destroy');
		Route::get('/descuentos/editar/{id}', 'descuentoController@edit');
		Route::put('/descuentos/actualizar', 'descuentoController@update');

		//Bancos
		Route::get('/bancos', 'bancoController@index');
		Route::get('/banco/{id}', 'bancoController@show');
		Route::post('/bancos', 'bancoController@store');
		Route::put('/bancos', 'bancoController@update');
		Route::delete('/banco/{id}', 'bancoController@destroy');
		
		//Tabuladores
		Route::get('/tabuladores', 'tabuladorController@index');
		Route::put('/tabuladores/update/{tab}', 'tabuladorController@update');

		//Roles
		Route::get('/roles', 'rolController@index');

		//Usuarios
		Route::get('/usuarios', 'userController@index');
		Route::get('/usuarios/buscarPerson', 'userController@buscarPerson');
		Route::post('/usuarios/agregarNuevo', 'userController@store');
		Route::get('/usuarios/editar/{id}', 'userController@edit');
		Route::put('/usuarios/actualizar', 'userController@update');
		Route::put('/usuarios/cambiarEstado', 'userController@cambiarCondicionUser');

		//Nominas
		Route::get('/nominas', 'nominaController@index');
		Route::post('/nominas/registrar', 'nominaController@registrarNominas');
		Route::delete('/nominas/delete/{id}', 'nominaController@destroyNomina');
		Route::get('/nominas/{id}', 'nominaController@consultarNomina');
		Route::get('/nominas/txt/{id}', 'nominaController@generarTxt');

		Route::get('/conceptos', 'nominaController@conceptos');

		//Tipos de Nominas
		Route::get('/tipos', 'tiposController@index');
		Route::get('/tipos/all', 'tiposController@getAll');
		Route::post('/tipos', 'tiposController@store');
		Route::delete('tipos/{id}', 'tiposController@destroy');


		//Backups
		Route::get('/backup', 'backup@index');
		Route::post('/backup/create', 'backup@create');
		Route::get('/backup/download/{file_name}', 'backup@download');
		Route::delete('/backup/delete/{file_name}', 'backup@delete');
	});


});

Route::get('/api/pago/{ci}/{year}/{quincena}', 'pagosController@datosReciboPago');

Auth::routes();

//Route::get('/test/{id}/{calc}/{tipo}', 'nominaController@asignaciones');