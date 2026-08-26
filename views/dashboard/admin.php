<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="mb-0 fw-bold text-primary"><i class="bi bi-graph-up-arrow"></i> Panel Gerencial</h2>
    <small class="text-muted">Vista de Administrador</small>
  </div>
  <div id="quick-actions-container"></div>
</div>

<div class="row g-3 mb-4" id="stats-container">
  <?php for($i=0; $i<4; $i++): ?>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm p-3 placeholder-glow">
      <div class="d-flex justify-content-between">
        <div class="w-75">
          <span class="placeholder col-8"></span>
          <br><span class="placeholder col-6 h4 mt-2"></span>
        </div>
        <span class="placeholder col-2 fs-1"></span>
      </div>
    </div>
  </div>
  <?php endfor; ?>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-3 h-100">
            <h5 class="mb-3 text-secondary">Ventas de los últimos 7 días</h5>
            <canvas id="lineChart" style="max-height: 250px;"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 h-100">
            <h5 class="mb-3 text-secondary">Top 5 Productos</h5>
            <canvas id="doughnutChart" style="max-height: 250px;"></canvas>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm p-3">
  <h5 id="recent-title" class="placeholder-glow text-secondary"><span class="placeholder col-3"></span></h5>
  <div class="table-responsive">
    <table class="table table-hover align-middle mt-2">
      <thead id="recent-head" class="placeholder-glow table-light">
        <tr><th><span class="placeholder col-6"></span></th><th><span class="placeholder col-6"></span></th><th><span class="placeholder col-6"></span></th></tr>
      </thead>
      <tbody id="recent-body" class="placeholder-glow">
        <?php for($i=0; $i<5; $i++): ?>
        <tr><td><span class="placeholder col-8"></span></td><td><span class="placeholder col-8"></span></td><td><span class="placeholder col-8"></span></td></tr>
        <?php endfor; ?>
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
                qa.innerHTML = data.quick_actions.map(q => `<a href="${q.url}" class="btn btn-outline-${q.color} btn-sm me-2 shadow-sm"><i class="bi ${q.icon}"></i> ${q.label}</a>`).join('');
            }
            if(data.stats) {
                const st = document.getElementById('stats-container');
                st.innerHTML = data.stats.map(s => `
                  <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3" style="border-left: 4px solid var(--bs-${s.color}) !important;">
                      <div class="d-flex justify-content-between">
                        <div>
                          <div class="text-muted small fw-bold text-uppercase">${s.label}</div>
                          <div class="h3 mb-0 fw-bold">${s.value}</div>
                        </div>
                        <i class="bi ${s.icon} text-${s.color} fs-1 opacity-50"></i>
                      </div>
                    </div>
                  </div>`).join('');
            }
            
            // Gráficos
            if(data.charts) {
                if(data.charts.ventas_7d) {
                    new Chart(document.getElementById('lineChart'), {
                        type: 'line',
                        data: {
                            labels: data.charts.ventas_7d.labels,
                            datasets: [{
                                label: 'Ingresos ($)',
                                data: data.charts.ventas_7d.data,
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                                tension: 0.3, fill: true
                            }]
                        },
                        options: { responsive: true, maintainAspectRatio: false }
                    });
                }
                if(data.charts.top_productos) {
                    new Chart(document.getElementById('doughnutChart'), {
                        type: 'doughnut',
                        data: {
                            labels: data.charts.top_productos.labels,
                            datasets: [{
                                data: data.charts.top_productos.data,
                                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0']
                            }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
                    });
                }
            }

            document.getElementById('recent-title').textContent = data.recent_title;
            document.getElementById('recent-title').className = 'mb-3 text-secondary';
            document.getElementById('recent-head').innerHTML = '<tr>' + data.recent_headers.map(h => `<th>${h}</th>`).join('') + '</tr>';
            document.getElementById('recent-head').className = 'table-light';
            
            const tbody = document.getElementById('recent-body');
            if (!data.recent || !data.recent.length) tbody.innerHTML = `<tr><td colspan="${data.recent_headers.length}" class="text-center">Vacío</td></tr>`;
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
