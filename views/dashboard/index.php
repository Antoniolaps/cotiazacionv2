<?php require __DIR__ . '/../layouts/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="mb-0">Dashboard</h2>
  <div id="quick-actions-container"></div>
</div>

<!-- Skeleton para stats -->
<div class="row g-3 mb-4" id="stats-container">
  <?php for($i=0; $i<4; $i++): ?>
  <div class="col-md-3">
    <div class="card p-3 placeholder-glow">
      <div class="d-flex justify-content-between">
        <div class="w-75">
          <span class="placeholder col-8"></span>
          <br>
          <span class="placeholder col-6 h4 mt-2"></span>
        </div>
        <span class="placeholder col-2 fs-1"></span>
      </div>
    </div>
  </div>
  <?php endfor; ?>
</div>

<!-- Skeleton para tabla -->
<div class="card p-3">
  <h5 id="recent-title" class="placeholder-glow"><span class="placeholder col-3"></span></h5>
  <table class="table table-sm mt-3">
    <thead id="recent-head" class="placeholder-glow">
      <tr><th><span class="placeholder col-6"></span></th><th><span class="placeholder col-6"></span></th><th><span class="placeholder col-6"></span></th></tr>
    </thead>
    <tbody id="recent-body" class="placeholder-glow">
      <?php for($i=0; $i<5; $i++): ?>
      <tr>
        <td><span class="placeholder col-8"></span></td>
        <td><span class="placeholder col-8"></span></td>
        <td><span class="placeholder col-8"></span></td>
      </tr>
      <?php endfor; ?>
    </tbody>
  </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('?r=dashboard_api/stats')
        .then(res => res.json())
        .then(data => {
            // Render Quick Actions
            if(data.quick_actions) {
                const qaContainer = document.getElementById('quick-actions-container');
                qaContainer.innerHTML = '';
                data.quick_actions.forEach(qa => {
                    qaContainer.innerHTML += `<a href="${qa.url}" class="btn btn-outline-${qa.color} btn-sm me-2"><i class="bi ${qa.icon}"></i> ${qa.label}</a>`;
                });
            }

            // Render Stats
            const statsContainer = document.getElementById('stats-container');
            statsContainer.innerHTML = '';
            if(data.stats) {
                data.stats.forEach(s => {
                    statsContainer.innerHTML += `
                    <div class="col-md-3">
                      <div class="card p-3">
                        <div class="d-flex justify-content-between">
                          <div>
                            <div class="text-muted small">${s.label}</div>
                            <div class="h4 mb-0">${s.value}</div>
                          </div>
                          <i class="bi ${s.icon} text-${s.color} fs-1"></i>
                        </div>
                      </div>
                    </div>`;
                });
            }

            // Render Recent Title
            const recentTitle = document.getElementById('recent-title');
            recentTitle.className = ''; // remove placeholder-glow
            recentTitle.textContent = data.recent_title;

            // Render Table Head
            const thead = document.getElementById('recent-head');
            thead.className = '';
            let headHtml = '<tr>';
            if(data.recent_headers) {
                data.recent_headers.forEach(h => {
                    headHtml += `<th>${h}</th>`;
                });
            }
            headHtml += '</tr>';
            thead.innerHTML = headHtml;

            // Render Table Body
            const tbody = document.getElementById('recent-body');
            tbody.innerHTML = '';
            if (!data.recent || data.recent.length === 0) {
                tbody.innerHTML = `<tr><td colspan="${data.recent_headers ? data.recent_headers.length : 1}" class="text-center text-muted">No hay registros recientes</td></tr>`;
            } else {
                data.recent.forEach(row => {
                    let tr = '<tr>';
                    row.forEach((cell, idx) => {
                        if (typeof cell === 'string' && (cell === 'pendiente' || cell === 'pagada' || cell === 'anulada' || cell === 'parcial' || cell === 'aprobada' || cell === 'recibida')) {
                            let bg = (cell === 'pagada' || cell === 'recibida') ? 'success' : ((cell === 'pendiente' || cell === 'parcial') ? 'warning' : (cell === 'anulada' ? 'danger' : 'primary'));
                            tr += `<td><span class="badge bg-${bg}">${cell}</span></td>`;
                        } else {
                            tr += `<td>${cell !== null ? cell : '—'}</td>`;
                        }
                    });
                    tr += '</tr>';
                    tbody.innerHTML += tr;
                });
            }
            tbody.classList.remove('placeholder-glow');
        })
        .catch(err => {
            console.error('Error cargando dashboard', err);
            document.getElementById('stats-container').innerHTML = '<div class="alert alert-danger w-100">Error al cargar datos del dashboard.</div>';
        });
});
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
