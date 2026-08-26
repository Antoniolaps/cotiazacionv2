<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Actions\Cotizaciones\CreateQuoteAction;
use App\Actions\Cotizaciones\ConvertQuoteToSaleAction;
use Exception;

class CotizacionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Venta::with(['cliente', 'vendedor'])
            ->where('estado', 'cotizacion');

        if ($search) {
            $query->where('numero_factura', 'LIKE', "%{$search}%");
        }

        $cotizaciones = $query->orderBy('fecha_venta', 'desc')->paginate(15);
        return view('cotizaciones.index', compact('cotizaciones', 'search'));
    }

    public function create()
    {
        // Generar número de cotización preview (readonly en el form)
        $count = Venta::where('estado', 'cotizacion')->count() + 1;
        // Prefijo estático 40722- con relleno de 4 dígitos
        $numeroCotizacion = '40722-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        return view('cotizaciones.create', compact('numeroCotizacion'));
    }

    public function store(Request $request, CreateQuoteAction $createQuoteAction)
    {
        $request->validate([
            'cliente_id'   => 'nullable|exists:clientes,id',
            'cliente_nombre' => 'nullable|string|max:255',
            'punto_facturacion' => 'nullable|string|max:100',
            'items'        => 'required|array|min:1',
            'observaciones'=> 'nullable|string',
        ]);

        try {
            $cotizacion = $createQuoteAction->execute($request->all());
            return redirect()->route('cotizaciones.show', $cotizacion->id)
                ->with('success', 'Cotización generada exitosamente.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Venta $cotizacion)
    {
        if ($cotizacion->estado !== 'cotizacion') {
            return redirect()->route('ventas.show', $cotizacion->id);
        }

        $cotizacion->load(['cliente', 'vendedor', 'detalles.producto']);
        return view('cotizaciones.show', compact('cotizacion'));
    }

    public function convertir(Venta $cotizacion, ConvertQuoteToSaleAction $convertQuoteToSaleAction)
    {
        try {
            $venta = $convertQuoteToSaleAction->execute($cotizacion);
            return redirect()->route('ventas.show', $venta->id)
                ->with('success', 'Cotización convertida en Venta/Factura con éxito.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ─── BÚSQUEDA MODAL (un solo request por acción manual) ──────────────────

    /**
     * Busca clientes por código o nombre (llamado desde modal).
     */
    public function buscarCliente(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $clientes = Cliente::where('activo', 1)
            ->where(function ($query) use ($q) {
                $query->where('codigo', 'LIKE', "%{$q}%")
                      ->orWhere('nombre', 'LIKE', "%{$q}%")
                      ->orWhere('cedula_ruc', 'LIKE', "%{$q}%");
            })
            ->select('id', 'codigo', 'nombre', 'cedula_ruc', 'telefono', 'direccion', 'email')
            ->orderBy('nombre')
            ->limit(30)
            ->get();

        return response()->json($clientes);
    }

    /**
     * Busca productos por código o nombre (llamado desde modal).
     */
    public function buscarProducto(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $productos = Producto::where('activo', 1)
            ->where(function ($query) use ($q) {
                $query->where('codigo', 'LIKE', "%{$q}%")
                      ->orWhere('nombre', 'LIKE', "%{$q}%");
            })
            ->select('id', 'codigo', 'nombre', 'precio_venta', 'descripcion')
            ->orderBy('nombre')
            ->limit(50)
            ->get();

        return response()->json($productos);
    }

    /**
     * Busca proveedores por código o nombre (llamado desde modal).
     */
    public function buscarProveedor(Request $request)
    {
        $q = trim($request->input('q', ''));

        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $proveedores = Proveedor::where('activo', 1)
            ->where(function ($query) use ($q) {
                $query->where('codigo', 'LIKE', "%{$q}%")
                      ->orWhere('nombre', 'LIKE', "%{$q}%");
            })
            ->select('id', 'codigo', 'nombre', 'telefono', 'email')
            ->orderBy('nombre')
            ->limit(20)
            ->get();

        return response()->json($proveedores);
    }

    /**
     * Validación en lote de códigos (usado para importar Excel).
     * Recibe: { codigos: ["AAA","BBB","CCC"] }
     * Devuelve: { encontrados: [...], no_encontrados: ["BBB"] }
     */
    public function validarLote(Request $request)
    {
        $codigos = $request->input('codigos', []);

        if (empty($codigos) || !is_array($codigos)) {
            return response()->json(['encontrados' => [], 'no_encontrados' => []]);
        }

        $codigos = array_map('strtoupper', array_map('trim', $codigos));

        $encontrados = Producto::where('activo', 1)
            ->whereIn('codigo', $codigos)
            ->select('id', 'codigo', 'nombre', 'precio_venta')
            ->get();

        $codigosEncontrados = $encontrados->pluck('codigo')->toArray();
        $noEncontrados = array_values(array_diff($codigos, $codigosEncontrados));

        return response()->json([
            'encontrados'    => $encontrados,
            'no_encontrados' => $noEncontrados,
        ]);
    }
    
}

