<?php

namespace App\Actions\Ventas;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Pago;
use App\Models\Inventario;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class CreateSaleAction
{
    /**
     * Procesa y registra una venta completa desde la terminal POS o interfaz de ventas.
     */
    public function execute(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $clienteId = $data['cliente_id'] ?? null;
            $metodoPago = $data['metodo_pago'] ?? 'efectivo';
            $montoRecibido = (float)($data['monto_recibido'] ?? 0);
            $items = $data['items'] ?? [];

            if (empty($items)) {
                throw new Exception('El carrito de compras está vacío.');
            }

            if ($metodoPago === 'tarjeta') {
                $metodoPago = 'tarjeta_credito';
            }

            $itbmsRate = (float) env('ITBMS_RATE', 0.07);
            $subtotal = 0;

            foreach ($items as $it) {
                $subtotal += (float)$it['cantidad'] * (float)$it['precio'];
            }

            $itbms = round($subtotal * $itbmsRate, 2);
            $total = $subtotal + $itbms;

            if ($metodoPago === 'efectivo' && $montoRecibido < $total - 0.005) {
                throw new Exception('Monto recibido insuficiente.');
            }

            // Generar número de factura único (Formato FAC-YYYYMMDD-XXXX)
            $factura = 'FAC-' . date('Ymd') . '-' . str_pad((string)(Venta::count() + 1), 4, '0', STR_PAD_LEFT);

            $venta = Venta::create([
                'numero_factura' => $factura,
                'cliente_id' => $clienteId,
                'vendedor_id' => Auth::id(),
                'fecha_venta' => now(),
                'subtotal' => $subtotal,
                'itbms' => $itbms,
                'total' => $total,
                'estado' => 'pagada',
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            foreach ($items as $it) {
                $pid = (int)$it['id'];
                $cant = (int)$it['cantidad'];
                $precio = (float)$it['precio'];
                $subitem = round($cant * $precio, 2);

                $inv = Inventario::where('producto_id', $pid)->first();
                $stockActual = $inv ? (int)$inv->stock_actual : 0;

                if ($stockActual < $cant) {
                    throw new Exception("Stock insuficiente para el producto: " . ($it['nombre'] ?? "ID $pid"));
                }

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $pid,
                    'cantidad' => $cant,
                    'precio_unitario' => $precio,
                    'subtotal' => $subitem,
                ]);
            }

            $cambio = ($metodoPago === 'efectivo') ? max(0, $montoRecibido - $total) : 0;

            Pago::create([
                'venta_id' => $venta->id,
                'monto' => $total,
                'moneda' => 'USD',
                'monto_recibido' => $montoRecibido > 0 ? $montoRecibido : $total,
                'cambio' => $cambio,
                'fecha_pago' => now(),
                'metodo_pago' => $metodoPago,
                'estado' => 'aprobado',
                'referencia' => $factura,
                'usuario_id' => Auth::id(),
            ]);

            ActivityLogger::log('INSERT', 'ventas', $venta->id, null, ['factura' => $factura, 'total' => $total]);

            return [
                'ok' => true,
                'factura' => $factura,
                'venta_id' => $venta->id,
                'total' => $total,
                'cambio' => round($cambio, 2),
                'url_factura' => route('ventas.show', $venta->id),
            ];
        });
    }
}
