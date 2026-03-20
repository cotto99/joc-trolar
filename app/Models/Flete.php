<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flete extends Model
{
    protected $fillable = [
        'bodega_id',
        'cliente_id',
        'descripcion_carga',
        'costo',
        'fecha_flete',
        'estado',
        'notas',
    ];

    protected $casts = [
        'fecha_flete' => 'date',
    ];

    public static $estados = [
        'pendiente'  => 'Pendiente',
        'en_camino'  => 'En Camino',
        'entregado'  => 'Entregado',
    ];

    public function bodega()
    {
        return $this->belongsTo(Bodega::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function getColorEstadoAttribute(): string
    {
        return match($this->estado) {
            'pendiente' => 'yellow',
            'en_camino' => 'blue',
            'entregado' => 'green',
            default     => 'gray',
        };
    }
}