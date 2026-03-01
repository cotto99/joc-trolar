<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contrato extends Model
{
    protected $fillable = [
        'cliente_id', 'bodega_id', 'fecha_inicio', 'fecha_fin',
        'monto', 'periodicidad', 'dia_pago', 'estado',
        'observaciones', 'numero_contrato'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function bodega()
    {
        return $this->belongsTo(Bodega::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function proximoPago()
    {
        return $this->hasOne(Pago::class)
            ->where('estado', 'pendiente')
            ->orderBy('fecha_vencimiento');
    }

    // Genera número de contrato único
    public static function generarNumero(): string
    {
        $ultimo = self::latest()->first();
        $numero = $ultimo ? (intval(substr($ultimo->numero_contrato, -4)) + 1) : 1;
        return 'CONT-' . date('Y') . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    // Genera los pagos automáticamente según periodicidad
    public function generarPagos(): void
    {
        $fecha = $this->fecha_inicio->copy();
        $fin   = $this->fecha_fin->copy();

        $meses = match($this->periodicidad) {
            'mensual'     => 1,
            'trimestral'  => 3,
            'semestral'   => 6,
            'anual'       => 12,
        };

        while ($fecha->lte($fin)) {
            $vencimiento = $fecha->copy()->day($this->dia_pago);

            Pago::create([
                'contrato_id'       => $this->id,
                'cliente_id'        => $this->cliente_id,
                'bodega_id'         => $this->bodega_id,
                'monto'             => $this->monto,
                'fecha_pago'        => null,
                'fecha_vencimiento' => $vencimiento,
                'estado'            => 'pendiente',
            ]);

            $fecha->addMonths($meses);
        }
    }
}