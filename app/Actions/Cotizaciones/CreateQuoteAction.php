<?php

namespace App\Actions\Cotizaciones;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class CreateQuoteAction
{
    /**
     * Registra una cotización (estado = 'cotizacion'). No descuenta stock.
     */
    public function execute(array $data): Venta
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'] ?? [];

            if (empty($items)) {
                throw new Exception('Debe agregar al menos un producto a la cotización.');
            }

            $itbmsRate = (float) env('ITBMS_RATE', 0.07);
            $subtotal  = 0;

            foreach ($items as $it) {
                $subtotal += (float)$it['cantidad'] * (float)$it['precio'];
            }

            $itbms = round($subtotal * $itbmsRate, 2);
            $total = $subtotal + $itbms;

            // Número de cotización consecutivo 
            $count = Venta::where('estado', 'cotizacion')->count() + 1;
            $numero = 'COT-' . ('40722') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);

            $cotizacion = Venta::create([
                'numero_factura'    => $numero,
                'cliente_id'        => $data['cliente_id'] ?? null,
                'cliente_nombre'    => $data['cliente_nombre'] ?? null,
                'punto_facturacion' => $data['punto_facturacion'] ?? null,
                'vendedor_id'       => Auth::id(),
                'fecha_venta'       => now(),
                'subtotal'          => $subtotal,
                'itbms'             => $itbms,
                'total'             => $total,
                'estado'            => 'cotizacion',
            ]);

            foreach ($items as $it) {
                DetalleVenta::create([
                    'venta_id'        => $cotizacion->id,
                    'producto_id'     => (int)$it['id'],
                    'cantidad'        => (int)$it['cantidad'],
                    'precio_unitario' => (float)$it['precio'],
                    'subtotal'        => round((int)$it['cantidad'] * (float)$it['precio'], 2),
                ]);
            }

            ActivityLogger::log('INSERT', 'ventas', $cotizacion->id, null, [
                'cotizacion' => $numero,
                'total'      => $total,
            ]);

            return $cotizacion;
        });
    }
}
