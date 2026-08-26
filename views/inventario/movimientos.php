<?php require __DIR__ . '/../layouts/header.php'; ?>
<h3>Movimientos de inventario</h3>
<div class="card p-3"><table class="table table-sm table-hover align-middle">
<thead>
    <tr>
        <th>Fecha</th>
        <th>Producto</th>
        <th>Tipo</th>
        <th>Nº Doc.</th>
        <th class="text-end">Cantidad</th>
        <th class="text-end">Anterior</th>
        <th class="text-end">Nuevo</th>
        <th>Usuario</th>
        <th>Obs.</th>
    </tr>
</thead>
<tbody>
<?php foreach ($rows as $r): ?>
    <tr>
        <td><small class="text-muted"><?= e($r['fecha_movimiento']) ?></small></td>
        <td class="fw-medium"><?= e($r['producto']) ?></td>
        <td>
            <?php if($r['tipo_movimiento'] === 'entrada'): ?>
                <span class="badge bg-success">Entrada</span>
            <?php elseif($r['tipo_movimiento'] === 'salida'): ?>
                <span class="badge bg-danger">Salida</span>
            <?php else: ?>
                <span class="badge bg-info"><?= e(ucfirst($r['tipo_movimiento'])) ?></span>
            <?php endif; ?>
        </td>
        <td>
            <?php 
                if ($r['factura_venta']) echo '<span class="badge bg-light text-dark border"><i class="bi bi-receipt me-1"></i>' . e($r['factura_venta']) . '</span>';
                elseif ($r['factura_compra']) echo '<span class="badge bg-light text-dark border"><i class="bi bi-cart me-1"></i>' . e($r['factura_compra']) . '</span>';
                else echo '<span class="text-muted">-</span>';
            ?>
        </td>
        <td class="text-end"><?= e($r['cantidad']) ?></td>
        <td class="text-end"><?= e($r['stock_anterior']) ?></td>
        <td class="text-end"><strong><?= e($r['stock_nuevo']) ?></strong></td>
        <td><?= e($r['usuario'] ?? '—') ?></td>
        <td><small><?= e($r['descripcion']) ?><?= $r['observaciones'] ? ' - '.e($r['observaciones']) : '' ?></small></td>
    </tr>
<?php endforeach; ?>
</tbody>
</table></div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
