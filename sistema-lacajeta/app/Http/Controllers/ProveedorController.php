<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedor::all();
        return view('proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        Proveedor::create($request->only('nombre', 'descripcion'));

        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update($request->only('nombre', 'descripcion'));

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);

        // Guardar datos en la sesión antes de eliminar
        session([
            'deleted_proveedor_data' => [
                'nombre' => $proveedor->nombre,
                'descripcion' => $proveedor->descripcion,
            ]
        ]);

        $proveedor->delete();

        return redirect()->route('proveedores.index')->with('deleted', $proveedor->nombre);
    }

    public function undoDelete(Request $request)
    {
        $data = session('deleted_proveedor_data');

        if (!$data) {
            return redirect()->route('proveedores.index')->with('error', 'No hay datos para restaurar.');
        }

        Proveedor::create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
        ]);

        // Limpiar la sesión
        session()->forget('deleted_proveedor_data');

        return redirect()->route('proveedores.index')->with('success', "Proveedor restaurado correctamente.");
    }
}
