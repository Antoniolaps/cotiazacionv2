<div class="pos-table-panel">
    <!-- Header con Búsqueda y Controles -->
    <div class="pos-table-header">
        
        <div class="pos-table-controls">
            <!-- Búsqueda Rápida -->
            <div class="pos-quick-search">
                <i class="bi bi-search"></i>
                <input type="text" 
                       id="quickSearch"
                       placeholder="Buscar por nombre, SKU o código de barras..." 
                       value="<?= e($_GET['q'] ?? '') ?>"
                       onkeypress="if(event.key === 'Enter') window.location.href='?r=productos/index&q='+this.value"
                       autofocus>
                <kbd>Enter</kbd>
            </div>

            <!-- Acciones -->
            <button class="btn-icon" title="Sincronizar">
                <i class="bi bi-arrow-repeat"></i>
            </button>
            <a href="<?= url('?r=' . $crud->module . '/create') ?>" class="btn-primary-compact">
                <i class="bi bi-plus-lg"></i> Nuevo
            </a>
        </div>
    </div>

    <!-- Filtros Rápidos -->
    <div class="pos-quick-filters">
       
        
        <div class="filter-divider"></div>
        
    </div>

    <!-- Tabla de Productos -->
    <div class="pos-table-container">
        <table class="pos-table">
            <thead>
                <tr>
                    <th class="col-product">Producto</th>
                    <th class="col-sku">Código</th>
                    <th class="col-category">Categoría</th>
                    <th class="col-unit">Unidad</th>
                    <th class="col-stock">Stock</th>
                    <th class="col-price" >Precio</th>
                    <th class="col-cost">Costo</th>
                    <th class="col-actions">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows)): ?>
                    <?php foreach ($rows as $r): ?>
                        <?php 
                            $stock = $r['stock_actual'] ?? 0;
                            $min = $r['stock_minimo'] ?? 0;
                            $stockStatus = $stock > $min ? 'ok' : ($stock > 0 ? 'low' : 'out');
                        ?>
                        <tr class="pos-row" data-product-id="<?= $r['id'] ?>">
                            <!-- Checkbox -->
                            
                            
                            <!-- Producto (Nombre + Imagen mini) -->
                            <td class="col-product">
                                <div class="product-cell">
                                    <div class="product-info">
                                        <div class="product-name"><?= e($r['nombre']) ?></div>
                                        <?php if (!empty($r['descripcion_corta'])): ?>
                                            <div class="product-desc"><?= e($r['descripcion_corta']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- SKU / Código -->
                            <td class="col-sku">
                                <div class="sku-cell">
                                    <div class="sku-code"><?= e($r['codigo'] ?? '-') ?></div>
                                    <?php if (!empty($r['codigo_barras'])): ?>
                                        <div class="sku-barcode">
                                            <i class="bi bi-upc"></i>
                                            <?= e($r['codigo_barras']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <!-- Categoría -->
                            <td class="col-category">
                                <?php if (!empty($r['categoria_nombre'])): ?>
                                    <span class="category-badge" style="background:<?= getStringColor($r['categoria_nombre']) ?>;color:#fff;padding:2px 8px;border-radius:12px;font-size:.75rem;font-weight:600;">
                                        <?= e($r['categoria_nombre']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            
                            <!-- Unidad de Medida -->
                            <td class="col-unit">
                                <span class="unit-badge">
                                    <?= e($r['unidad_medida'] ?? 'UND') ?>
                                </span>
                            </td>
                            
                            <!-- Stock con indicador visual -->
                            <td class="col-stock">
                                <div class="stock-cell stock-<?= $stockStatus ?>">
                                <div class="stock-value"><?= number_format($stock, 0) ?></div>
                                </div>
                            </td>
                            
                            <!-- Precio de Venta -->
                            <td class="col-price">
                                <div class="price-cell">
                                    <span class="price-value">$<?= number_format($r['precio_venta'], 2) ?></span>
                                </div>
                            </td>
                            
                            <!-- Costo -->
                            <td class="col-cost">
                                <span class="cost-value">$<?= number_format($r['precio_compra'], 2) ?></span>
                            </td>
                            
                            <!-- Acciones -->
                            <td class="col-actions">
                                <div class="action-buttons">
                                    <a href="<?= url('?r='.$crud->module.'/show&id='.$r['id']) ?>" class="btn-action btn-view" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if (Auth::role() === 'admin'): ?>
                                    <a href="<?= url('?r='.$crud->module.'/edit&id='.$r['id']) ?>" class="btn-action btn-edit" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="post" action="<?= url('?r='.$crud->module.'/delete') ?>" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= e($r['id']) ?>">
                                        
                                        <button type="submit" class="btn-action btn-delete" title="Eliminar" >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <h3>No se encontraron productos</h3>
                            <p>Intenta ajustar los filtros o agrega un nuevo producto</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer con Paginación -->
    <div class="pos-table-footer">
        <div class="pos-table-info">
            Mostrando <?= e($offset + 1) ?> a <?= e(min($offset + $limit, $totalRows ?? 0)) ?> de <?= e($totalRows ?? 0) ?> productos
        </div>
        
        <div class="pos-table-pagination">
            <form method="get" class="d-inline-flex align-items-center gap-2">
                <input type="hidden" name="r" value="<?= e($crud->module) ?>/index">
                <input type="hidden" name="q" value="<?= e($_GET['q'] ?? '') ?>">
                <label class="text-muted small">Mostrar:</label>
                <select name="limit" class="form-select form-select-sm" style="width: 70px;" onchange="this.form.submit()">
                    <?php foreach ([20, 50, 100, 200, 500] as $l): ?>
                        <option value="<?= $l ?>" <?= ($limit ?? 20) == $l ? 'selected' : '' ?>><?= $l ?></option>
                    <?php endforeach; ?>
                </select>
            </form>

            <?php if (isset($totalPages) && $totalPages > 1): ?>
                <nav class="ms-3">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?r=<?= e($crud->module) ?>/index&p=1&limit=<?= $limit ?>&q=<?= e($_GET['q'] ?? '') ?>">
                                <i class="bi bi-chevron-double-left"></i>
                            </a>
                        </li>
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?r=<?= e($crud->module) ?>/index&p=<?= $page - 1 ?>&limit=<?= $limit ?>&q=<?= e($_GET['q'] ?? '') ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        
                        <?php 
                        $start = max(1, $page - 2);
                        $end = min($totalPages, $page + 2);
                        for ($i = $start; $i <= $end; $i++): 
                        ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?r=<?= e($crud->module) ?>/index&p=<?= $i ?>&limit=<?= $limit ?>&q=<?= e($_GET['q'] ?? '') ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?r=<?= e($crud->module) ?>/index&p=<?= $page + 1 ?>&limit=<?= $limit ?>&q=<?= e($_GET['q'] ?? '') ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?r=<?= e($crud->module) ?>/index&p=<?= $totalPages ?>&limit=<?= $limit ?>&q=<?= e($_GET['q'] ?? '') ?>">
                                <i class="bi bi-chevron-double-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ESTILOS CSS PARA TABLA POS MODERNA -->
<style>
    .pos-table-panel {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Header */
    .pos-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        gap: 16px;
        flex-wrap: wrap;
    }
    .pos-table-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .pos-table-title i {
        font-size: 1.5rem;
        color: #3b82f6;
    }
    .pos-table-title h2 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #0f172a;
    }
    .pos-table-count {
        background: #f1f5f9;
        color: #64748b;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .pos-table-controls {
        display: flex;
        gap: 12px;
        align-items: center;
    }
    .pos-quick-search {
        position: relative;
        display: flex;
        align-items: center;
    }
    .pos-quick-search i {
        position: absolute;
        left: 12px;
        color: #94a3b8;
    }
    .pos-quick-search input {
        padding: 8px 60px 8px 36px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        width: 100%;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    .pos-quick-search input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .pos-quick-search kbd {
        position: absolute;
        right: 12px;
        background: #f1f5f9;
        color: #64748b;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-family: monospace;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-icon:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .btn-primary-compact {
        padding: 8px 16px;
        background: #3b82f6;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-primary-compact:hover {
        background: #2563eb;
    }

    /* Filtros Rápidos */
    .pos-quick-filters {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 16px 24px;
        border-bottom: 1px solid #e2e8f0;
        overflow-x: auto;
    }
    .filter-chip {
        padding: 6px 14px;
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        background: white;
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .filter-chip:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .filter-chip.active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .filter-divider {
        width: 1px;
        height: 24px;
        background: #e2e8f0;
        margin: 0 8px;
    }

    .filter-select-compact {
        padding: 6px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #64748b;
        background: white;
        cursor: pointer;
    }

    /* Tabla */
    .pos-table-container {
        overflow-x: auto;
    }
    .pos-table {
        width: 100%;
        border-collapse: collapse;
    }
    .pos-table thead {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }
    .pos-table thead th {
        padding: 12px 16px;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }
    .pos-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s;
    }
    .pos-table tbody tr:hover {
        background: #f8fafc;
    }
    .pos-table tbody td {
        padding: 12px 16px;
        font-size: 0.875rem;
        vertical-align: middle;
    }

    /* Columnas específicas */
    .col-check { width: 40px; }
    .col-fav { width: 40px; }
    .col-product { min-width: 250px; }
    .col-sku { min-width: 140px; }
    .col-category { min-width: 120px; }
    .col-unit { width: 80px; }
    .col-stock { width: 120px; }
    .col-price { width: 100px; }
    .col-cost { width: 100px; }
    .col-actions { width: 160px; }

    /* Checkbox moderno */
    .checkbox-modern {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #3b82f6;
    }

    /* Botón favorito */
    .btn-fav {
        background: none;
        border: none;
        color: #cbd5e1;
        cursor: pointer;
        font-size: 1.125rem;
        padding: 4px;
        transition: color 0.2s;
    }
    .btn-fav:hover {
        color: #fbbf24;
    }

    /* Celda de producto */
    .product-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .product-thumb {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    .product-thumb i {
        font-size: 1.25rem;
        color: #94a3b8;
    }
    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .product-info {
        flex: 1;
        min-width: 0;
    }
    .product-name {
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .product-desc {
        font-size: 0.75rem;
        color: #94a3b8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* SKU */
    .sku-cell {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .sku-code {
        font-family: monospace;
        font-weight: 600;
        color: #334155;
    }
    .sku-barcode {
        font-size: 0.75rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Categoría */
    .category-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    /* Unidad */
    .unit-badge {
        background: #f1f5f9;
        color: #64748b;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Stock con barra visual */
    .stock-cell {
        position: relative;
    }
    .stock-value {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 4px;
    }
    .stock-bar {
        height: 4px;
        background: #e2e8f0;
        border-radius: 2px;
        overflow: hidden;
    }
    .stock-bar-fill {
        height: 100%;
        transition: width 0.3s;
    }
    .stock-ok .stock-value { color: #10b981; }
    .stock-ok .stock-bar-fill { background: #10b981; }
    
    .stock-low .stock-value { color: #f59e0b; }
    .stock-low .stock-bar-fill { background: #f59e0b; }
    
    .stock-out .stock-value { color: #ef4444; }
    .stock-out .stock-bar-fill { background: #ef4444; }

    .stock-warning {
        position: absolute;
        top: 0;
        right: 0;
        color: #f59e0b;
        font-size: 0.875rem;
    }
    .stock-error {
        position: absolute;
        top: 0;
        right: 0;
        color: #ef4444;
        font-size: 0.875rem;
    }

    /* Precios */
    .price-cell {
        display: flex;
        flex-direction: column;
    }
    .price-value {
        font-weight: 700;
        color: #10b981;
        font-size: 1rem;
    }
    .cost-value {
        color: #64748b;
        font-weight: 500;
    }

    /* Acciones */
    .action-buttons {
        display: flex;
        gap: 4px;
    }
    .btn-action {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 6px;
        background: transparent;
        color: #64748b;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
    }
    .btn-action:hover {
        background: #f1f5f9;
    }
    .btn-add-cart:hover {
        background: #dbeafe;
        color: #3b82f6;
    }
    .btn-view:hover {
        background: #e0e7ff;
        color: #6366f1;
    }
    .btn-edit:hover {
        background: #fef3c7;
        color: #f59e0b;
    }
    .btn-delete:hover {
        background: #fee2e2;
        color: #ef4444;
    }

    /* Estado vacío */
    .empty-state {
        text-align: center;
        padding: 60px 20px !important;
        color: #94a3b8;
    }
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 16px;
    }
    .empty-state h3 {
        font-size: 1.125rem;
        margin-bottom: 8px;
        color: #64748b;
    }

    /* Footer */
    .pos-table-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        flex-wrap: wrap;
        gap: 16px;
    }
    .pos-table-info {
        color: #64748b;
        font-size: 0.875rem;
    }
    .pos-table-pagination {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    /* Paginación */
    .pagination {
        gap: 4px;
    }
    .page-link {
        border-radius: 6px !important;
        border: 1px solid #e2e8f0 !important;
        color: #64748b !important;
        padding: 6px 12px !important;
        font-size: 0.875rem !important;
    }
    .page-link:hover {
        background: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
    }
    .page-item.active .page-link {
        background: #3b82f6 !important;
        border-color: #3b82f6 !important;
        color: white !important;
    }
    .page-item.disabled .page-link {
        opacity: 0.5;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .pos-table-header {
            flex-direction: column;
            align-items: stretch;
        }
        .pos-quick-search input {
            width: 100%;
        }
    }
</style>

<script>
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const search = document.getElementById('quickSearch');
        if (document.activeElement === search) {
            search.value = '';
            search.dispatchEvent(new Event('input'));
        }
    }
});
</script>