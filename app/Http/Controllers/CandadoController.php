<?php

namespace App\Http\Controllers;

use App\Models\Candado;
use App\Models\VentaCandado;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CandadoController extends Controller
{
    public function index()
    {
        $candados = Candado::withCount('ventas')->get();

        $stats = [
            'total_stock'    => $candados->sum('stock'),
            'stock_bajo'     => $candados->filter(fn($c) => $c->stock <= $c->stock_minimo)->count(),
            'total_vendidos' => VentaCandado::sum('cantidad'),
            'ingresos_total' => VentaCandado::sum('total'),
        ];

        return Inertia::render('Candados/Index', [
            'candados' => $candados,
            'stats'    => $stats,
        ]);
    }

    public function create()
    {
        return Inertia::render('Candados/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo'         => 'required|string|max:100',
            'precio_costo' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);

        Candado::create($request->all());

        return redirect()->route('candados.index')
            ->with('success', 'Candado registrado en inventario.');
    }

    public function show(Candado $candado)
    {
        $candado->load('ventas.cliente');

        return Inertia::render('Candados/Show', [
            'candado' => $candado,
        ]);
    }

    public function edit(Candado $candado)
    {
        return Inertia::render('Candados/Edit', [
            'candado' => $candado,
        ]);
    }

    public function update(Request $request, Candado $candado)
    {
        $request->validate([
            'tipo'         => 'required|string|max:100',
            'precio_costo' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);

        $candado->update($request->all());

        return redirect()->route('candados.index')
            ->with('success', 'Candado actualizado.');
    }

    public function destroy(Candado $candado)
    {
        $candado->delete();

        return redirect()->route('candados.index')
            ->with('success', 'Candado eliminado.');
    }
}