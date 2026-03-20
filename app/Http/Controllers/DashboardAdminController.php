<?php

namespace App\Http\Controllers;

use App\Models\Bodega;
use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Pago;
use App\Models\Flete;
use App\Models\VentaCandado;
use App\Models\GastoFijo;
use App\Models\GastoVariable;
use App\Models\Bitacora;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardAdminController extends Controller
{
    public function index(Request $request)
    {
        $filtro = $request->get('filtro', 'mensual');
        $mes    = $request->get('mes', now()->month);
        $anio   = $request->get('anio', now()->year);

        // Rango de fechas según filtro
        if ($filtro === 'diario') {
            $desde = Carbon::today();
            $hasta = Carbon::today()->endOfDay();
        } else {
            $desde = Carbon::create($anio, $mes, 1)->startOfMonth();
            $hasta = Carbon::create($anio, $mes, 1)->endOfMonth();
        }

        // ── INGRESOS ──
        $ingresosPagos = Pago::whereBetween('fecha_pago', [$desde, $hasta])
            ->where('estado', 'pagado')
            ->sum('monto');

        $ingresosVentasCandados = VentaCandado::whereBetween('fecha_venta', [$desde, $hasta])
            ->sum('total');

        $totalIngresos = $ingresosPagos + $ingresosVentasCandados;

        // ── EGRESOS ──
        $gastosVariables = GastoVariable::whereBetween('fecha_gasto', [$desde, $hasta])
            ->sum('monto');

        $gastosFijos = GastoFijo::activos()->sum('monto_mensual');
        if ($filtro === 'diario') {
            $gastosFijos = round($gastosFijos / 30, 2);
        }

        $costoFletes = Flete::whereBetween('fecha_flete', [$desde, $hasta])
            ->sum('costo');

        // Costo de candados vendidos
        $costoCandados = VentaCandado::whereBetween('fecha_venta', [$desde, $hasta])
            ->with('candado')
            ->get()
            ->sum(fn($v) => $v->candado->precio_costo * $v->cantidad);

        $totalEgresos = $gastosVariables + $gastosFijos + $costoFletes + $costoCandados;

        // ── UTILIDADES ──
        $utilidadGeneral  = $totalIngresos - $totalEgresos;
        $utilidadCandados = VentaCandado::whereBetween('fecha_venta', [$desde, $hasta])
            ->with('candado')
            ->get()
            ->sum(fn($v) => ($v->precio_unitario - $v->candado->precio_costo) * $v->cantidad);

        // ── BODEGAS ──
        $totalBodegas      = Bodega::count();
        $bodegasOcupadas   = Bodega::where('estado', 'ocupada')->count();
        $bodegasDisponibles= Bodega::where('estado', 'disponible')->count();
        $ocupacionPct      = $totalBodegas > 0 ? round(($bodegasOcupadas / $totalBodegas) * 100) : 0;

        // ── CLIENTES ──
        $totalClientes     = Cliente::count();
        $clientesNuevosMes = Cliente::whereBetween('created_at', [$desde, $hasta])->count();

        // ── FLETES ──
        $totalFletes    = Flete::whereBetween('fecha_flete', [$desde, $hasta])->count();
        $fletesEntregados = Flete::whereBetween('fecha_flete', [$desde, $hasta])
            ->where('estado', 'entregado')->count();

        // ── CANDADOS ──
        $candadosVendidos = VentaCandado::whereBetween('fecha_venta', [$desde, $hasta])
            ->sum('cantidad');

        // ── PAGOS PENDIENTES Y ATRASADOS ──
       // Pagos pendientes y atrasados
$pagosPendientes = Pago::where('estado', 'pendiente')->count();

$pagosAtrasados  = Pago::where(function($q) {
    $q->where('estado', 'atrasado')
      ->orWhere(function($q2) {
          $q2->where('estado', 'pendiente')
             ->whereDate('fecha_vencimiento', '<', now());
      });
})->count();

// Alertas próximos 5 días
$alertas = Pago::with(['contrato.cliente', 'contrato.bodega'])
    ->where('estado', 'pendiente')
    ->whereDate('fecha_vencimiento', '<=', Carbon::now()->addDays(5))
    ->get();

        // ── GRÁFICA: Ingresos vs Egresos últimos 6 meses ──
        $graficaMeses = [];
        for ($i = 5; $i >= 0; $i--) {
            $fecha  = Carbon::now()->subMonths($i);
            $inicio = $fecha->copy()->startOfMonth();
            $fin    = $fecha->copy()->endOfMonth();
            $label  = $fecha->translatedFormat('M Y');

            $ingresoMes = Pago::whereBetween('fecha_pago', [$inicio, $fin])
                ->where('estado', 'pagado')->sum('monto')
                + VentaCandado::whereBetween('fecha_venta', [$inicio, $fin])->sum('total');

            $egresoMes = GastoVariable::whereBetween('fecha_gasto', [$inicio, $fin])->sum('monto')
                + GastoFijo::activos()->sum('monto_mensual')
                + Flete::whereBetween('fecha_flete', [$inicio, $fin])->sum('costo');

            $graficaMeses[] = [
                'mes'      => $label,
                'ingresos' => (float) $ingresoMes,
                'egresos'  => (float) $egresoMes,
                'utilidad' => (float) ($ingresoMes - $egresoMes),
            ];
        }

        // ── GRÁFICA: Distribución de ingresos ──
        $distribucionIngresos = [
            ['nombre' => 'Rentas', 'valor' => (float) $ingresosPagos],
            ['nombre' => 'Candados', 'valor' => (float) $ingresosVentasCandados],
        ];

        // ── GRÁFICA: Distribución de egresos ──
        $distribucionEgresos = [
            ['nombre' => 'Gastos Fijos',     'valor' => (float) $gastosFijos],
            ['nombre' => 'Gastos Variables',  'valor' => (float) $gastosVariables],
            ['nombre' => 'Fletes',            'valor' => (float) $costoFletes],
            ['nombre' => 'Costo Candados',    'valor' => (float) $costoCandados],
        ];

        // ── GRÁFICA: Bodegas por estado ──
        $graficaBodegas = [
            ['estado' => 'Ocupadas',    'total' => $bodegasOcupadas],
            ['estado' => 'Disponibles', 'total' => $bodegasDisponibles],
        ];

        // ── Últimas bitácoras ──
        $ultimasBitacoras = Bitacora::with(['bodega', 'user'])
            ->latest('fecha_evento')
            ->take(5)
            ->get();

        return Inertia::render('DashboardAdmin', [
            'filtro'               => $filtro,
            'mes'                  => (int) $mes,
            'anio'                 => (int) $anio,
            'totalIngresos'        => (float) $totalIngresos,
            'totalEgresos'         => (float) $totalEgresos,
            'utilidadGeneral'      => (float) $utilidadGeneral,
            'utilidadCandados'     => (float) $utilidadCandados,
            'ingresosPagos'        => (float) $ingresosPagos,
            'ingresosVentasCandados'=> (float) $ingresosVentasCandados,
            'gastosVariables'      => (float) $gastosVariables,
            'gastosFijos'          => (float) $gastosFijos,
            'costoFletes'          => (float) $costoFletes,
            'totalBodegas'         => $totalBodegas,
            'bodegasOcupadas'      => $bodegasOcupadas,
            'bodegasDisponibles'   => $bodegasDisponibles,
            'ocupacionPct'         => $ocupacionPct,
            'totalClientes'        => $totalClientes,
            'clientesNuevosMes'    => $clientesNuevosMes,
            'totalFletes'          => $totalFletes,
            'fletesEntregados'     => $fletesEntregados,
            'candadosVendidos'     => $candadosVendidos,
            'pagosPendientes'      => $pagosPendientes,
            'pagosAtrasados'       => $pagosAtrasados,
            'alertas'              => $alertas,
            'graficaMeses'         => $graficaMeses,
            'distribucionIngresos' => $distribucionIngresos,
            'distribucionEgresos'  => $distribucionEgresos,
            'graficaBodegas'       => $graficaBodegas,
            'ultimasBitacoras'     => $ultimasBitacoras,
        ]);
    }
}