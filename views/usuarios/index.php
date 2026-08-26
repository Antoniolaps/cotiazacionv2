<?php require __DIR__ . '/../layouts/header.php'; ?>

<style>
/* ================================================================
   PANEL DE USUARIOS — DISEÑO PREMIUM
================================================================ */
:root {
    --usr-accent:   #6366f1;
    --usr-success:  #10b981;
    --usr-warning:  #f59e0b;
    --usr-danger:   #ef4444;
    --usr-info:     #06b6d4;
    --usr-border:   #e2e8f0;
    --usr-bg:       #f8fafc;
    --usr-card:     #ffffff;
    --usr-text:     #0f172a;
    --usr-muted:    #64748b;
    --usr-radius:   12px;
    --usr-shadow:   0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.06);
}

.usr-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}
.usr-page-title {
    display: flex;
    align-items: center;
    gap: 12px;
}
.usr-page-title .title-icon {
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
.usr-page-title h1 {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--usr-text);
    margin: 0;
}
.usr-page-title p {
    color: var(--usr-muted);
    font-size: .85rem;
    margin: 0;
}

/* Header actions */
.usr-header-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

.btn-usr-new {
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
.btn-usr-new:hover { color: white; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.4); }

/* Barra de filtros */
.usr-toolbar {
    background: var(--usr-card);
    border: 1px solid var(--usr-border);
    border-radius: var(--usr-radius);
    padding: 14px 20px;
    margin-bottom: 20px;
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
    box-shadow: var(--usr-shadow);
}
.usr-search-wrap {
    position: relative;
    flex: 1;
    min-width: 200px;
}
.usr-search-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--usr-muted);
}
#userSearch {
    width: 100%;
    padding: 8px 12px 8px 36px;
    border: 1.5px solid var(--usr-border);
    border-radius: 8px;
    font-size: .875rem;
    transition: border-color .2s;
    background: var(--usr-bg);
    color: var(--usr-text);
}
#userSearch:focus { outline: none; border-color: var(--usr-accent); }

.usr-filter-select {
    padding: 8px 12px;
    border: 1.5px solid var(--usr-border);
    border-radius: 8px;
    font-size: .875rem;
    background: var(--usr-bg);
    color: var(--usr-muted);
    cursor: pointer;
}
.usr-filter-select:focus { outline: none; border-color: var(--usr-accent); }

/* Stats summary */
.usr-stats-row {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.usr-stat-chip {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    background: var(--usr-card);
    border: 1px solid var(--usr-border);
    border-radius: 8px;
    font-size: .82rem;
    font-weight: 600;
    color: var(--usr-muted);
    box-shadow: var(--usr-shadow);
}
.usr-stat-chip .chip-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}
.usr-stat-chip .chip-val {
    font-weight: 800;
    font-size: .95rem;
    color: var(--usr-text);
}

/* Tabla */
.usr-table-card {
    background: var(--usr-card);
    border: 1px solid var(--usr-border);
    border-radius: var(--usr-radius);
    overflow: hidden;
    box-shadow: var(--usr-shadow);
}
.usr-table {
    width: 100%;
    border-collapse: collapse;
}
.usr-table thead {
    background: var(--usr-bg);
    border-bottom: 2px solid var(--usr-border);
}
.usr-table thead th {
    padding: 12px 16px;
    font-size: .72rem;
    font-weight: 700;
    color: var(--usr-muted);
    text-transform: uppercase;
    letter-spacing: .06em;
    white-space: nowrap;
}
.usr-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background .12s;
}
.usr-table tbody tr:last-child { border-bottom: none; }
.usr-table tbody tr:hover { background: #f8fafc; }
.usr-table tbody td {
    padding: 14px 16px;
    font-size: .875rem;
    vertical-align: middle;
}

/* Avatar + nombre */
.usr-identity {
    display: flex;
    align-items: center;
    gap: 12px;
}
.usr-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: .85rem;
    color: white;
    flex-shrink: 0;
}
.usr-name { font-weight: 700; color: var(--usr-text); }

/* Badges de rol */
.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .03em;
}
.role-admin        { background: rgba(99,102,241,.12);  color: #6366f1; }
.role-vendedor     { background: rgba(16,185,129,.12);  color: #059669; }
.role-almacen      { background: rgba(245,158,11,.12);  color: #d97706; }
.role-cliente      { background: rgba(6,182,212,.12);   color: #0891b2; }
.role-default      { background: rgba(100,116,139,.1);  color: #64748b; }

/* Estado activo / inactivo */
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
.status-inactive { background: rgba(100,116,139,.1); color: #64748b; }
.status-dot { width: 6px; height: 6px; border-radius: 50%; }

/* Username chip */
.username-chip {
    font-family: monospace;
    font-size: .8rem;
    font-weight: 600;
    background: #f1f5f9;
    color: #334155;
    padding: 3px 8px;
    border-radius: 5px;
}

/* Último login */
.last-login { font-size: .78rem; color: var(--usr-muted); }

/* Acciones */
.usr-actions { display: flex; gap: 5px; }
.btn-usr-action {
    width: 32px;
    height: 32px;
    border-radius: 7px;
    border: 1.5px solid var(--usr-border);
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    cursor: pointer;
    transition: all .15s;
    text-decoration: none;
    color: var(--usr-muted);
}
.btn-usr-action:hover { color: var(--usr-text); border-color: #cbd5e1; background: #f8fafc; }
.btn-usr-action.btn-edit:hover  { border-color: #6366f1; color: #6366f1; background: rgba(99,102,241,.06); }
.btn-usr-action.btn-delete:hover { border-color: var(--usr-danger); color: var(--usr-danger); background: rgba(239,68,68,.06); }

/* Estado vacío */
.usr-empty {
    text-align: center;
    padding: 60px 20px;
    color: var(--usr-muted);
}
.usr-empty i { font-size: 2.5rem; margin-bottom: 12px; display: block; color: #cbd5e1; }
.usr-empty h3 { font-size: 1.05rem; font-weight: 700; color: #64748b; margin-bottom: 6px; }

/* Responsive */
@media (max-width: 768px) {
    .col-phone, .col-login { display: none; }
}
</style>

<?php
/* ── calcular estadísticas rápidas ── */
$total     = count($rows);
$activos   = count(array_filter($rows, fn($r) => $r['estado']));
$inactivos = $total - $activos;

$rolColors = [
    'admin'    => '#6366f1',
    'vendedor' => '#10b981',
    'almacen'  => '#f59e0b',
    'cliente'  => '#06b6d4',
];
?>

<!-- Header -->
<div class="usr-page-header">
    <div class="usr-page-title">
        <div class="title-icon"><i class="bi bi-people-fill"></i></div>
        <div>
            <h1>Gestión de Usuarios</h1>
            <p>Administra accesos, roles y permisos del sistema</p>
        </div>
    </div>
    <div class="usr-header-actions">
        <a href="<?= url('?r=roles/index') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-shield-check"></i> Roles
        </a>
        <a href="<?= url('?r=usuarios/create') ?>" class="btn-usr-new">
            <i class="bi bi-person-plus-fill"></i> Nuevo Usuario
        </a>
    </div>
</div>

<!-- Stats chips -->
<div class="usr-stats-row">
    <div class="usr-stat-chip">
        <i class="bi bi-people" style="color:#6366f1"></i>
        Total: <span class="chip-val"><?= $total ?></span> usuarios
    </div>
    <div class="usr-stat-chip">
        <span class="chip-dot" style="background:#10b981"></span>
        Activos: <span class="chip-val" style="color:#059669"><?= $activos ?></span>
    </div>
    <?php if ($inactivos > 0): ?>
    <div class="usr-stat-chip">
        <span class="chip-dot" style="background:#94a3b8"></span>
        Inactivos: <span class="chip-val" style="color:#64748b"><?= $inactivos ?></span>
    </div>
    <?php endif; ?>
</div>

<!-- Toolbar de búsqueda / filtro -->
<div class="usr-toolbar">
    <div class="usr-search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" id="userSearch" placeholder="Buscar por nombre o usuario..." autofocus>
    </div>
    <select class="usr-filter-select" id="roleFilter">
        <option value="">Todos los roles</option>
        <?php
        $roles = array_unique(array_column($rows, 'rol'));
        sort($roles);
        foreach ($roles as $rol):
            if (!$rol) continue;
        ?>
            <option value="<?= e($rol) ?>"><?= ucfirst(e($rol)) ?></option>
        <?php endforeach; ?>
    </select>
    <select class="usr-filter-select" id="statusFilter">
        <option value="">Todos los estados</option>
        <option value="1">Activos</option>
        <option value="0">Inactivos</option>
    </select>
</div>

<!-- Tabla -->
<div class="usr-table-card">
    <table class="usr-table" id="usersTable">
        <thead>
            <tr>
                <th style="width:40px">#</th>
                <th>Usuario</th>
                <th>Username</th>
                <th>Rol</th>
                <th class="col-phone">Teléfono</th>
                <th class="col-login">Último acceso</th>
                <th>Estado</th>
                <th style="width:90px; text-align:right">Acciones</th>
            </tr>
        </thead>
        <tbody id="usersBody">
        <?php if (empty($rows)): ?>
            <tr>
                <td colspan="8">
                    <div class="usr-empty">
                        <i class="bi bi-person-x"></i>
                        <h3>No hay usuarios registrados</h3>
                        <p>Comienza creando el primer usuario del sistema.</p>
                    </div>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($rows as $r):
                $rolNombre = strtolower($r['rol'] ?? '');
                $rolClass  = in_array($rolNombre, ['admin','vendedor','almacen','cliente'])
                             ? 'role-' . $rolNombre : 'role-default';
                $avatarColor = $rolColors[$rolNombre] ?? '#6366f1';
                $initials = strtoupper(mb_substr($r['nombre'], 0, 1) . (strpos($r['nombre'], ' ') !== false ? mb_substr(explode(' ', $r['nombre'])[1], 0, 1) : ''));
                $rolIcons = ['admin'=>'bi-shield-fill','vendedor'=>'bi-cart-fill','almacen'=>'bi-boxes','cliente'=>'bi-person-fill'];
                $rolIcon  = $rolIcons[$rolNombre] ?? 'bi-person';
            ?>
            <tr class="usr-row"
                data-nombre="<?= strtolower(e($r['nombre'])) ?>"
                data-username="<?= strtolower(e($r['username'])) ?>"
                data-rol="<?= e($r['rol'] ?? '') ?>"
                data-estado="<?= $r['estado'] ? '1' : '0' ?>">

                <td style="color:#94a3b8;font-size:.78rem;font-weight:700"><?= $r['id'] ?></td>

                <td>
                    <div class="usr-identity">
                        <div class="usr-avatar" style="background: <?= $avatarColor ?>22; color: <?= $avatarColor ?>; border: 2px solid <?= $avatarColor ?>33">
                            <?= $initials ?: '?' ?>
                        </div>
                        <div>
                            <div class="usr-name"><?= e($r['nombre']) ?></div>
                        </div>
                    </div>
                </td>

                <td><span class="username-chip"><?= e($r['username']) ?></span></td>

                <td>
                    <span class="role-badge <?= $rolClass ?>">
                        <i class="bi <?= $rolIcon ?>"></i>
                        <?= ucfirst(e($r['rol'] ?? '—')) ?>
                    </span>
                </td>

                <td class="col-phone last-login"><?= e($r['telefono'] ?? '—') ?></td>

                <td class="col-login">
                    <span class="last-login">
                        <?php if (!empty($r['ultimo_login'])): ?>
                            <i class="bi bi-clock" style="margin-right:3px"></i>
                            <?= date('d/m/Y H:i', strtotime($r['ultimo_login'])) ?>
                        <?php else: ?>
                            <span style="color:#cbd5e1">Nunca</span>
                        <?php endif; ?>
                    </span>
                </td>

                <td>
                    <?php if ($r['estado']): ?>
                        <span class="status-badge status-active">
                            <span class="status-dot" style="background:#10b981"></span> Activo
                        </span>
                    <?php else: ?>
                        <span class="status-badge status-inactive">
                            <span class="status-dot" style="background:#94a3b8"></span> Inactivo
                        </span>
                    <?php endif; ?>
                </td>

                <td style="text-align:right">
                    <div class="usr-actions" style="justify-content:flex-end">
                        <a href="<?= url('?r=usuarios/edit&id='.$r['id']) ?>"
                           class="btn-usr-action btn-edit" title="Editar usuario">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <?php if ($r['id'] !== (Auth::user()['id'] ?? 0)): ?>
                        <form method="post" action="<?= url('?r=usuarios/delete') ?>"
                              class="d-inline"
                              onsubmit="return confirm('¿Eliminar al usuario «<?= e($r['nombre']) ?>»?\nEsta acción no se puede deshacer.')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= e($r['id']) ?>">
                            <button type="submit" class="btn-usr-action btn-delete" title="Eliminar usuario">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                        <?php else: ?>
                            <span class="btn-usr-action" title="Eres tú" style="opacity:.3;cursor:default">
                                <i class="bi bi-person-check"></i>
                            </span>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div style="margin-top:12px; font-size:.78rem; color:#94a3b8; text-align:right" id="filter-info">
    Mostrando <?= count($rows) ?> de <?= count($rows) ?> usuarios
</div>

<script>
/* ── Filtrado en tiempo real ── */
const searchInput  = document.getElementById('userSearch');
const roleFilter   = document.getElementById('roleFilter');
const statusFilter = document.getElementById('statusFilter');
const rows         = document.querySelectorAll('.usr-row');
const filterInfo   = document.getElementById('filter-info');

function applyFilters() {
    const q      = searchInput.value.toLowerCase();
    const rol    = roleFilter.value.toLowerCase();
    const estado = statusFilter.value;

    let visible = 0;
    rows.forEach(row => {
        const name    = row.dataset.nombre;
        const user    = row.dataset.username;
        const rowRol  = row.dataset.rol.toLowerCase();
        const rowEst  = row.dataset.estado;

        const matchQ   = !q || name.includes(q) || user.includes(q);
        const matchRol = !rol || rowRol === rol;
        const matchEst = !estado || rowEst === estado;

        const show = matchQ && matchRol && matchEst;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    filterInfo.textContent = `Mostrando ${visible} de <?= count($rows) ?> usuarios`;
}

searchInput.addEventListener('input', applyFilters);
roleFilter.addEventListener('change', applyFilters);
statusFilter.addEventListener('change', applyFilters);
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
