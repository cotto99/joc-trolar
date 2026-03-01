<?php
namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Bodega;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ContratoController extends Controller
{
    public function index()
    {
        return Inertia::render('Contratos/Index', [
            'contratos' => Contrato::with('cliente', 'bodega', 'proximoPago')
                ->orderByDesc('created_at')->paginate(20),
            'clientes'  => Cliente::orderBy('nombre')->get(),
            'bodegas'   => Bodega::where('estado', 'disponible')->orderBy('numero')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'    => 'required|exists:clientes,id',
            'bodega_id'     => 'required|exists:bodegas,id',
            'fecha_inicio'  => 'required|date',
            'fecha_fin'     => 'required|date|after:fecha_inicio',
            'monto'         => 'required|numeric|min:0',
            'periodicidad'  => 'required|in:mensual,trimestral,semestral,anual',
            'dia_pago'      => 'required|integer|min:1|max:28',
            'observaciones' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $contrato = Contrato::create([
                'cliente_id'      => $request->cliente_id,
                'bodega_id'       => $request->bodega_id,
                'fecha_inicio'    => $request->fecha_inicio,
                'fecha_fin'       => $request->fecha_fin,
                'monto'           => $request->monto,
                'periodicidad'    => $request->periodicidad,
                'dia_pago'        => $request->dia_pago,
                'observaciones'   => $request->observaciones,
                'numero_contrato' => Contrato::generarNumero(),
                'estado'          => 'activo',
            ]);

            $contrato->bodega->update(['estado' => 'ocupada']);
            $contrato->generarPagos();
        });

        return redirect()->back()->with('success', 'Contrato creado y pagos generados.');
    }

    public function cancelar(Contrato $contrato)
    {
        DB::transaction(function () use ($contrato) {
            $contrato->update(['estado' => 'cancelado']);

            $otrosContratos = Contrato::where('bodega_id', $contrato->bodega_id)
                ->where('estado', 'activo')
                ->where('id', '!=', $contrato->id)
                ->count();

            if ($otrosContratos === 0) {
                $contrato->bodega->update(['estado' => 'disponible']);
            }

            $contrato->pagos()->where('estado', 'pendiente')->update(['estado' => 'vencido']);
        });

        return redirect()->back()->with('success', 'Contrato cancelado.');
    }

    public function descargar(Contrato $contrato)
    {
        $contrato->load('cliente', 'bodega');

        $pdf = Pdf::loadView('contratos.pdf', compact('contrato'))
                  ->setPaper('letter', 'portrait');

        return $pdf->download('Contrato-' . $contrato->numero_contrato . '.pdf');
    }
}