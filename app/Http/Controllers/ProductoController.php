<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\Inventario;
use App\Services\ActivityLogger;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Producto::with(['categoria', 'proveedor', 'inventario']);

        if ($search) {
            $query->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('codigo', 'LIKE', "%{$search}%")
                  ->orWhere('codigo_barras', 'LIKE', "%{$search}%");
        }

        $productos = $query->paginate(15);
        return view('productos.index', compact('productos', 'search'));
    }

    public function create()
    {
        $categorias = Categoria::where('activo', 1)->get();
        $proveedores = Proveedor::where('activo', 1)->get();
        return view('productos.create', compact('categorias', 'proveedores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|unique:productos,codigo',
            'codigo_barras' => 'nullable|string|unique:productos,codigo_barras',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'nullable|exists:categoria,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
            'unidad_medida' => 'nullable|string|max:20',
        ]);

        $producto = Producto::create($data);

        // Crear registro en inventario por defecto con stock 0 si no lo crea el trigger
        Inventario::firstOrCreate(['producto_id' => $producto->id], ['stock_actual' => 0, 'stock_reservado' => 0]);

        ActivityLogger::log('INSERT', 'productos', $producto->id, null, $producto->toArray());

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::where('activo', 1)->get();
        $proveedores = Proveedor::where('activo', 1)->get();
        return view('productos.edit', compact('producto', 'categorias', 'proveedores'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'codigo' => 'required|string|unique:productos,codigo,' . $producto->id,
            'codigo_barras' => 'nullable|string|unique:productos,codigo_barras,' . $producto->id,
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'nullable|exists:categoria,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
            'unidad_medida' => 'nullable|string|max:20',
            'activo' => 'boolean',
        ]);

        $old = $producto->toArray();
        $producto->update($data);

        ActivityLogger::log('UPDATE', 'productos', $producto->id, $old, $producto->toArray());

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $old = $producto->toArray();
        $producto->delete();

        ActivityLogger::log('DELETE', 'productos', $producto->id, $old, null);

        return redirect()->route('productos.index')->with('success', 'Producto eliminado.');
    }
}
