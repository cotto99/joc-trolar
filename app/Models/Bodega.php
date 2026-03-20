<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // ← agregar

class Bodega extends Model
{
    use SoftDeletes; // ← agregar

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