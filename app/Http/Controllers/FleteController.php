<?php

namespace App\Http\Controllers;

use App\Models\Flete;
use App\Models\Bodega;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FleteController extends Controller
{
    public function index()
    {
        $fletes = Flete::with(['bodega', 'cliente'])
            ->latest('fecha_flete')
            ->get();

        $stats = [
            'total'       => $fletes->count(),
            'pendientes'  => $fletes->where('estado', 'pendiente')->count(),
            'en_camino'   => $fletes->where('estado', 'en_camino')->count(),
            'entregados'  => $fletes->where('estado', 'entregado')->count(),
            'monto_total' => $fletes->sum('costo'),
        ];

        return Inertia::render('Fletes/Index', [
            'fletes' => $fletes,
            'stats'  => $stats,
        ]);
    }

    public function create()
    {
        return Inertia::render('Fletes/Create', [
            'bodegas'  => Bodega::orderBy('numero')->get(),
            'clientes' => Cliente::orderBy('nombre')->get(),
            'estados'  => Flete::$estados,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion_carga' => 'required|string|max:255',
            'costo'             => 'required|numeric|min:0',
            'fecha_flete'       => 'required|date',
            'estado'            => 'required|in:pendiente,en_camino,entregado',
        ]);

        Flete::create($request->all());

        return redirect()->route('fletes.index')
            ->with('success', 'Flete registrado correctamente.');
    }

    public function show(Flete $flete)
    {
        $flete->load(['bodega', 'cliente']);

        return Inertia::render('Fletes/Show', [
            'flete' => $flete,
        ]);
    }

    public function edit(Flete $flete)
    {
        return Inertia::render('Fletes/Edit', [
            'flete'    => $flete,
            'bodegas'  => Bodega::orderBy('numero')->get(),
            'clientes' => Cliente::orderBy('nombre')->get(),
            'estados'  => Flete::$estados,
        ]);
    }

    public function update(Request $request, Flete $flete)
    {
        $request->validate([
            'descripcion_carga' => 'required|string|max:255',
            'costo'             => 'required|numeric|min:0',
            'fecha_flete'       => 'required|date',
            'estado'            => 'required|in:pendiente,en_camino,entregado',
        ]);

        $flete->update($request->all());

        return redirect()->route('fletes.index')
            ->with('success', 'Flete actualizado correctamente.');
    }

    public function destroy(Flete $flete)
    {
        $flete->delete();

        return redirect()->route('fletes.index')
            ->with('success', 'Flete eliminado.');
    }
}