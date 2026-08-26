<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        $ventasMes = Venta::where('estado', 'pagada')
            ->whereMonth('fecha_venta', now()->month)
            ->sum('total');

        $topProductos = DB::table('detalle_ventas')
            ->join('productos', 'productos.id', '=', 'detalle_ventas.producto_id')
            ->select('productos.nombre', DB::raw('SUM(detalle_ventas.cantidad) as total_vendido'))
            ->groupBy('productos.id', 'productos.nombre')
            ->orderBy('total_vendido', 'desc')
            ->limit(10)
            ->get();

        return view('reportes.index', compact('ventasMes', 'topProductos'));
    }
}
