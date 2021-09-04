<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 22 Nov 2018 15:36:22 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TblCliente
 *
 * @property int $id
 * @property int $id_clientes_tipo
 * @property string $nombre
 * @property string $apellido
 * @property int $genero_id
 * @property \Carbon\Carbon $fecha_nacimiento
 * @property int $id_nacionalidad
 * @property string $formula
 * @property string $titulo
 * @property int $id_documento_tipo
 * @property string $no_documento
 * @property string $nombre_empresa
 * @property string $no_empresa
 * @property string $nombre_agencia
 * @property string $no_agencia
 * @property string $calle_residencia
 * @property string $lugar_residencia
 * @property string $codigo_postal_residencia
 * @property int $id_pais_residencia
 * @property int $id_departamento_residencia
 * @property string $calle_factura
 * @property string $lugar_factura
 * @property string $codigo_postal_factura
 * @property int $id_pais_factura
 * @property int $id_departamento_factura
 * @property string $comentarios
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Models\TblGenero $tbl_genero
 * @property \App\Models\TblClientesTipo $tbl_clientes_tipo
 * @property \App\Models\TblDepartamento $tbl_departamento
 * @property \App\Models\TblDocumentoTipo $tbl_documento_tipo
 * @property \App\Models\TblPaise $tbl_paise
 * @property \Illuminate\Database\Eloquent\Collection $tbl_cliente_contactos
 * @property \Illuminate\Database\Eloquent\Collection $tbl_reservas
 *
 * @package App\Models
 */
class TblCliente extends Eloquent
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $casts = [
		'id_clientes_tipo' => 'int',
		'genero_id' => 'int',
		'id_nacionalidad' => 'int',
		'id_documento_tipo' => 'int',
		'id_pais_residencia' => 'int',
		'id_departamento_residencia' => 'int',
		'id_pais_factura' => 'int',
		'id_departamento_factura' => 'int'
	];

	protected $fillable = [
		'id_clientes_tipo',
		'nombre',
		'apellido',
		'genero_id',
		'fecha_nacimiento',
		'pais_id',
		'id_documento_tipo',
		'no_documento',
		'nombre_empresa',
		'no_empresa',
		'nombre_agencia',
		'no_agencia',
		'telefono',
		'celular',
		'direccion',
		'estado',
		'postal'
	
	];

	public function tbl_genero()
	{
		return $this->belongsTo(\App\Models\TblGenero::class, 'genero_id');
	}

	public function tbl_clientes_tipo()
	{
		return $this->belongsTo(\App\Models\TblClientesTipo::class, 'id_clientes_tipo');
	}

	public function tbl_departamento()
	{
		return $this->belongsTo(\App\Models\TblDepartamento::class, 'id_departamento_residencia');
	}

	public function tbl_documento_tipo()
	{
		return $this->belongsTo(\App\Models\TblDocumentoTipo::class, 'id_documento_tipo');
	}

	public function tbl_paise()
	{
		return $this->belongsTo(\App\Models\TblPaise::class, 'id_pais_residencia');
	}

	public function tbl_cliente_contactos()
	{
		return $this->hasMany(\App\Models\TblClienteContacto::class, 'cliente_id');
	}

	public function tbl_reservas()
	{
		return $this->hasMany(\App\Models\TblReserva::class, 'id_cliente');
	}
}
