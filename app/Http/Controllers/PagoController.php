<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;

class PagoController extends Controller
{
    public function index()
    {
        $pagos = Pago::with(['venta', 'usuario'])
            ->orderBy('fecha_pago', 'desc')
            ->paginate(20);

        return view('pagos.index', compact('pagos'));
    }
}
