<?php
namespace App\Http\Controllers;

use App\Models\Bodega;
use App\Models\Pago;
use App\Models\Contrato;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $bodegas = Bodega::with([
            'contratoActivo.cliente',
            'contratoActivo.pagos' => fn($q) => $q->where('estado', 'pendiente')->orderBy('fecha_vencimiento')
        ])->orderBy('numero')->get();

        // Alertas: pagos que vencen en 5 días o ya vencidos
        $alertas = Pago::with(['cliente', 'bodega', 'contrato'])
            ->where('estado', 'pendiente')
            ->whereDate('fecha_vencimiento', '<=', now()->addDays(5))
            ->orderBy('fecha_vencimiento')
            ->get();

        $stats = [
            'total_bodegas'      => $bodegas->count(),
            'ocupadas'           => $bodegas->where('estado', 'ocupada')->count(),
            'disponibles'        => $bodegas->where('estado', 'disponible')->count(),
            'pagos_pendientes'   => Pago::where('estado', 'pendiente')->count(),
            'alertas'            => $alertas->count(),
        ];

        return Inertia::render('Dashboard', [
            'bodegas' => $bodegas,
            'alertas' => $alertas,
            'stats'   => $stats,
        ]);
    }
}