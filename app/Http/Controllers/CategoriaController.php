<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Services\ActivityLogger;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::paginate(15);
        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        $categoria = Categoria::create($data);
        ActivityLogger::log('INSERT', 'categoria', $categoria->id, null, $categoria->toArray());

        return redirect()->route('categorias.index')->with('success', 'Categoría creada con éxito.');
    }

    public function update(Request $request, Categoria $categoria)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $old = $categoria->toArray();
        $categoria->update($data);
        ActivityLogger::log('UPDATE', 'categoria', $categoria->id, $old, $categoria->toArray());

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy(Categoria $categoria)
    {
        $old = $categoria->toArray();
        $categoria->delete();
        ActivityLogger::log('DELETE', 'categoria', $categoria->id, $old, null);

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada.');
    }
}
