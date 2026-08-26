<?php

namespace App\Actions\Cotizaciones;

use App\Models\Venta;
use App\Models\Inventario;
use App\Models\Pago;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class ConvertQuoteToSaleAction
{
    /**
     * Convierte una cotización en una venta formal pagada/factura y descuenta inventario.
     */
    public function execute(Venta $cotizacion, string $metodoPago = 'efectivo'): Venta
    {
        if ($cotizacion->estado !== 'cotizacion') {
            throw new Exception('El documento ya ha sido procesado o no es una cotización.');
        }

        return DB::transaction(function () use ($cotizacion, $metodoPago) {
            $cotizacion->load('detalles');

            // Verificar stock disponible para cada ítem
            foreach ($cotizacion->detalles as $d) {
                $inv = Inventario::where('producto_id', $d->producto_id)->first();
                $stockActual = $inv ? (int)$inv->stock_actual : 0;
                if ($stockActual < $d->cantidad) {
                    throw new Exception("Stock insuficiente para convertir cotización. Producto ID {$d->producto_id} disponible: {$stockActual}.");
                }
            }

            // Cambiar estado a pagada
            $old = $cotizacion->toArray();
            $cotizacion->estado = 'pagada';
            $cotizacion->fecha_venta = now();
            $cotizacion->save();

            // Registrar pago
            Pago::create([
                'venta_id' => $cotizacion->id,
                'monto' => $cotizacion->total,
                'moneda' => 'USD',
                'monto_recibido' => $cotizacion->total,
                'cambio' => 0,
                'fecha_pago' => now(),
                'metodo_pago' => $metodoPago,
                'estado' => 'aprobado',
                'referencia' => $cotizacion->numero_factura,
                'usuario_id' => Auth::id(),
            ]);

            ActivityLogger::log('UPDATE', 'ventas', $cotizacion->id, $old, $cotizacion->toArray());

            return $cotizacion;
        });
    }
}
