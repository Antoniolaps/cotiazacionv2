<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="mb-0 fw-bold text-danger"><i class="bi bi-box-seam"></i> Centro de Almacén</h2>
    <small class="text-muted">Control de Inventario y Abastecimiento</small>
  </div>
  <div id="quick-actions-container"></div>
</div>

<div class="row g-3 mb-4" id="stats-container">
  <?php for($i=0; $i<4; $i++): ?>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm p-3 placeholder-glow">
      <span class="placeholder col-6"></span><br><span class="placeholder col-4 h3 mt-2"></span>
    </div>
  </div>
  <?php endfor; ?>
</div>

<div class="card border-0 shadow-sm p-4 border-top border-danger border-4">
  <div class="d-flex align-items-center mb-3">
    <i class="bi bi-exclamation-triangle-fill text-danger fs-3 me-2"></i>
    <h5 id="recent-title" class="mb-0 text-danger fw-bold placeholder-glow"><span class="placeholder col-4"></span></h5>
  </div>
  <p class="text-muted small">Los siguientes productos han alcanzado o están por debajo de su stock mínimo. Se recomienda generar órdenes de compra pronto.</p>
  
  <div class="table-responsive">
    <table class="table table-hover mt-2">
      <thead id="recent-head" class="table-danger placeholder-glow">
        <tr><th><span class="placeholder col-12"></span></th></tr>
      </thead>
      <tbody id="recent-body" class="placeholder-glow">
        <?php for($i=0; $i<3; $i++): ?><tr><td><span class="placeholder col-12"></span></td></tr><?php endfor; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('?r=dashboard_api/stats')
        .then(res => res.json())
        .then(data => {
            if(data.quick_actions) {
                const qa = document.getElementById('quick-actions-container');
                qa.innerHTML = data.quick_actions.map(q => `<a href="${q.url}" class="btn btn-${q.color} shadow-sm me-2"><i class="bi ${q.icon}"></i> ${q.label}</a>`).join('');
            }
            if(data.stats) {
                const st = document.getElementById('stats-container');
                st.innerHTML = data.stats.map(s => {
                  let bgClass = s.color === 'danger' ? 'bg-danger text-white' : 'bg-white text-dark';
                  let iconClass = s.color === 'danger' ? 'text-white' : `text-${s.color}`;
                  let textClass = s.color === 'danger' ? 'text-white-50' : 'text-muted';
                  
                  return `
                  <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3 h-100 ${bgClass} rounded-3">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <div class="${textClass} small fw-bold text-uppercase">${s.label}</div>
                          <div class="h2 mb-0 fw-bold">${s.value}</div>
                        </div>
                        <i class="bi ${s.icon} ${iconClass} fs-1 opacity-75"></i>
                      </div>
                    </div>
                  </div>`;
                }).join('');
            }
            
            document.getElementById('recent-title').textContent = data.recent_title;
            document.getElementById('recent-title').classList.remove('placeholder-glow');
            document.getElementById('recent-head').innerHTML = '<tr>' + data.recent_headers.map(h => `<th>${h}</th>`).join('') + '</tr>';
            document.getElementById('recent-head').classList.remove('placeholder-glow');
            
            const tbody = document.getElementById('recent-body');
            if (!data.recent || !data.recent.length) tbody.innerHTML = `<tr><td colspan="${data.recent_headers.length}" class="text-center py-4"><i class="bi bi-check-circle text-success fs-1"></i><br>¡Todo el inventario está en niveles óptimos!</td></tr>`;
            else {
                tbody.innerHTML = data.recent.map(row => '<tr>' + row.map((cell, idx) => {
                    // Si es la columna de "Stock Actual" (índice 2) y es bajo stock, pintarlo rojo
                    if (idx === 2) {
                        return `<td><span class="badge bg-danger fs-6">${cell}</span></td>`;
                    }
                    return `<td><span class="fw-semibold">${cell||'—'}</span></td>`;
                }).join('') + '</tr>').join('');
            }
            tbody.classList.remove('placeholder-glow');
        });
});
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
