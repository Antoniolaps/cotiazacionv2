<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="row align-items-center mb-4">
  <div class="col-md-8">
    <h2 class="mb-0 fw-bold text-success"><i class="bi bi-shop"></i> Panel de Ventas</h2>
    <p class="text-muted mb-0">Bienvenido a tu estación de trabajo. ¿Qué venderemos hoy?</p>
  </div>
  <div class="col-md-4 text-end" id="quick-actions-container">
    <!-- Quick actions go here -->
  </div>
</div>

<!-- Highlighted POS Card -->
<div class="card border-0 shadow-lg bg-primary text-white mb-4 p-4 text-center rounded-4" style="background: linear-gradient(135deg, #0d6efd, #0dcaf0);">
    <h3 class="fw-bold mb-3"><i class="bi bi-cart-plus fs-1"></i></h3>
    <h4 class="mb-3">Atender a un nuevo cliente</h4>
    <a href="<?= url('?r=ventas/punto_venta') ?>" class="btn btn-light btn-lg text-primary fw-bold px-5 rounded-pill shadow-sm">Abrir Punto de Venta (POS)</a>
</div>

<div class="row g-3 mb-4" id="stats-container">
  <?php for($i=0; $i<3; $i++): ?>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm p-3 placeholder-glow">
      <span class="placeholder col-6"></span><br><span class="placeholder col-4 h3 mt-2"></span>
    </div>
  </div>
  <?php endfor; ?>
</div>

<div class="card border-0 shadow-sm p-3">
  <h5 id="recent-title" class="placeholder-glow text-secondary"><span class="placeholder col-4"></span></h5>
  <div class="table-responsive">
    <table class="table table-hover mt-2">
      <thead id="recent-head" class="table-light placeholder-glow">
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
                qa.innerHTML = data.quick_actions.map(q => {
                    if (q.url.includes('pos')) return ''; // POS ya está gigante
                    return `<a href="${q.url}" class="btn btn-${q.color} shadow-sm"><i class="bi ${q.icon}"></i> ${q.label}</a>`;
                }).join('');
            }
            if(data.stats) {
                const st = document.getElementById('stats-container');
                st.innerHTML = data.stats.map(s => `
                  <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4 text-center rounded-4 h-100">
                      <div class="text-muted small fw-bold text-uppercase mb-2">${s.label}</div>
                      <div class="h2 mb-2 fw-bold text-${s.color}">${s.value}</div>
                      <i class="bi ${s.icon} text-${s.color} fs-2 opacity-50"></i>
                    </div>
                  </div>`).join('');
            }
            
            document.getElementById('recent-title').textContent = data.recent_title;
            document.getElementById('recent-title').className = 'mb-3 text-secondary';
            document.getElementById('recent-head').innerHTML = '<tr>' + data.recent_headers.map(h => `<th>${h}</th>`).join('') + '</tr>'; 
            document.getElementById('recent-head').className = 'table-light';
            
            const tbody = document.getElementById('recent-body');
            if (!data.recent || !data.recent.length) tbody.innerHTML = `<tr><td colspan="${data.recent_headers.length}" class="text-center">No has realizado ventas recientes.</td></tr>`;
            else {
                tbody.innerHTML = data.recent.map(row => '<tr>' + row.map(cell => {
                    if (typeof cell === 'string' && ['pendiente','pagada','anulada','parcial'].includes(cell)) {
                        let bg = cell==='pagada'?'success':(cell==='anulada'?'danger':'warning');
                        return `<td><span class="badge bg-${bg}">${cell}</span></td>`;
                    }
                    return `<td>${cell||'—'}</td>`;
                }).join('') + '</tr>').join('');
            }
            tbody.classList.remove('placeholder-glow');
        });
});
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
