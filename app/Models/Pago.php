<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'contrato_id', 'cliente_id', 'bodega_id',
        'monto', 'fecha_pago', 'fecha_vencimiento',
        'estado', 'comprobante', 'notas'
    ];

    protected $casts = [
        'fecha_pago'        => 'date',
        'fecha_vencimiento' => 'date',
    ];

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function bodega()
    {
        return $this->belongsTo(Bodega::class);
    }

    // Verifica si está próximo a vencer (5 días)
    public function proximoAVencer(): bool
    {
        if ($this->estado !== 'pendiente') return false;
        return $this->fecha_vencimiento->diffInDays(now()) <= 5
            && $this->fecha_vencimiento->isFuture();
    }
}