<?php

namespace App\Http\Controllers;

use App\Models\GastoFijo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GastoFijoController extends Controller
{
    public function index()
    {
        $activos   = GastoFijo::activos()->get();
        $inactivos = GastoFijo::inactivos()->get();
        $total     = GastoFijo::totalMensual();

        return Inertia::render('GastosFijos/Index', [
            'activos'   => $activos,
            'inactivos' => $inactivos,
            'total'     => $total,
        ]);
    }

    public function create()
    {
        return Inertia::render('GastosFijos/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:100',
            'monto_mensual' => 'required|numeric|min:0',
            'dia_pago'      => 'required|integer|min:1|max:28',
        ]);

        GastoFijo::create($request->all());

        return redirect()->route('gastos-fijos.index')
            ->with('success', 'Gasto fijo registrado.');
    }

    public function edit(GastoFijo $gastoFijo)
    {
        return Inertia::render('GastosFijos/Edit', [
            'gastoFijo' => $gastoFijo,
        ]);
    }

    public function update(Request $request, GastoFijo $gastoFijo)
    {
        $request->validate([
            'nombre'        => 'required|string|max:100',
            'monto_mensual' => 'required|numeric|min:0',
            'dia_pago'      => 'required|integer|min:1|max:28',
        ]);

        $gastoFijo->update($request->all());

        return redirect()->route('gastos-fijos.index')
            ->with('success', 'Gasto fijo actualizado.');
    }

    public function desactivar(Request $request, GastoFijo $gastoFijo)
    {
        $request->validate([
            'justificacion_inactivo' => 'required|string|min:10',
        ]);

        $gastoFijo->update([
            'activo'                 => false,
            'justificacion_inactivo' => $request->justificacion_inactivo,
        ]);

        return redirect()->route('gastos-fijos.index')
            ->with('success', 'Gasto fijo desactivado con justificación.');
    }

    public function reactivar(GastoFijo $gastoFijo)
    {
        $gastoFijo->update([
            'activo'                 => true,
            'justificacion_inactivo' => null,
        ]);

        return redirect()->route('gastos-fijos.index')
            ->with('success', 'Gasto fijo reactivado.');
    }

    public function destroy(GastoFijo $gastoFijo)
    {
        $gastoFijo->delete();

        return redirect()->route('gastos-fijos.index')
            ->with('success', 'Gasto fijo eliminado.');
    }
}