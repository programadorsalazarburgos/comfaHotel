<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class idioma extends Model
{
  protected $table = 'tbl_idiomas';
  protected $primarykey = 'id';
  protected $fillable = [
     'nombre',
     'estados_menu',
     'reservas_menu',
     'reservas_pms_menu',
     'reservas_filtradas_menu',
     'reservas_importar_menu',
     'configuracion_menu',
     'control_menu',
     'usuarios_menu',
     'roles_menu',
     'reportes_menu',
     'reporte_venta_menu',
     'reporte_cajero_menu',
     'detalle_habitacion',
     'llegadas',
     'salidas',
     'alojado',
     'disponibles',
     'habitaciones',
     'crear_reserva_orbe',
     'crear_reserva_manual',
     'filtro_calendario',
     'actualizar',
     'adelantos',
     'reservaciones',
     'reservas_filtradas',
     'fecha_reserva',
     'fecha_llegada',
     'fecha_salida',
     'tipo_habitacion',
     'fuente_reserva',
     'filtrar',
     'reservas',
     'reporte_excel',
     'no_reserva',
     'nombres',
     'no_habitacion',
     'dias_estadia',
     'precio_neto',
     'estado',
     'origen',
     'cargar_excel',
     'subir',
     'configuracion',
     'desayuno',
     'valor',
     'accion',
     'guardar',
     'borrar',
     'impuestos',
     'nuevo',
     'principal',
     'ish',
     'servicio'
  ];
}
