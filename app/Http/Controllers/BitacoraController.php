<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Bodega;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BitacoraController extends Controller
{
    public function index()
    {
        $bitacoras = Bitacora::with(['user', 'bodega', 'cliente'])
            ->latest('fecha_evento')
            ->get();

        return Inertia::render('Bitacoras/Index', [
            'bitacoras' => $bitacoras
        ]);
    }

    public function create()
    {
        return Inertia::render('Bitacoras/Create', [
            'bodegas'    => Bodega::orderBy('numero')->get(),
            'clientes'   => Cliente::orderBy('nombre')->get(),
            'tipos'      => Bitacora::$tipos,
            'prioridades'=> Bitacora::$prioridades,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo'         => 'required|in:evento,visita,incidente,mantenimiento',
            'titulo'       => 'required|string|max:200',
            'descripcion'  => 'required|string',
            'prioridad'    => 'required|in:baja,media,alta',
            'fecha_evento' => 'required|date',
        ]);

        Bitacora::create([
            ...$request->all(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('bitacoras.index')
            ->with('success', 'Registro en bitácora creado correctamente.');
    }

    public function show(Bitacora $bitacora)
    {
        $bitacora->load(['user', 'bodega', 'cliente']);
        return Inertia::render('Bitacoras/Show', [
            'bitacora' => $bitacora
        ]);
    }

    public function edit(Bitacora $bitacora)
    {
        return Inertia::render('Bitacoras/Edit', [
            'bitacora'   => $bitacora,
            'bodegas'    => Bodega::orderBy('numero')->get(),
            'clientes'   => Cliente::orderBy('nombre')->get(),
            'tipos'      => Bitacora::$tipos,
            'prioridades'=> Bitacora::$prioridades,
        ]);
    }

    public function update(Request $request, Bitacora $bitacora)
    {
        $request->validate([
            'tipo'         => 'required|in:evento,visita,incidente,mantenimiento',
            'titulo'       => 'required|string|max:200',
            'descripcion'  => 'required|string',
            'prioridad'    => 'required|in:baja,media,alta',
            'fecha_evento' => 'required|date',
        ]);

        $bitacora->update($request->all());

        return redirect()->route('bitacoras.index')
            ->with('success', 'Bitácora actualizada correctamente.');
    }

    public function destroy(Bitacora $bitacora)
    {
        $bitacora->delete();
        return redirect()->route('bitacoras.index')
            ->with('success', 'Registro eliminado.');
    }
}