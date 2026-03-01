<?php
namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->get('mes', date('Y-m'));
    
        return Inertia::render('Pagos/Index', [
            'mes'   => $mes,
            'pagos' => Pago::with('cliente', 'bodega', 'contrato')
                ->whereRaw("DATE_FORMAT(fecha_vencimiento, '%Y-%m') = ?", [$mes])
                ->orderBy('fecha_vencimiento')
                ->paginate(50),
        ]);
    }

    public function marcarPagado(Request $request, Pago $pago)
    {
        $request->validate([
            'fecha_pago'  => 'required|date',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notas'       => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('comprobante')) {
            $path = $request->file('comprobante')->store('comprobantes', 'public');
        }

        $pago->update([
            'estado'     => 'pagado',
            'fecha_pago' => $request->fecha_pago,
            'comprobante'=> $path,
            'notas'      => $request->notas,
        ]);

        return redirect()->route('pagos.index')->with('success', 'Pago registrado.');
    }
}