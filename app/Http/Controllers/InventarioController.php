<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use App\Actions\Inventario\AdjustStockAction;
use Exception;

class InventarioController extends Controller
{
    public function index()
    {
        $inventario = Inventario::with(['producto.categoria'])->paginate(15);
        $productos = Producto::where('activo', 1)->get();

        return view('inventario.index', compact('inventario', 'productos'));
    }

    public function movimientos()
    {
        $movimientos = MovimientoInventario::with(['producto', 'usuario'])
            ->orderBy('fecha_movimiento', 'desc')
            ->paginate(20);

        return view('inventario.movimientos', compact('movimientos'));
    }

    public function ajustar(Request $request, AdjustStockAction $adjustStockAction)
    {
        $data = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'stock_nuevo' => 'required|integer|min:0',
            'tipo_movimiento' => 'required|in:entrada,salida,ajuste,devolucion',
            'descripcion' => 'nullable|string',
        ]);

        try {
            $adjustStockAction->execute(
                (int)$data['producto_id'],
                (int)$data['stock_nuevo'],
                $data['tipo_movimiento'],
                $data['descripcion']
            );

            return redirect()->route('inventario.index')->with('success', 'Stock ajustado con éxito.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
