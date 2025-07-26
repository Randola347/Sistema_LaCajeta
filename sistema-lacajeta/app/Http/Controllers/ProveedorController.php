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

        \App\Models\Proveedor::create($request->only('nombre', 'descripcion'));

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
    public function destroy(string $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $nombre = $proveedor->nombre;
        $proveedor->delete();

        return redirect()->route('proveedores.index')->with('deleted', $nombre);
    }
    public function undoDelete(Request $request)
    {
        $nombre = $request->input('nombre');

        Proveedor::create([
            'nombre' => $nombre,
            'descripcion' => 'Restaurado',
        ]);

        return redirect()->route('proveedores.index')->with('success', 'Proveedor restaurado correctamente.');
    }
}
