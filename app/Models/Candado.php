<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candado extends Model
{
    protected $fillable = [
        'codigo',
        'tipo',
        'precio_costo',
        'precio_venta',
        'stock',
        'stock_minimo',
    ];

    public function ventas()
    {
        return $this->hasMany(VentaCandado::class);
    }

    // Verificar si el stock está bajo
    public function stockBajoAttribute(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }

    // Total vendido
    public function totalVendido(): int
    {
        return $this->ventas()->sum('cantidad');
    }
}