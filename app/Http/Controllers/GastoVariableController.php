<?php

namespace App\Http\Controllers;

use App\Models\GastoVariable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GastoVariableController extends Controller
{
    public function index()
    {
        $gastos = GastoVariable::with('user')
            ->latest('fecha_gasto')
            ->get();

        $stats = [
            'total_mes'     => GastoVariable::totalMesActual(),
            'total_general' => $gastos->sum('monto'),
            'categorias'    => $gastos->groupBy('categoria')
                                ->map->sum('monto'),
        ];

        return Inertia::render('GastosVariables/Index', [
            'gastos' => $gastos,
            'stats'  => $stats,
        ]);
    }

    public function create()
    {
        return Inertia::render('GastosVariables/Create', [
            'categorias' => GastoVariable::$categorias,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'monto'       => 'required|numeric|min:0',
            'fecha_gasto' => 'required|date',
            'categoria'   => 'nullable|string|max:100',
            'comprobante' => 'nullable|string|max:100',
        ]);

        GastoVariable::create([
            ...$request->all(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('gastos-variables.index')
            ->with('success', 'Gasto variable registrado.');
    }

    public function edit(GastoVariable $gastoVariable)
    {
        return Inertia::render('GastosVariables/Edit', [
            'gastoVariable' => $gastoVariable,
            'categorias'    => GastoVariable::$categorias,
        ]);
    }

    public function update(Request $request, GastoVariable $gastoVariable)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'monto'       => 'required|numeric|min:0',
            'fecha_gasto' => 'required|date',
            'categoria'   => 'nullable|string|max:100',
            'comprobante' => 'nullable|string|max:100',
        ]);

        $gastoVariable->update($request->all());

        return redirect()->route('gastos-variables.index')
            ->with('success', 'Gasto variable actualizado.');
    }

    public function destroy(GastoVariable $gastoVariable)
    {
        $gastoVariable->delete();

        return redirect()->route('gastos-variables.index')
            ->with('success', 'Gasto eliminado.');
    }
}