<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenCompra;
use App\Models\Proveedor;

class CompraController extends Controller
{
    public function index()
    {
        $compras = OrdenCompra::with(['proveedor', 'usuario'])
            ->orderBy('fecha_orden', 'desc')
            ->paginate(15);

        return view('compras.index', compact('compras'));
    }

    public function show(OrdenCompra $compra)
    {
        $compra->load(['proveedor', 'usuario', 'detalles.producto']);
        return view('compras.show', compact('compra'));
    }
}
