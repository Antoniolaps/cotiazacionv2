<div class="products-panel" style="max-width: 800px; margin: 0 auto;">
    <div class="products-header border-bottom pb-3 mb-4">
        <div class="d-flex align-items-center">
            <h2 class="products-title">
                <div class="products-title-icon"><i class="bi bi-card-text"></i></div>
                <?= e($crud->title) ?> - Detalles
            </h2>
        </div>
        <div class="products-actions">
            <?php if (Auth::role() === 'admin'): ?>
            <a href="<?= url('?r=' . $crud->module . '/edit&id=' . ($row['id'] ?? '')) ?>" class="btn-sync"><i class="bi bi-pencil"></i> Editar</a>
            <?php endif; ?>
            <a href="<?= url('?r=' . $crud->module . '/index') ?>" class="btn-sync"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <div class="row g-4">
        <?php foreach ($crud->fields as $col => $f): ?>
            <?php 
            $val = $row[$col] ?? ''; 
            $display = $val;
            
            // Format options
            if (($f['type'] ?? '') === 'select' && isset($f['options'])) {
                foreach ($f['options'] as $opt) {
                    if ($opt['v'] == $val) {
                        $display = $opt['l'];
                        break;
                    }
                }
            }
            // Format booleans
            if (($f['type'] ?? '') === 'checkbox') {
                $display = $val ? 'Sí' : 'No';
            }
            ?>
            <div class="col-md-6">
                <div class="p-3 bg-light rounded" style="border: 1px solid #edf2f7;">
                    <label class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;"><?= e($f['label']) ?></label>
                    <div class="fs-6 text-dark">
                        <?php if (($f['type'] ?? '') === 'checkbox'): ?>
                            <span class="custom-pill" style="background-color: <?= $val ? '#48BB78' : '#F56565' ?>">
                                <?= e($display) ?>
                            </span>
                        <?php elseif (strpos($col, 'precio') !== false || strpos($col, 'monto') !== false): ?>
                            <span class="fw-medium"><span class="currency-symbol">$</span><?= number_format((float)$val, 2) ?></span>
                        <?php else: ?>
                            <?= e($display) ?: '<span class="text-muted fst-italic">N/A</span>' ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
