<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0"><i class="bi bi-journal-text text-primary me-2"></i>Bitácora de Actividades (Logs)</h3>
  <span class="text-muted small">Registro detallado de acciones y cambios en el sistema</span>
</div>

<!-- Filtros de búsqueda -->
<div class="card p-3 mb-4 shadow-sm">
  <form method="get" action="<?= url('') ?>" class="row g-2 align-items-end">
    <input type="hidden" name="r" value="logs/index">
    
    <div class="col-md-2">
      <label class="form-label small fw-bold">Desde</label>
      <input type="date" class="form-control form-control-sm" name="desde" value="<?= e($desde) ?>">
    </div>
    
    <div class="col-md-2">
      <label class="form-label small fw-bold">Hasta</label>
      <input type="date" class="form-control form-control-sm" name="hasta" value="<?= e($hasta) ?>">
    </div>
    
    <div class="col-md-2">
      <label class="form-label small fw-bold">Usuario</label>
      <select class="form-select form-select-sm" name="usuario_id">
        <option value="">— Todos —</option>
        <?php foreach ($usuarios as $u): ?>
          <option value="<?= $u['id'] ?>" <?= $qUsuario == $u['id'] ? 'selected' : '' ?>><?= e($u['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    
    <div class="col-md-2">
      <label class="form-label small fw-bold">Acción</label>
      <select class="form-select form-select-sm" name="accion">
        <option value="">— Todas —</option>
        <?php foreach (['INSERT','UPDATE','DELETE','LOGIN','LOGOUT','CREATE','ALTER','DROP'] as $act): ?>
          <option value="<?= $act ?>" <?= $qAccion === $act ? 'selected' : '' ?>><?= $act ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    
    <div class="col-md-2">
      <label class="form-label small fw-bold">Tabla</label>
      <select class="form-select form-select-sm" name="tabla">
        <option value="">— Todas —</option>
        <?php foreach ($tablas as $t): ?>
          <option value="<?= e($t['tabla_afectada']) ?>" <?= $qTabla === $t['tabla_afectada'] ? 'selected' : '' ?>><?= e($t['tabla_afectada']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    
    <div class="col-md-2 d-flex gap-1">
      <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i> Filtrar</button>
      <a href="<?= url('?r=logs/index') ?>" class="btn btn-sm btn-outline-secondary" title="Limpiar"><i class="bi bi-x-circle"></i></a>
    </div>
  </form>
</div>

<!-- Tabla de logs -->
<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
      <thead class="table-light">
        <tr>
          <th>Fecha / Hora</th>
          <th>Usuario</th>
          <th>Rol</th>
          <th>Acción</th>
          <th>Tabla</th>
          <th>ID Reg.</th>
          <th>IP</th>
          <th class="text-end">Detalles</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $log): 
          $badgeMap = [
            'INSERT' => 'bg-success',
            'UPDATE' => 'bg-primary',
            'DELETE' => 'bg-danger',
            'LOGIN'  => 'bg-info text-dark',
            'LOGOUT' => 'bg-secondary',
          ];
          $badgeClass = $badgeMap[$log['accion']] ?? 'bg-dark';
        ?>
        <tr>
          <td><small class="text-muted"><?= e(date('d/m/Y H:i:s', strtotime($log['fecha']))) ?></small></td>
          <td><strong><?= e($log['usuario'] ?? 'Sistema / Anónimo') ?></strong></td>
          <td><span class="badge bg-light text-dark border"><?= e($log['rol'] ?? '—') ?></span></td>
          <td><span class="badge <?= $badgeClass ?>"><?= e($log['accion']) ?></span></td>
          <td><code class="text-primary"><?= e($log['tabla_afectada']) ?></code></td>
          <td><?= e($log['registro_id'] ?? '—') ?></td>
          <td><small class="text-muted"><?= e($log['ip_address'] ?? '—') ?></small></td>
          <td class="text-end">
            <?php if ($log['cambios_anteriores'] || $log['cambios_nuevos']): ?>
              <button class="btn btn-sm btn-outline-info" 
                      onclick="viewDetails(<?= e(json_encode($log['cambios_anteriores'])) ?>, <?= e(json_encode($log['cambios_nuevos'])) ?>, '<?= e($log['accion']) ?> - <?= e($log['tabla_afectada']) ?> #<?= $log['registro_id'] ?>')">
                <i class="bi bi-eye"></i> Cambios
              </button>
            <?php else: ?>
              <span class="text-muted small">—</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?>
        <tr>
          <td colspan="8" class="text-center py-4 text-muted">No se encontraron registros de actividad con los filtros seleccionados.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Paginación -->
<?php if ($totalPages > 1): ?>
<nav class="mt-3">
  <ul class="pagination pagination-sm justify-content-center">
    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= url("?r=logs/index&p=".($page-1)."&desde=$desde&hasta=$hasta&usuario_id=$qUsuario&accion=$qAccion&tabla=$qTabla") ?>">Anterior</a>
    </li>
    <?php for ($i=1; $i<=$totalPages; $i++): ?>
      <li class="page-item <?= $page == $i ? 'active' : '' ?>">
        <a class="page-link" href="<?= url("?r=logs/index&p=$i&desde=$desde&hasta=$hasta&usuario_id=$qUsuario&accion=$qAccion&tabla=$qTabla") ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>
    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
      <a class="page-link" href="<?= url("?r=logs/index&p=".($page+1)."&desde=$desde&hasta=$hasta&usuario_id=$qUsuario&accion=$qAccion&tabla=$qTabla") ?>">Siguiente</a>
    </li>
  </ul>
</nav>
<?php endif; ?>

<!-- Modal de detalles de cambios -->
<div class="modal fade" id="detailsModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Detalle de Cambios</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <h6 class="text-muted"><i class="bi bi-arrow-left-circle me-1"></i> Estado Anterior</h6>
            <pre class="bg-light p-3 border rounded text-danger" id="jsonAnterior" style="max-height:300px;overflow:auto;font-size:.8rem"></pre>
          </div>
          <div class="col-md-6 mb-3">
            <h6 class="text-muted"><i class="bi bi-arrow-right-circle me-1"></i> Estado Nuevo</h6>
            <pre class="bg-light p-3 border rounded text-success" id="jsonNuevo" style="max-height:300px;overflow:auto;font-size:.8rem"></pre>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function viewDetails(ant, nue, title) {
  document.getElementById('modalTitle').textContent = title;
  try {
    document.getElementById('jsonAnterior').textContent = ant ? JSON.stringify(JSON.parse(ant), null, 2) : '(ninguno)';
  } catch(e) { document.getElementById('jsonAnterior').textContent = ant || '(ninguno)'; }

  try {
    document.getElementById('jsonNuevo').textContent = nue ? JSON.stringify(JSON.parse(nue), null, 2) : '(ninguno)';
  } catch(e) { document.getElementById('jsonNuevo').textContent = nue || '(ninguno)'; }

  var modal = new bootstrap.Modal(document.getElementById('detailsModal'));
  modal.show();
}
</script>
