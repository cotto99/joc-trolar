<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaCandado extends Model
{
    protected $table = 'ventas_candados'; // ← Esta línea arregla el problema

    protected $fillable = [
        'candado_id',
        'cliente_id',
        'contrato_id',
        'cantidad',
        'precio_unitario',
        'total',
        'fecha_venta',
        'notas',
    ];

    protected $casts = [
        'fecha_venta' => 'date',
    ];

    public function candado()
    {
        return $this->belongsTo(Candado::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }
}