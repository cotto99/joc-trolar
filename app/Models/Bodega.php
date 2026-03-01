<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    protected $fillable = [
        'numero', 'nombre', 'tamanio_m2',
        'descripcion', 'estado'
    ];

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    public function contratoActivo()
    {
        return $this->hasOne(Contrato::class)->where('estado', 'activo')->latest();
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}