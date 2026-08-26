<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Inventario;
use App\Actions\Ventas\CreateSaleAction;
use Exception;

class PosController extends Controller
{
    public function terminal()
    {
        $categorias = Categoria::where('activo', 1)->orderBy('nombre')->get();
        $clientes = Cliente::where('activo', 1)->orderBy('nombre')->get();

        return view('pos.terminal', compact('categorias', 'clientes'));
    }

    public function apiProductos(Request $request)
    {
        $q = trim($request->input('q', ''));
        $barcode = trim($request->input('barcode', ''));
        $categoriaId = (int)$request->input('categoria_id', 0);

        $query = Producto::with(['categoria', 'inventario'])
            ->where('productos.activo', 1);

        if ($barcode !== '') {
            $query->where(function($b) use ($barcode) {
                $b->where('codigo_barras', $barcode)
                  ->orWhere('codigo', $barcode);
            });
        } elseif ($q !== '') {
            $query->where(function($s) use ($q) {
                $s->where('nombre', 'LIKE', "%{$q}%")
                  ->orWhere('codigo', 'LIKE', "%{$q}%")
                  ->orWhere('codigo_barras', 'LIKE', "%{$q}%");
            });
        }

        if ($categoriaId > 0) {
            $query->where('categoria_id', $categoriaId);
        }

        $productos = $query->limit(100)->get()->map(function($p) {
            return [
                'id' => $p->id,
                'codigo' => $p->codigo,
                'codigo_barras' => $p->codigo_barras,
                'nombre' => $p->nombre,
                'precio_venta' => (float)$p->precio_venta,
                'unidad_medida' => $p->unidad_medida,
                'categoria_nombre' => $p->categoria->nombre ?? '',
                'stock' => (int)($p->inventario->stock_actual ?? 0),
            ];
        });

        return response()->json($productos);
    }

    public function apiStock(Request $request)
    {
        $id = (int)$request->input('id', 0);
        $inv = Inventario::where('producto_id', $id)->first();

        return response()->json(['stock' => (int)($inv->stock_actual ?? 0)]);
    }

    public function apiVender(Request $request, CreateSaleAction $createSaleAction)
    {
        try {
            $result = $createSaleAction->execute($request->all());
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function customerDisplay()
    {
        return view('pos.customer_display');
    }
}
