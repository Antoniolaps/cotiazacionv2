<style>
/* ================================================================
   CRUD PANEL — DISEÑO PREMIUM
================================================================ */
:root {
    --crud-accent:   #6366f1;
    --crud-success:  #10b981;
    --crud-warning:  #f59e0b;
    --crud-danger:   #ef4444;
    --crud-info:     #06b6d4;
    --crud-border:   #e2e8f0;
    --crud-bg:       #f8fafc;
    --crud-card:     #ffffff;
    --crud-text:     #0f172a;
    --crud-muted:    #64748b;
    --crud-radius:   12px;
    --crud-shadow:   0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.06);
}

.crud-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}
.crud-page-title {
    display: flex;
    align-items: center;
    gap: 12px;
}
.crud-page-title .title-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 12px rgba(99,102,241,.3);
}
.crud-page-title h1 {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--crud-text);
    margin: 0;
    text-transform: capitalize;
}
.crud-page-title p {
    color: var(--crud-muted);
    font-size: .85rem;
    margin: 0;
}

.btn-crud-new {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 18px;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: white;
    border-radius: 8px;
    font-weight: 700;
    font-size: .875rem;
    text-decoration: none;
    transition: all .2s;
    box-shadow: 0 4px 12px rgba(99,102,241,.25);
    border: none;
}
.btn-crud-new:hover { color: white; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.4); }

.crud-toolbar {
    background: var(--crud-card);
    border: 1px solid var(--crud-border);
    border-radius: var(--crud-radius);
    padding: 14px 20px;
    margin-bottom: 20px;
    display: flex;
    gap: 12px;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    box-shadow: var(--crud-shadow);
}
.crud-search-wrap {
    position: relative;
    flex: 1;
    min-width: 200px;
}
.crud-search-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--crud-muted);
}
.crud-search-wrap input {
    width: 100%;
    padding: 8px 12px 8px 36px;
    border: 1.5px solid var(--crud-border);
    border-radius: 8px;
    font-size: .875rem;
    transition: border-color .2s;
    background: var(--crud-bg);
    color: var(--crud-text);
}
.crud-search-wrap input:focus { outline: none; border-color: var(--crud-accent); }

.crud-filter-select {
    padding: 8px 12px;
    border: 1.5px solid var(--crud-border);
    border-radius: 8px;
    font-size: .875rem;
    background: var(--crud-bg);
    color: var(--crud-muted);
    cursor: pointer;
}
.crud-filter-select:focus { outline: none; border-color: var(--crud-accent); }

.crud-table-card {
    background: var(--crud-card);
    border: 1px solid var(--crud-border);
    border-radius: var(--crud-radius);
    overflow: hidden;
    box-shadow: var(--crud-shadow);
    margin-bottom: 20px;
}
.crud-table {
    width: 100%;
    border-collapse: collapse;
}
.crud-table thead {
    background: var(--crud-bg);
    border-bottom: 2px solid var(--crud-border);
}
.crud-table thead th {
    padding: 12px 16px;
    font-size: .72rem;
    font-weight: 700;
    color: var(--crud-muted);
    text-transform: uppercase;
    letter-spacing: .06em;
    white-space: nowrap;
}
.crud-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background .12s;
}
.crud-table tbody tr:last-child { border-bottom: none; }
.crud-table tbody tr:hover { background: #f8fafc; }
.crud-table tbody td {
    padding: 14px 16px;
    font-size: .875rem;
    vertical-align: middle;
}

.crud-actions { display: flex; gap: 5px; justify-content: flex-end; }
.btn-crud-action {
    width: 32px;
    height: 32px;
    border-radius: 7px;
    border: 1.5px solid var(--crud-border);
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    cursor: pointer;
    transition: all .15s;
    text-decoration: none;
    color: var(--crud-muted);
}
.btn-crud-action:hover { color: var(--crud-text); border-color: #cbd5e1; background: #f8fafc; }
.btn-crud-action.btn-edit:hover  { border-color: #6366f1; color: #6366f1; background: rgba(99,102,241,.06); }
.btn-crud-action.btn-delete:hover { border-color: var(--crud-danger); color: var(--crud-danger); background: rgba(239,68,68,.06); }
.btn-crud-action.btn-view:hover { border-color: #06b6d4; color: #06b6d4; background: rgba(6,182,212,.06); }

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 700;
}
.status-active   { background: rgba(16,185,129,.1);  color: #059669; }
.status-inactive { background: rgba(239,68,68,.1); color: #dc2626; }
.status-dot { width: 6px; height: 6px; border-radius: 50%; }

.currency-val { font-weight: 600; }
.currency-sym { color: var(--crud-muted); font-size: 0.8em; margin-right: 2px; }

.crud-empty {
    text-align: center;
    padding: 60px 20px;
    color: var(--crud-muted);
}
.crud-empty i { font-size: 2.5rem; margin-bottom: 12px; display: block; color: #cbd5e1; }
.crud-empty h3 { font-size: 1.05rem; font-weight: 700; color: #64748b; margin-bottom: 6px; }

/* Pagination */
.crud-pagination { display: flex; list-style: none; padding: 0; margin: 0; gap: 5px; }
.crud-pagination li a {
    display: flex; align-items: center; justify-content: center;
    min-width: 32px; height: 32px; padding: 0 8px;
    border-radius: 6px; border: 1px solid var(--crud-border);
    background: white; color: var(--crud-text); text-decoration: none;
    font-size: .85rem; font-weight: 500; transition: all .2s;
}
.crud-pagination li a:hover { border-color: var(--crud-accent); color: var(--crud-accent); }
.crud-pagination li.active a { background: var(--crud-accent); border-color: var(--crud-accent); color: white; }
.crud-pagination li.disabled a { opacity: 0.5; pointer-events: none; background: var(--crud-bg); }
</style>

<div class="crud-page-header">
    <div class="crud-page-title">
        <div class="title-icon"><i class="bi bi-table"></i></div>
        <div>
            <h1><?= e($crud->title) ?></h1>
            <p>Gestión y administración de registros</p>
        </div>
    </div>
    <div class="crud-header-actions">
        <a href="<?= url('?r=' . $crud->module . '/create') ?>" class="btn-crud-new">
            <i class="bi bi-plus-lg"></i> Nuevo Registro
        </a>
    </div>
</div>

<div class="crud-toolbar">
    <form class="d-flex w-100" method="get" style="gap:12px; align-items:center; flex-wrap:wrap; margin:0">
        <input type="hidden" name="r" value="<?= e($crud->module) ?>/index">
        <div class="crud-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="Buscar en <?= e(strtolower($crud->title)) ?>..." autofocus>
        </div>
        <select name="limit" class="crud-filter-select" onchange="this.form.submit()">
            <?php foreach ([10, 20, 50, 100] as $l): ?>
                <option value="<?= $l ?>" <?= ($limit ?? 10) == $l ? 'selected' : '' ?>>Mostrar <?= $l ?> registros</option>
            <?php endforeach; ?>
        </select>
        <button type="submit" style="display:none"></button>
    </form>
</div>

<div class="crud-table-card">
    <div style="overflow-x:auto;">
        <table class="crud-table">
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <?php foreach ($crud->columns as $c => $l): ?>
                        <th><?= e($l) ?></th>
                    <?php endforeach; ?>
                    <th style="text-align:right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="<?= count($crud->columns)+2 ?>">
                        <div class="crud-empty">
                            <i class="bi bi-inbox"></i>
                            <h3>No hay registros</h3>
                            <p>Comienza creando el primer registro en <?= e(strtolower($crud->title)) ?>.</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                    <tr>
                        <td style="color:var(--crud-muted);font-size:.78rem;font-weight:700"><?= e($r['id']) ?></td>
                        <?php foreach ($crud->columns as $c => $l): ?>
                            <td>
                                <?php
                                $val = $r[$c] ?? '';
                                if ($c === 'activo' || $c === 'estado') {
                                    $is_active = ($val == 1 || strtolower((string)$val) === 'activo' || strtolower((string)$val) === 'aprobada' || strtolower((string)$val) === 'pagada');
                                    if ($is_active) {
                                        echo '<span class="status-badge status-active"><span class="status-dot" style="background:#10b981"></span> ' . e($val == 1 ? 'Activo' : $val) . '</span>';
                                    } else {
                                        echo '<span class="status-badge status-inactive"><span class="status-dot" style="background:#dc2626"></span> ' . e($val == 0 ? 'Inactivo' : $val) . '</span>';
                                    }
                                } elseif (strpos($c, 'precio') !== false || strpos($c, 'subtotal') !== false || strpos($c, 'total') !== false || strpos($c, 'monto') !== false) {
                                    echo '<span class="currency-val"><span class="currency-sym">$</span>' . number_format((float)$val, 2) . '</span>';
                                } else {
                                    echo e((string)$val);
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>
                        <td style="text-align:right">
                            <div class="crud-actions">
                                <a href="<?= url('?r='.$crud->module.'/show&id='.$r['id']) ?>" class="btn-crud-action btn-view" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?= url('?r='.$crud->module.'/edit&id='.$r['id']) ?>" class="btn-crud-action btn-edit" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="post" action="<?= url('?r='.$crud->module.'/delete') ?>" class="d-inline" onsubmit="return confirm('¿Eliminar este registro?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= e($r['id']) ?>">
                                    <button type="submit" class="btn-crud-action btn-delete" title="Eliminar">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px">
    <div style="font-size:.85rem; color:var(--crud-muted);">
        <?php if (isset($totalRows) && $totalRows > 0): ?>
            Mostrando <?= e($offset + 1) ?> a <?= e(min($offset + $limit, $totalRows)) ?> de <strong><?= e($totalRows) ?></strong> registros
        <?php endif; ?>
    </div>
    
    <?php if (isset($totalPages) && $totalPages > 1): ?>
    <ul class="crud-pagination">
        <li class="<?= ($page <= 1) ? 'disabled' : '' ?>">
            <a href="?r=<?= e($crud->module) ?>/index&p=<?= $page - 1 ?>&limit=<?= $limit ?>&q=<?= e($_GET['q'] ?? '') ?>"><i class="bi bi-chevron-left"></i></a>
        </li>
        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
        <li class="<?= ($i == $page) ? 'active' : '' ?>">
            <a href="?r=<?= e($crud->module) ?>/index&p=<?= $i ?>&limit=<?= $limit ?>&q=<?= e($_GET['q'] ?? '') ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
        <li class="<?= ($page >= $totalPages) ? 'disabled' : '' ?>">
            <a href="?r=<?= e($crud->module) ?>/index&p=<?= $page + 1 ?>&limit=<?= $limit ?>&q=<?= e($_GET['q'] ?? '') ?>"><i class="bi bi-chevron-right"></i></a>
        </li>
    </ul>
    <?php endif; ?>
</div>
