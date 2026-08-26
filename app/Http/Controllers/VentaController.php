<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Services\ActivityLogger;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Venta::with(['cliente', 'vendedor']);

        if ($search) {
            $query->where('numero_factura', 'LIKE', "%{$search}%");
        }

        $ventas = $query->orderBy('fecha_venta', 'desc')->paginate(15);
        return view('ventas.index', compact('ventas', 'search'));
    }

    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'vendedor', 'detalles.producto', 'pagos']);
        return view('ventas.show', compact('venta'));
    }

    public function anular(Venta $venta)
    {
        if ($venta->estado === 'anulada') {
            return back()->with('error', 'La venta ya se encuentra anulada.');
        }

        $old = $venta->toArray();
        $venta->estado = 'anulada';
        $venta->save();

        ActivityLogger::log('UPDATE', 'ventas', $venta->id, $old, $venta->toArray());

        return redirect()->route('ventas.index')->with('success', 'Factura ' . $venta->numero_factura . ' anulada.');
    }
}
