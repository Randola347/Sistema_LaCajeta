<?php

namespace App\Http\Controllers;

use App\Models\Deudor;
use App\Models\Factura;
use Illuminate\Http\Request;

class DeudorController extends Controller
{
    /**
     * Muestra la lista de deudores.
     */
    public function index()
    {
        $deudores = Deudor::all();
        return view('deudores.index', compact('deudores'));
    }

    /**
     * Muestra el formulario para crear un nuevo deudor.
     */
    public function create()
    {
        return view('deudores.create');
    }

    /**
     * Almacena un nuevo deudor en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        Deudor::create($request->only('nombre', 'descripcion'));

        // Redirige de nuevo a la página anterior (facturas.pendientes)
        return redirect()->route('deudores.index')->with('success', 'Deudor agregado correctamente.');
    }

    /**
     * Muestra el formulario para editar un deudor existente.
     */
    public function edit(Deudor $deudor)
    {
        return view('deudores.edit', compact('deudor'));
    }

    /**
     * Actualiza un deudor en la base de datos.
     */
    public function update(Request $request, Deudor $deudor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $deudor->update($request->only('nombre', 'descripcion'));

        return redirect()->route('deudores.index')->with('success', 'Deudor actualizado correctamente.');
    }

    /**
     * Elimina un deudor de la base de datos.
     */
    public function destroy(Deudor $deudor)
    {
        $deudor->delete();

        return redirect()->route('deudores.index')->with('success', 'Deudor eliminado correctamente.');
    }

    public function marcarPagada($id)
    {
        $factura = Factura::findOrFail($id);
        $factura->estado = 'Pagada';
        $factura->save();

        return back()->with('success', "Factura #{$factura->id} marcada como pagada.");
    }
}
