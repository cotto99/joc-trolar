<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    protected $fillable = [
        'user_id',
        'bodega_id',
        'cliente_id',
        'tipo',
        'titulo',
        'descripcion',
        'prioridad',
        'fecha_evento',
    ];

    protected $casts = [
        'fecha_evento' => 'datetime',
    ];

    // Tipos disponibles
    public static $tipos = [
        'evento'        => 'Evento',
        'visita'        => 'Visita',
        'incidente'     => 'Incidente',
        'mantenimiento' => 'Mantenimiento',
    ];

    // Prioridades
    public static $prioridades = [
        'baja'  => 'Baja',
        'media' => 'Media',
        'alta'  => 'Alta',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bodega()
    {
        return $this->belongsTo(Bodega::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Color según prioridad para las vistas
    public function getColorPrioridadAttribute(): string
    {
        return match($this->prioridad) {
            'alta'  => 'red',
            'media' => 'yellow',
            'baja'  => 'green',
            default => 'gray',
        };
    }

    // Icono según tipo
    public function getIconoTipoAttribute(): string
    {
        return match($this->tipo) {
            'evento'        => '📅',
            'visita'        => '👁️',
            'incidente'     => '⚠️',
            'mantenimiento' => '🔧',
            default         => '📝',
        };
    }
}