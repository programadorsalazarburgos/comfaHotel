<?php


use App\User;
use App\Models\TblHotel;
use App\CuentaReserva;
use App\Models\TblImpuesto;
use App\Inventario;
use App\TarifaFecha;
use App\InventarioHabitacion;
use App\Http\Controllers\EsquemaController;
//Subida JSB

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
//dd(User::all());
Route::get('es',function(){
        // EsquemaController::changeConection();
        $datosHotel = TblHotel::find(1);
        $date = $datosHotel->hora_utc;
        dd($date);
});


Route::get('pass/{pass}',function($pass){echo bcrypt($pass);});

Route::get('/all', function() {

  for ($i = "2020-09-21";$i < "2020-12-31";$i = date("Y-m-d", strtotime($i . "+ 1 days")))
  {
    $data = Inventario::where('tipo_habitacion_id', 1)->whereDate('start', $i)->firstOrfail();
    $data->disponibilidad = 2;
    $data->save();

    $data2 = InventarioHabitacion::where('tipo_habitacion_id', 1)->whereDate('start', $i)->firstOrfail();
    $data2->disponibilidad = true;
    $data2->save();
  }

  for ($i = "2020-09-21";$i < "2020-12-31";$i = date("Y-m-d", strtotime($i . "+ 1 days")))
  {
    $data = Inventario::where('tipo_habitacion_id', 2)->whereDate('start', $i)->firstOrfail();
    $data->disponibilidad = 1;
    $data->save();

    $data2 = InventarioHabitacion::where('tipo_habitacion_id', 2)->whereDate('start', $i)->firstOrfail();
    $data2->disponibilidad = true;
    $data2->save();
  }

  for ($i = "2020-09-21";$i < "2020-12-31";$i = date("Y-m-d", strtotime($i . "+ 1 days")))
  {
    $data = Inventario::where('tipo_habitacion_id', 3)->whereDate('start', $i)->firstOrfail();
    $data->disponibilidad = 1;
    $data->save();

    $data2 = InventarioHabitacion::where('tipo_habitacion_id', 3)->whereDate('start', $i)->firstOrfail();
    $data2->disponibilidad = true;
    $data2->save();
  }
  for ($i = "2020-09-21";$i < "2020-12-31";$i = date("Y-m-d", strtotime($i . "+ 1 days")))
  {
    $data = Inventario::where('tipo_habitacion_id', 4)->whereDate('start', $i)->firstOrfail();
    $data->disponibilidad = 4;
    $data->save();

    $data2 = InventarioHabitacion::where('tipo_habitacion_id', 4)->whereDate('start', $i)->firstOrfail();
    $data2->disponibilidad = true;
    $data2->save();
  }
});


Route::get('/double', function() {


  for ($i = "2020-09-21";$i < "2020-12-31";$i = date("Y-m-d", strtotime($i . "+ 1 days")))
  {
    $data = Inventario::where('tipo_habitacion_id', 1)->whereDate('start', $i)->get();
    foreach ($data as $value) {
      $data1 = Inventario::where('id', $value['id'])->firstOrfail();
      $data1->disponibilidad = 2;
      $data1->save();
    }

    $data2 = InventarioHabitacion::where('tipo_habitacion_id', 1)->whereDate('start', $i)->get();
    foreach ($data2 as $value) {
      $data3 = InventarioHabitacion::where('id', $value['id'])->firstOrfail();
      $data3->disponibilidad = true;
      $data3->save();
    }
  }



});
Route::get('/king', function() {

  for ($i = "2020-09-21";$i < "2020-12-31";$i = date("Y-m-d", strtotime($i . "+ 1 days")))
  {
    $data = Inventario::where('tipo_habitacion_id', 2)->whereDate('start', $i)->get();
    foreach ($data as $value) {
      $data1 = Inventario::where('id', $value['id'])->firstOrfail();
      $data1->disponibilidad = 1;
      $data1->save();
    }

    $data2 = InventarioHabitacion::where('tipo_habitacion_id', 2)->whereDate('start', $i)->get();
    foreach ($data2 as $value) {
      $data3 = InventarioHabitacion::where('id', $value['id'])->firstOrfail();
      $data3->disponibilidad = true;
      $data3->save();
    }
  }


});
Route::get('/standard', function() {

  for ($i = "2020-09-21";$i < "2020-12-31";$i = date("Y-m-d", strtotime($i . "+ 1 days")))
  {
    $data = Inventario::where('tipo_habitacion_id', 3)->whereDate('start', $i)->get();
    foreach ($data as $value) {
      $data1 = Inventario::where('id', $value['id'])->firstOrfail();
      $data1->disponibilidad = 1;
      $data1->save();
    }

    $data2 = InventarioHabitacion::where('tipo_habitacion_id', 3)->whereDate('start', $i)->get();
    foreach ($data2 as $value) {
      $data3 = InventarioHabitacion::where('id', $value['id'])->firstOrfail();
      $data3->disponibilidad = true;
      $data3->save();
    }
  }

});
Route::get('/standardk', function() {

  for ($i = "2020-09-21";$i < "2020-12-31";$i = date("Y-m-d", strtotime($i . "+ 1 days")))
  {
    $data = Inventario::where('tipo_habitacion_id', 4)->whereDate('start', $i)->get();
    foreach ($data as $value) {
      $data1 = Inventario::where('id', $value['id'])->firstOrfail();
      $data1->disponibilidad = 4;
      $data1->save();
    }

    $data2 = InventarioHabitacion::where('tipo_habitacion_id', 4)->whereDate('start', $i)->get();
    foreach ($data2 as $value) {
      $data3 = InventarioHabitacion::where('id', $value['id'])->firstOrfail();
      $data3->disponibilidad = true;
      $data3->save();
    }
  }
});

Route::get('/pruebas3', 'HomeController@pruebas3');

Route::get('/limpiar', function() {

   Artisan::call('cache:clear');
   Artisan::call('config:clear');
   Artisan::call('config:cache');
   Artisan::call('view:clear');

   return "Cleared!";

});
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('obtener_select','ReservasController@pruebas_select');
Route::get('conectar','UserController@conectar');
Route::get('obtener_idiomas','UserController@obtener_idiomas');
Route::get('usuario_supervisor','UsuarioController@usuario_supervisor');
Route::get('/cargarIframe','ReservasController@cargar_iframe');
Route::get('/listarall_reservas','ReservasController@listarall_reservas');
Route::get('/pruebas','HomeController@pruebas');
Route::get('/lesson/create','LessonController@newLesson');
Route::get('/prueba2','HomeController@prueba2');
Route::get('/prueba','HomeController@prueba');
Route::get('/permisos','HomeController@permisos');
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('/index/{any}', 'HomeController@plantilla')->where('any', '.*');
Route::get('/1', function() {


     $exitCode1 = Artisan::call('cache:clear');
     $exitCode2 = Artisan::call('optimize');
     // $exitCode3 = Artisan::call('route:cache');
     $exitCode4 = Artisan::call('route:clear');
     $exitCode5 = Artisan::call('view:clear');
     $exitCode6 = Artisan::call('config:cache');
    return '<h1>Cache facade value cleared</h1>';
});
// Route::get('/cambio', function() {
//     PGSchema::switchTo('hotel_fenixhotelcasasantamaria');
//     $data = TarifaFecha::all();
//     foreach ($data as $value) {
//       $cambio = TarifaFecha::where('id', $value['id'])->firstOrfail();
//       $cambio->tarifa_x_habitacion = (float)$cambio->tarifa_x_habitacion * 1.2;
//       $cambio->save();
//     }
//
// });


Route::post('/index/logout', 'Auth\LoginController@logout');

Route::get('/lesson/create','LessonController@newLesson');
Route::post('/notification/lesson/notification', 'LessonController@notification');
Route::get('/friends','FriendController@index');
Route::get('/chat','ChatController@index')->name('chat.index');
Route::get('/chat/{id}','ChatController@show')->name('chat.show');
Route::post('/chat/getChat/{id}', 'ChatController@getChat');
Route::post('/chat/sendChat', 'ChatController@sendChat')->middleware('auth');


Route::get('/',function(){return redirect('/login');});
Route::group(['middleware'=>['guest']],function(){
    Route::get('/login','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware'=>['auth']],function(){

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/dashboard', 'DashboardController');
    Route::get('/notification/get', 'NotificationController@get');

// Route::get('/main', function () {
//
//         return view('contenido/contenido');
//
//     })->name('main');

Route::group(['middleware' => ['Administrador', 'Supervisor']], function () {

       //Conexion Api
         Route::get('/api', 'ApiController@api');
         Route::get('/api2', 'ApiController@api2');
         Route::get('/reservas', 'ApiController@VerReservas');
        //Rutas Globales
        Route::get('/obtener/tipo_habitacion_nueva','GlobalesController@tipo_habitacion_nueva');
        Route::get('/obtener/selectCajeros','GlobalesController@obtener_cajeros');
        Route::get('/obtener/selectPais', 'GlobalesController@selectPaises');
        Route::get('/obtener_estados', 'GlobalesController@obtener_estados');
        Route::get('/obtener_tipos_habitacion', 'GlobalesController@obtener_tipos_habitacion');
        Route::get('/obtener_fuentes_reservas', 'GlobalesController@obtener_fuentes_reservas');
        Route::get('/obtener/selectDepartamento', 'GlobalesController@selectDepartamentos');
        Route::get('/obtener/selectTipoContacto', 'GlobalesController@selectTipoContacto');
        Route::get('/obtener/selectTipoDocumento', 'GlobalesController@selectTipoDocumento');
        Route::get('/obtener/selectSexo', 'GlobalesController@selectSexo');
        Route::get('/obtener/habitaciones', 'GlobalesController@obtener_habitaciones');
        Route::get('/obtener/impuestos_reserva', 'GlobalesController@obtener_impuesto');
        Route::get('/obtener/listar_habitacion', 'GlobalesController@listar_habitacion');
        Route::get('/obtener/listar_bloqueos_habitaciones', 'GlobalesController@listar_bloqueos_habitaciones');
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        // Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');
        Route::get('/cliente', 'ClienteController@index');
        Route::get('/cliente/selectTipoCliente', 'ClienteController@selectTipoCliente');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::post('/cliente/actualizar', 'ClienteController@actualizar');
        Route::get('/usuario/listarUsuario', 'AlmacenController@listarUsuario');
        Route::get('/rol', 'RolController@index');
        Route::get('/rol/selectRol', 'RolController@selectRol');
        Route::put('/rol/desactivar', 'RolController@desactivar');
        Route::put('/rol/activar', 'RolController@activar');
        Route::post('/quitar/permiso', 'RolController@quitar_permiso');
        Route::post('/quitar/permisos_all', 'RolController@quitar_permisos_all');
        Route::post('/aplicar_servicio/reserva', 'ReservasController@aplicar_servicio_reserva');
        Route::post('/quitar_servicio/reserva', 'ReservasController@quitar_servicio_reserva');
        Route::post('/quitar_servicio_individual/reserva', 'ReservasController@quitar_servicio_individual');
        Route::post('/agregar_servicio_individual/reserva', 'ReservasController@agregar_servicio_individual');
        Route::post('/cambiar_tarifa/individual', 'ReservasController@cambiar_tarifa_individual');
        Route::post('/rol/registrar', 'RolController@store');
        Route::put('/rol/actualizar', 'RolController@update');
        Route::get('/obtener/permisos', 'RolController@permisos');
        Route::post('/asignar/permisos', 'RolController@asignar_permisos');
        Route::get('/obtener/permiso_asignado', 'RolController@permisos_asignados');
        Route::get('/user', 'UsuarioController@index');
        Route::post('/user/registrar', 'UsuarioController@store');
        Route::put('/user/actualizar', 'UsuarioController@update');
        Route::put('/user/desactivar', 'UsuarioController@desactivar');
        Route::put('/user/activar', 'UsuarioController@activar');
        Route::get('/user/selectUsuarios', 'UsuarioController@selectUsuarios');
        Route::get('/documento', 'DocumentoController@index');
        Route::prefix('habitaciones')->group(function ()
        {
            Route::get('EstadoLimpieza',    'ReservasController@EstadoLimpieza');
            Route::get('VerEstadosReservas','ReservasController@VerEstadosReservas');
            Route::get('VerEstadosReservas_salidas','ReservasController@VerEstadosReservas_salidas');
            Route::get('Pisos',             'HabitacionesController@Pisos');
            Route::get('tipo',              'HabitacionesController@ListarHabitacionesTipo');
            Route::get('camastipo',         'HabitacionesController@VerCamasTipo');
            Route::get('superbookingtipo',  'HabitacionesController@VerSuperbookingTipo');
            Route::get('index',             'HabitacionesController@VerHabitaciones');
            Route::post('res',              'HabitacionesController@VerHabitacionesTipo');
            Route::post('NuevaTipo',        'HabitacionesController@SaveNewTipo');
            Route::post('EditarTipo',       'HabitacionesController@SaveEditTipo');
            Route::post('nueva',            'HabitacionesController@SaveNewHabitacion');
            Route::post('Editar',           'HabitacionesController@SaveEditHabitacion');
            Route::get('obtener',    'HabitacionesController@obtener_habitaciones');
            Route::post('cambiarestado',    'ReservasController@cambiarestado');
            Route::post('cambiarestado_desbloquear',    'ReservasController@cambiarestado_desbloquear');
            Route::get('obtener_tipo',    'HabitacionesController@obtener_tipo');
            Route::get('ubicaciones',    'HabitacionesController@ubicaciones');
            Route::get('ver_habitaciones',             'HabitacionesController@ver_habitaciones');
            Route::get('estados',             'HabitacionesController@ver_estados');
            Route::get('estados_salidas',             'HabitacionesController@ver_estados_salidas');
            Route::get('estados_llegadas',             'HabitacionesController@ver_estados_llegadas');
            Route::get('estados_alojados',             'HabitacionesController@ver_estados_alojados');
            Route::get('ver_planes',             'HabitacionesController@ver_planes');
            Route::get('ver_habitaciones_planes',             'HabitacionesController@ver_habitaciones_planes');
            Route::get('selectBloqueos',             'HabitacionesController@obtener_bloqueos');
            Route::put('desactivar', 'HabitacionesController@desactivar');
            Route::put('activar', 'HabitacionesController@activar');
            Route::get('listar',   'HabitacionesController@listar');
            Route::post('inventario_nuevo',   'HabitacionesController@inventario_nuevo');

        });

        Route::prefix('disponibilidades')->group(function ()
        {
            Route::get('tipo_habitaciones',  'ReservasController@tipo_habitaciones');
            Route::get('tipo_habitaciones_modal',  'ReservasController@tipo_habitaciones_modal');
        });
        Route::prefix('turnos')->group(function ()
        {
            Route::post('Nueva',  'turnosController@SaveNewTurno');
            Route::post('Editar', 'turnosController@SaveEditTurno');
            Route::get('index',   'turnosController@VerTurnos');
        });
        Route::prefix('moneda')->group(function ()
        {
            Route::post('Editar', 'monedaController@SaveEditMoneda');
            Route::get('index',   'monedaController@VerMoneda');
        });

        Route::prefix('tipo_habitaciones')->group(function ()
        {
            Route::get('listar',   'TipoHabitacionController@listar');
            Route::get('Inventario',    'TipoHabitacionController@Inventario');
        });

        Route::prefix('inventarios')->group(function ()
        {
            Route::post('registrar', 'InventarioController@registrar');
            Route::post('editar', 'InventarioController@editar_inventario');
            Route::get('inventario_habitaciones', 'InventarioController@habitaciones_inventarios');
            Route::get('total_habitaciones', 'InventarioController@total_habitaciones');
            Route::get('ocupaciones', 'InventarioController@ocupaciones');
        });
        Route::prefix('reportes')->group(function ()
        {
            Route::get('reservas', 'ReporteController@reservas');
            Route::get('reservas_mes', 'ReporteController@reservas_mes');
        });
        Route::prefix('reservas')->group(function ()
        {
            Route::post('desasignar', 'ReservasController@desasignar');
            Route::put('elimina_comentario', 'ReservasController@elimina_comentario');
            Route::put('eliminar_consumo', 'ReservasController@eliminar_consumo');
            Route::put('eliminar_abono', 'ReservasController@eliminar_abono');
            Route::post('crear_cuentas', 'ReservasController@crear_cuentas');
            Route::post('crear_consumos', 'ReservasController@crear_consumos');
            Route::post('actualizar_cuenta_reserva', 'ReservasController@actualizar_cuenta_reserva');
            Route::post('modificar_inventario', 'ReservasController@modificar_inventario');
            Route::post('modificar_inventario_manual', 'ReservasController@modificar_inventario_manual');
            Route::post('modificar_inventario_expandir', 'ReservasController@modificar_inventario_expandir');
            Route::post('cargar_consumos', 'ReservasController@cargar_consumos');
            Route::post('insertar_pms_waldo', 'ReservasController@insertar_pms_waldo');
            Route::post('asignar_pagos_notas', 'ReservasController@asignar_pagos_notas');
            Route::post('agregar_prepago_nota', 'ReservasController@agregar_prepago_nota');
            Route::post('actualizar_prepago_nota', 'ReservasController@actualizar_prepago_nota');
            Route::post('anular_reserva', 'ReservasController@anular_reserva');
            Route::post('eliminar_nota_prepago', 'ReservasController@eliminar_nota_prepago');
            Route::post('anular_reserva_grupo', 'ReservasController@anular_reserva_grupo');
            Route::post('reservas_manuales', 'ReservasController@reservas_manuales');
            Route::get('obtener_prepagos_notas', 'ReservasController@obtener_prepagos_notas');
            Route::get('obtener_prepagos_notas2', 'ReservasController@obtener_prepagos_notas2');
            Route::get('miscuentas', 'ReservasController@miscuentas');
            Route::get('tipoHabitaciones', 'ReservasController@tipoHabitaciones');
            Route::get('mensajes_realizados', 'ReservasController@mensajes_realizados');
            Route::get('VerReservasChanel', 'ApiController@VerReservas');
            Route::get('VerClientes',       'ReservasController@VerClientes');
            Route::get('VerReservas',       'ReservasController@VerReservacionesActuales');
            Route::get('data_reservas',    'ReservasController@data_reservas');
            Route::get('obtener_reservas_realizadas',    'ReservasController@ReservasRealizadas');
            Route::post('ActualizarFecha',  'ReservasController@ActualizarFecha');
            Route::post('expandir_reserva',  'ReservasController@expandir_reserva');
            Route::post('actualizar',       'ReservasController@actualizar_reserva');
            Route::post('changecolor',      'ReservasController@changecolor');
            Route::post('agregar_cliente_hospedado',      'ReservasController@agregar_cliente_hospedado');
            Route::get('clientes_hospedados', 'ReservasController@clientes_hospedados');
            Route::post('agregar_cliente_detalle_hospedado',      'ReservasController@agregar_cliente_detalle_hospedado');
            Route::post('eliminar_cliente_hospedado',      'ReservasController@eliminar_cliente_hospedado');
            Route::get('nombre_reservante', 'ReservasController@nombre_reservante');
            Route::post('comentario',      'ReservasController@crear_comentarios');

            Route::get('monto_pagado', 'ReservasController@monto_pagado');
            Route::get('listar_comentarios', 'ReservasController@listar_comentarios');
            Route::post('checkOut', 'ReservasController@checkOut');
            Route::post('actualizar_numeroimpuesto', 'ReservasController@actualizar_numeroimpuesto');
            Route::post('actualizar_numeroimpuesto2', 'ReservasController@actualizar_numeroimpuesto2');

            Route::post('save/comentarios/{id}','ReservasController@SaveComentarios');
            Route::get('reserva_grupo', 'ReservasController@reservas_grupos');
            Route::get('tipo_pagos_pagadores', 'ReservasController@tipo_pagos_pagadores');

            Route::get('impuestos', 'ReservasController@impuestos');
            Route::post('elegir_pagador', 'ReservasController@elegir_pagador');
            Route::post('agregar_pagador', 'ReservasController@agregar_pagador');
            Route::post('eliminar_pagador', 'ReservasController@eliminar_pagador');
            Route::post('eliminar_mensaje', 'ReservasController@eliminar_mensaje');

            Route::get('grupo_habitaciones', 'ReservasController@habitaciones_grupos');
            Route::post('guardar_factura', 'ReservasController@guardar_factura');

            Route::post('GuardarChekin',    'ReservasController@GuardarChekin');
            Route::post('anularReserva',    'ReservasController@anularReserva');
            Route::post('save/comentarios/{id}','ReservasController@SaveComentarios');
            Route::post('ReservasCompletas','ReservasController@ReservasCompletas');
            Route::get('reserva_factura','ReservasController@reserva_factura');
            Route::get('tipo_pagos','ReservasController@tipo_pagos');
            Route::post('guardar_tipopago_pagadores','ReservasController@guardar_tipopago_pagadores');
            Route::post('guardar_mensajes','ReservasController@guardar_mensajes');
            Route::get('pago_realizado','ReservasController@pago_realizado');
            Route::post('actualizar_tipopago_pagadores','ReservasController@actualizar_tipopago_pagadores');
            Route::post('actualizar_mensajes','ReservasController@actualizar_mensajes');

            Route::post('eliminar_tipopago_pagadores','ReservasController@eliminar_tipopago_pagadores');
            Route::get('pdf/{factura}/{reserva}','ReservasController@pdf')->name('venta_pdf');
            Route::get('pdf2/{factura}/{reserva}/{cliente}/{total_a_pagar}/{total_pagado}','ReservasController@pdf2')->name('venta_pdf');
            Route::get('pdfCajero/{cajero_id}/{fecha_filtro}','ReservasController@pdfCajero')->name('venta_pdf');
            Route::get('listar_bloqueos', 'ReservasController@listar_bloqueos');
            Route::post('actualizar_tarifa','ReservasController@actualizar_tarifa');
            Route::post('bloquear_habitaciones','ReservasController@bloquear_habitaciones');
            Route::post('desbloquear_habitacion', 'ReservasController@desbloquear_habitacion');
            Route::get('tasas_impuestos_detalles', 'ReservasController@tasas_impuestos_detalles');
            Route::get('data_grupos', 'ReservasController@data_grupos');
            Route::get('data_cuentas', 'ReservasController@data_cuentas');

        });

        Route::prefix('config')->group(function ()
        {
            Route::get('get/{name}', 'TblConfigController@Get');
        });
        Route::get('obtener/cuentasDetalles', 'ReservasController@detalles_cuentas');
        Route::prefix('impuestos')->group(function ()
        {
            Route::get('importes', 'TblImpuestosController@get_importes');
            Route::get('formatos', 'TblImpuestosController@get_formatos');
            Route::post('nuevo', 'TblImpuestosController@nuevo');
            Route::get('index_hotel', 'TblImpuestosController@index_hotel');
            Route::get('index', 'TblImpuestosController@index');
            Route::post('Editar', 'TblImpuestosController@editar');
            Route::post('borrar', 'TblImpuestosController@borrar');
            Route::post('principal', 'TblImpuestosController@principal');
            Route::post('ish', 'TblImpuestosController@ish');
            Route::post('servicio', 'TblImpuestosController@servicio');
            Route::post('actualizar_estado', 'TblImpuestosController@actualizar_estado');


        });

        Route::prefix('prepagos')->group(function ()
      {
          Route::get('index', 'PrepagoController@index');
          Route::get('listar', 'PrepagoController@listar');

      });

          Route::prefix('impuestos_productos')->group(function ()
        {
            Route::get('index', 'TblImpuestosProductosController@index');
            Route::post('Nueva', 'TblImpuestosProductosController@Nueva');
            Route::post('Editar', 'TblImpuestosProductosController@Editar');
            Route::post('borrar', 'TblImpuestosProductosController@borrar');
            Route::post('principal', 'TblImpuestosProductosController@principal');
            Route::post('servicio', 'TblImpuestosProductosController@servicio');


        });

               Route::prefix('categorias_productos')->group(function ()
        {
            Route::get('index', 'TblCategoriasProductosController@index');
            Route::post('store', 'TblCategoriasProductosController@store');
            Route::post('Editar', 'TblCategoriasProductosController@Editar');
            Route::post('borrar', 'TblCategoriasProductosController@borrar');

        });


        Route::prefix('tipo_pagos')->group(function ()
 {
     Route::get('index', 'TipoPagoController@index');
     Route::post('store', 'TipoPagoController@store');
     Route::post('Editar', 'TipoPagoController@Editar');
     Route::post('borrar', 'TipoPagoController@borrar');

 });

             Route::prefix('listar_productos')->group(function ()
        {
            Route::get('index', 'TblProductosController@index');
            Route::post('store', 'TblProductosController@store');
            Route::post('Editar', 'TblProductosController@Editar');
            Route::post('borrar', 'TblProductosController@borrar');
            Route::get('obtener_categorias', 'TblProductosController@obtener_categorias');

        });



        Route::prefix('Hoteles')->group(function ()
        {
            Route::get('index', 'HotelController@index');
            Route::post('Nueva', 'HotelController@Nueva');
            Route::post('Editar', 'HotelController@Editar');
            Route::post('borrar', 'HotelController@borrar');
        });


     });

});

Route::prefix('planes_tarifarios')->group(function ()
{
Route::post('nuevo', 'PlanTarifarioController@nuevo');
Route::post('editar', 'PlanTarifarioController@editar');
Route::get('ver_planes_tarifarios', 'PlanTarifarioController@ver_planes_tarifarios');
Route::post('actualizar_estado', 'PlanTarifarioController@actualizar_estado');
Route::get('listar', 'PlanTarifarioController@listar');
Route::get('obtener_tipo_habitaciones', 'PlanTarifarioController@obtener_tipo_habitaciones');
Route::get('ver_agenda', 'PlanTarifarioController@ver_agenda');
Route::post('crear_tarifa_fecha', 'PlanTarifarioController@crear_tarifa_fecha');
Route::get('obtener', 'PlanTarifarioController@obtener_planes');
Route::get('calculo_tarifa', 'PlanTarifarioController@calculo_tarifa');

});

Route::get('/obtener_abonos', 'ReservasController@obtener_abonos');
Route::get('/fechasx/grupo', 'ReservasController@get_clientes');
Route::get('/obtener/clientes', 'ReservasController@get_clientes');
Route::get('/obtener/zonas', 'ReservasController@get_zonas');
Route::get('/contacts', 'ContactsController@get');
Route::get('/conversation/{id}', 'ContactsController@getMessagesFor');
Route::post('/conversation/send', 'ContactsController@send');
Route::post('/import-excel-reservas', 'ReservasController@importar');

Route::get('/comidas', 'ComidaController@index');
Route::post('/comidas/Nueva', 'ComidaController@store');
Route::post('/comidas/Editar', 'ComidaController@update');
Route::get('/obtener/categorias', 'ComidaController@obtener_categorias');
Route::get('/obtener/productos', 'ComidaController@obtener_productos');
Route::get('/obtener/datos_productos', 'ComidaController@datos_productos');
Route::get('/obtener/impuestos_productos', 'ComidaController@impuestos_productos');
Route::get('/obtener/impuestos_habitaciones', 'ComidaController@impuestos_habitaciones');
Route::post('/registrar/consumo_extra', 'ComidaController@consumos_extras');
Route::post('/actualizar/consumo_extra', 'ComidaController@actualizar_extras');
Route::get('/obtener/consumos_extras', 'ComidaController@obtener_consumos_extras');
Route::get('/obtener/consumos_extras2', 'ComidaController@obtener_consumos_extras2');
Route::get('/obtener/impuesto_producto', 'ComidaController@impuesto_producto');
Route::prefix('punto_ventas')->group(function ()
{
    Route::get('index', 'PuntoVentaController@index');
    Route::post('store', 'PuntoVentaController@store');
    Route::post('Editar', 'PuntoVentaController@Editar');
    Route::post('borrar', 'PuntoVentaController@borrar');

});

Route::get('/obtener_puntosventa', 'GlobalesController@obtener_puntosventa');
Route::get('/obtener/categoriasFacturas', 'ComidaController@obtener_categorias_f');
Route::get('/obtener/impuesto_seleccionado', 'GlobalesController@impuesto_seleccionado');
Route::get('/cargar_tipo_habitaciones', 'GlobalesController@cargar_tipo_habitaciones');

Route::post('/consumo_extra/eliminar', 'ComidaController@eliminar_extras');
Route::post('/registrar/factura_pagos', 'ComidaController@factura_pagos');
Route::post('/cancelar/factura', 'ReservasController@cancelar_factura');


Route::get('/obtener/total_facturas', 'ComidaController@total_facturas');
Route::post('registrar/pagos_facturacion', 'ComidaController@pagos_facturacion');
Route::get('/obtener/impuesto_ish', 'ComidaController@impuesto_ish');
Route::get('/obtener/impuesto_servicio', 'ComidaController@impuesto_servicio');
Route::get('/obtener/impuesto_servicio_habitacion', 'ComidaController@impuesto_servicio_habitacion');
Route::get('/obtener/impuesto_reserva', 'ComidaController@impuesto_reserva');
Route::get('/listar/reporte', 'ReporteController@listar_reporte');
Route::get('/listar/reporte_consumos', 'ReporteController@listar_reporte_consumos');
Route::prefix('desayuno')->group(function ()
{
    Route::get('index', 'DesayunoController@index');
    Route::post('Nueva', 'DesayunoController@Nueva');
    Route::post('Editar', 'DesayunoController@Editar');
    Route::post('borrar', 'DesayunoController@borrar');

});

Route::get('/prueba5', 'GlobalesController@idiomas');
Route::get('/obtener/idioma', 'GlobalesController@idiomas');
Route::get('/obtener/numero_impuesto', 'GlobalesController@numero_impuesto');
Route::get('pruebas2','GlobalesController@pruebas2');
