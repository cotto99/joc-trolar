<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombre', 'apellido', 'telefono', 'email',
        'dpi', 'nit', 'direccion', 'notas'
    ];

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    public function contratosActivos()
    {
        return $this->hasMany(Contrato::class)->where('estado', 'activo');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }
}