<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastoFijo extends Model
{
    protected $table = 'gastos_fijos'; // ← Esta línea arregla el problema

    protected $fillable = [
        'nombre',
        'descripcion',
        'monto_mensual',
        'dia_pago',
        'activo',
        'justificacion_inactivo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeInactivos($query)
    {
        return $query->where('activo', false);
    }

    public static function totalMensual(): float
    {
        return self::activos()->sum('monto_mensual');
    }
}