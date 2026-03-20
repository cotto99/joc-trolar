<?php

namespace App\Http\Controllers;

use App\Models\VentaCandado;
use App\Models\Candado;
use App\Models\Cliente;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VentaCandadoController extends Controller
{
    public function index()
    {
        $ventas = VentaCandado::with(['candado', 'cliente', 'contrato.bodega'])
            ->latest('fecha_venta')
            ->get();

        $stats = [
            'total_ventas'   => $ventas->count(),
            'total_ingresos' => $ventas->sum('total'),
            'total_unidades' => $ventas->sum('cantidad'),
        ];

        return Inertia::render('VentasCandados/Index', [
            'ventas' => $ventas,
            'stats'  => $stats,
        ]);
    }

    public function create()
    {
        return Inertia::render('VentasCandados/Create', [
            'candados'  => Candado::where('stock', '>', 0)->get(),
            'clientes'  => Cliente::orderBy('nombre')->get(),
            'contratos' => Contrato::with(['cliente', 'bodega'])
                            ->where('estado', 'activo')
                            ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'candado_id'        => 'required|exists:candados,id',
            'cantidad'          => 'required|integer|min:1',
            'fecha_venta'       => 'required|date',
            'contrato_id'       => 'nullable|exists:contratos,id',
            'notas'             => 'nullable|string',
            'cliente_id'        => 'nullable|exists:clientes,id',
            // Campos para nuevo cliente
            'nuevo_cliente'          => 'nullable|boolean',
            'nuevo_cliente_nombre'   => 'required_if:nuevo_cliente,true|string|max:100',
            'nuevo_cliente_apellido' => 'required_if:nuevo_cliente,true|string|max:100',
            'nuevo_cliente_telefono' => 'nullable|string|max:20',
            'nuevo_cliente_email'    => 'nullable|email|unique:clientes,email',
        ]);
    
        $candado = Candado::findOrFail($request->candado_id);
    
        if ($candado->stock < $request->cantidad) {
            return back()->withErrors([
                'cantidad' => 'Stock insuficiente. Disponible: ' . $candado->stock
            ]);
        }
    
        // Si está creando un cliente nuevo
        $clienteId = $request->cliente_id;
    
        if ($request->nuevo_cliente) {
            $nuevoCliente = \App\Models\Cliente::create([
                'nombre'   => $request->nuevo_cliente_nombre,
                'apellido' => $request->nuevo_cliente_apellido,
                'telefono' => $request->nuevo_cliente_telefono,
                'email'    => $request->nuevo_cliente_email,
            ]);
            $clienteId = $nuevoCliente->id;
        }
    
        $total = $candado->precio_venta * $request->cantidad;
    
        VentaCandado::create([
            'candado_id'      => $candado->id,
            'cliente_id'      => $clienteId, // null = consumidor final
            'contrato_id'     => $request->contrato_id,
            'cantidad'        => $request->cantidad,
            'precio_unitario' => $candado->precio_venta,
            'total'           => $total,
            'fecha_venta'     => $request->fecha_venta,
            'notas'           => $request->notas,
        ]);
    
        $candado->decrement('stock', $request->cantidad);
    
        return redirect()->route('ventas-candados.index')
            ->with('success', "Venta registrada. Total: Q{$total}");
    }

    public function show(VentaCandado $ventaCandado)
    {
        $ventaCandado->load(['candado', 'cliente', 'contrato.bodega']);

        return Inertia::render('VentasCandados/Show', [
            'venta' => $ventaCandado,
        ]);
    }

    public function destroy(VentaCandado $ventaCandado)
    {
        // Restaurar stock al cancelar
        $ventaCandado->candado->increment('stock', $ventaCandado->cantidad);
        $ventaCandado->delete();

        return redirect()->route('ventas-candados.index')
            ->with('success', 'Venta cancelada y stock restaurado.');
    }
}