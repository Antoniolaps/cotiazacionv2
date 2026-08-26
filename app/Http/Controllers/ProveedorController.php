<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Categoria;
use App\Services\ActivityLogger;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::with('categoria')->paginate(15);
        $categorias = Categoria::where('activo', 1)->get();

        return view('proveedores.index', compact('proveedores', 'categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'ruc' => 'nullable|string|unique:proveedores,ruc',
            'categoria_id' => 'nullable|exists:categoria,id',
            'tipo_proveedor' => 'required|in:distribuidor,fabricante,importador,mayorista,otro',
            'contacto' => 'nullable|string',
            'telefono' => 'nullable|string',
            'email' => 'nullable|email',
            'direccion' => 'nullable|string',
        ]);

        $proveedor = Proveedor::create($data);
        ActivityLogger::log('INSERT', 'proveedores', $proveedor->id, null, $proveedor->toArray());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado exitosamente.');
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'ruc' => 'nullable|string|unique:proveedores,ruc,' . $proveedor->id,
            'categoria_id' => 'nullable|exists:categoria,id',
            'tipo_proveedor' => 'required|in:distribuidor,fabricante,importador,mayorista,otro',
            'contacto' => 'nullable|string',
            'telefono' => 'nullable|string',
            'email' => 'nullable|email',
            'direccion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $old = $proveedor->toArray();
        $proveedor->update($data);
        ActivityLogger::log('UPDATE', 'proveedores', $proveedor->id, $old, $proveedor->toArray());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $old = $proveedor->toArray();
        $proveedor->delete();
        ActivityLogger::log('DELETE', 'proveedores', $proveedor->id, $old, null);

        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado.');
    }
}
