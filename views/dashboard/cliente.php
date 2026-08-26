<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="mb-0 fw-bold"><i class="bi bi-person-check"></i> Mi Panel</h2>
    <p class="text-muted mb-0">Rastrea tus compras y pedidos fácilmente.</p>
  </div>
  <div id="quick-actions-container"></div>
</div>

<div class="row g-3 mb-4" id="stats-container">
  <?php for($i=0; $i<2; $i++): ?>
  <div class="col-md-6">
    <div class="card border-0 shadow-sm p-4 placeholder-glow rounded-4">
      <span class="placeholder col-4"></span><br><span class="placeholder col-2 h3 mt-2"></span>
    </div>
  </div>
  <?php endfor; ?>
</div>

<div class="card border-0 shadow-sm p-4 rounded-4">
  <h5 id="recent-title" class="placeholder-glow text-primary fw-bold mb-3"><span class="placeholder col-4"></span></h5>
  <div class="table-responsive">
    <table class="table table-borderless table-hover align-middle">
      <thead id="recent-head" class="border-bottom placeholder-glow">
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
                qa.innerHTML = data.quick_actions.map(q => `<a href="${q.url}" class="btn btn-${q.color} shadow-sm rounded-pill px-4"><i class="bi ${q.icon}"></i> ${q.label}</a>`).join('');
            }
            if(data.stats) {
                const st = document.getElementById('stats-container');
                st.innerHTML = data.stats.map(s => `
                  <div class="col-md-6">
                    <div class="card border-0 shadow-sm p-4 h-100 rounded-4" style="background-color: #f8f9fa;">
                      <div class="d-flex align-items-center">
                        <div class="bg-${s.color} text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 60px; height: 60px;">
                          <i class="bi ${s.icon} fs-3"></i>
                        </div>
                        <div>
                          <div class="h2 mb-0 fw-bold text-dark">${s.value}</div>
                          <div class="text-muted small fw-bold text-uppercase">${s.label}</div>
                        </div>
                      </div>
                    </div>
                  </div>`).join('');
            }
            
            document.getElementById('recent-title').textContent = data.recent_title;
            document.getElementById('recent-title').classList.remove('placeholder-glow');
            document.getElementById('recent-head').innerHTML = '<tr>' + data.recent_headers.map(h => `<th class="text-muted text-uppercase small">${h}</th>`).join('') + '</tr>';
            document.getElementById('recent-head').classList.remove('placeholder-glow');
            
            const tbody = document.getElementById('recent-body');
            if (!data.recent || !data.recent.length) tbody.innerHTML = `<tr><td colspan="${data.recent_headers.length}" class="text-center py-5 text-muted"><i class="bi bi-cart-x fs-1"></i><br>Aún no tienes pedidos registrados.</td></tr>`;
            else {
                tbody.innerHTML = data.recent.map(row => '<tr class="border-bottom">' + row.map(cell => {
                    if (typeof cell === 'string' && ['pendiente','pagada','anulada','parcial'].includes(cell)) {
                        let bg = cell==='pagada'?'success':(cell==='anulada'?'danger':'warning');
                        return `<td><span class="badge bg-${bg} rounded-pill px-3">${cell}</span></td>`;
                    }
                    return `<td><span class="fw-medium">${cell||'—'}</span></td>`;
                }).join('') + '</tr>').join('');
            }
            tbody.classList.remove('placeholder-glow');
        });
});
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
