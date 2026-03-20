<?php
namespace App\Http\Controllers;

use App\Models\Bodega;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BodegaController extends Controller
{
    public function index()
    {
        return Inertia::render('Bodegas/Index', [
            'bodegas' => Bodega::with('contratoActivo.cliente')->orderBy('numero')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero'      => 'required|string|unique:bodegas,numero',
            'nombre'      => 'nullable|string|max:255',
            'tamanio_m2'  => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        Bodega::create($request->all());
        return redirect()->back()->with('success', 'Bodega creada exitosamente.');
    }

    public function update(Request $request, Bodega $bodega)
    {
        $request->validate([
            'numero'      => 'required|string|unique:bodegas,numero,' . $bodega->id,
            'nombre'      => 'nullable|string|max:255',
            'tamanio_m2'  => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        $bodega->update($request->all());
        return redirect()->back()->with('success', 'Bodega actualizada.');
    }

    public function destroy(Bodega $bodega)
    {
        $bodega->delete(); // soft delete, queda en BD
    
        return redirect()->route('bodegas.index')
            ->with('success', 'Bodega eliminada del sistema correctamente.');
    }
}