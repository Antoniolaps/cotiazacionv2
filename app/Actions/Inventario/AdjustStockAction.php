<?php

namespace App\Actions\Inventario;

use App\Models\Inventario;
use App\Models\MovimientoInventario;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class AdjustStockAction
{
    /**
     * Ajusta el stock de un producto y registra el movimiento correspondiente.
     */
    public function execute(int $productoId, int $nuevaCantidad, string $tipo = 'ajuste', ?string $descripcion = null): void
    {
        DB::transaction(function () use ($productoId, $nuevaCantidad, $tipo, $descripcion) {
            $inv = Inventario::firstOrCreate(
                ['producto_id' => $productoId],
                ['stock_actual' => 0, 'stock_reservado' => 0]
            );

            $stockAnterior = (int)$inv->stock_actual;
            $delta = $nuevaCantidad - $stockAnterior;

            $inv->stock_actual = $nuevaCantidad;
            $inv->save();

            MovimientoInventario::create([
                'producto_id' => $productoId,
                'usuario_id' => Auth::id(),
                'tipo_movimiento' => $tipo,
                'cantidad' => abs($delta),
                'fecha_movimiento' => now(),
                'descripcion' => $descripcion ?? 'Ajuste manual de inventario',
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $nuevaCantidad,
            ]);

            ActivityLogger::log('UPDATE', 'inventario', $inv->id, ['stock_actual' => $stockAnterior], ['stock_actual' => $nuevaCantidad]);
        });
    }
}
