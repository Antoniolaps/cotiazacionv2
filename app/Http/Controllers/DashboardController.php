<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Inventario;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVentas = Venta::where('estado', 'pagada')->sum('total');
        $ventasHoy = Venta::where('estado', 'pagada')->whereDate('fecha_venta', today())->sum('total');
        $totalProductos = Producto::where('activo', 1)->count();
        $totalClientes = Cliente::where('activo', 1)->count();

        $bajoStock = Inventario::with('producto')
            ->join('productos', 'productos.id', '=', 'inventario.producto_id')
            ->whereColumn('inventario.stock_actual', '<=', 'productos.stock_minimo')
            ->where('productos.activo', 1)
            ->select('inventario.*')
            ->limit(5)
            ->get();

        $ultimasVentas = Venta::with(['cliente', 'vendedor'])
            ->orderBy('fecha_venta', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('totalVentas', 'ventasHoy', 'totalProductos', 'totalClientes', 'bajoStock', 'ultimasVentas'));
    }

    public function apiVentasChart()
    {
        $ventasPorDia = Venta::select(DB::raw('DATE(fecha_venta) as fecha'), DB::raw('SUM(total) as total'))
            ->where('estado', 'pagada')
            ->where('fecha_venta', '>=', now()->subDays(7))
            ->groupBy('fecha')
            ->orderBy('fecha', 'asc')
            ->get();

        return response()->json($ventasPorDia);
    }
}
