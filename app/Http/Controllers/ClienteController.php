<?php
namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClienteController extends Controller
{
    public function index()
    {
        return Inertia::render('Clientes/Index', [
            'clientes' => Cliente::withCount('contratos')
                ->with('contratosActivos.bodega')
                ->orderBy('nombre')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'apellido'  => 'required|string|max:255',
            'telefono'  => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'dpi'       => 'nullable|string|max:20',
            'nit'       => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'notas'     => 'nullable|string',
        ]);
    
        $cliente = Cliente::create($request->all());
    
        // Si viene de contratos, redirigir allá
        $redirect = $request->headers->get('referer');
        return redirect($redirect)->with('success', 'Cliente creado.')->with('nuevo_cliente_id', $cliente->id);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'apellido'  => 'required|string|max:255',
            'telefono'  => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'dpi'       => 'nullable|string|max:20',
            'nit'       => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'notas'     => 'nullable|string',
        ]);

        $cliente->update($request->all());
        return redirect()->back()->with('success', 'Cliente actualizado.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->back()->with('success', 'Cliente eliminado.');
    }
}