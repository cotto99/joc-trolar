<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GastoVariable extends Model
{
    protected $table = 'gastos_variables';
    protected $fillable = [
        'categoria',
        'descripcion',
        'monto',
        'fecha_gasto',
        'comprobante',
        'user_id',
    ];

    protected $casts = [
        'fecha_gasto' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Total del mes actual
    public static function totalMesActual(): float
    {
        return self::whereMonth('fecha_gasto', now()->month)
                   ->whereYear('fecha_gasto', now()->year)
                   ->sum('monto');
    }

    // Categorías sugeridas
    public static $categorias = [
        'Limpieza',
        'Reparación',
        'Herramientas',
        'Papelería',
        'Transporte',
        'Alimentación',
        'Otros',
    ];
}