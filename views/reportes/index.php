<?php require __DIR__ . '/../layouts/header.php'; ?>
<style>
:root {
  --rep-bg: #f8fafc;
  --rep-card: #fff;
  --rep-accent: #6366f1;
  --rep-accent2: #10b981;
  --rep-warn: #f59e0b;
  --rep-danger: #ef4444;
  --rep-border: #e2e8f0;
}
.rep-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 1.5rem;
  margin-top: 1.5rem;
}
.rep-card {
  background: var(--rep-card);
  border: 1.5px solid var(--rep-border);
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(0,0,0,.06);
  transition: transform .2s, box-shadow .2s;
}
.rep-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(99,102,241,.12); }
.rep-card-header {
  padding: 1.1rem 1.4rem 0.8rem;
  border-bottom: 1px solid var(--rep-border);
  display: flex;
  align-items: center;
  gap: 12px;
}
.rep-icon {
  width: 42px;
  height: 42px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.3rem;
  flex-shrink: 0;
}
.rep-icon.purple  { background: #ede9fe; color: var(--rep-accent); }
.rep-icon.green   { background: #d1fae5; color: var(--rep-accent2); }
.rep-icon.orange  { background: #fef3c7; color: var(--rep-warn); }
.rep-icon.blue    { background: #dbeafe; color: #3b82f6; }
.rep-title { font-size: 1rem; font-weight: 700; color: #1e293b; }
.rep-desc  { font-size: .8rem; color: #64748b; }
.rep-body  { padding: 1.2rem 1.4rem; }
.rep-form .form-label { font-size: .78rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .04em; margin-bottom: 4px; }
.rep-form .form-control, .rep-form .form-select { font-size: .875rem; border-radius: 8px; border: 1.5px solid var(--rep-border); padding: 6px 10px; }
.rep-form .form-control:focus, .rep-form .form-select:focus { border-color: var(--rep-accent); box-shadow: 0 0 0 3px rgba(99,102,241,.12); outline: none; }
.rep-actions { display: flex; gap: 8px; margin-top: 1rem; flex-wrap: wrap; }
.btn-csv {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: .85rem;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  border: none;
  transition: all .18s;
}
.btn-csv.primary { background: var(--rep-accent); color: #fff; }
.btn-csv.primary:hover { background: #4f46e5; color: #fff; }
.btn-csv.sheets  { background: #fff; border: 1.5px solid #34a853; color: #34a853; }
.btn-csv.sheets:hover  { background: #f0fdf4; }
.rep-info { font-size: .78rem; color: #94a3b8; margin-top: .6rem; display: flex; align-items: center; gap: 5px; }
</style>

<div class="d-flex justify-content-between align-items-center mb-1">
  <h3 class="mb-0"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Reportes</h3>
  <span class="text-muted small">Exporta tus datos para análisis en Google Sheets o Excel</span>
</div>

<div class="rep-grid">

  <!-- ── Ventas por período ── -->
  <div class="rep-card">
    <div class="rep-card-header">
      <div class="rep-icon purple"><i class="bi bi-receipt"></i></div>
      <div>
        <div class="rep-title">Ventas por período</div>
        <div class="rep-desc">Todas las ventas en un rango de fechas, por vendedor</div>
      </div>
    </div>
    <div class="rep-body">
      <form class="rep-form" id="form-ventas" target="_blank">
        <input type="hidden" name="r" value="reportes/ventas_periodo">
        <div class="row g-2 mb-2">
          <div class="col-6">
            <label class="form-label">Desde</label>
            <input type="date" class="form-control" name="desde" value="<?= date('Y-m-01') ?>">
          </div>
          <div class="col-6">
            <label class="form-label">Hasta</label>
            <input type="date" class="form-control" name="hasta" value="<?= date('Y-m-d') ?>">
          </div>
        </div>
        <div class="mb-2">
          <label class="form-label">Vendedor (opcional)</label>
          <select class="form-select" name="vendedor_id">
            <option value="">— Todos —</option>
            <?php foreach ($vendedores as $v): ?>
            <option value="<?= e($v['id']) ?>"><?= e($v['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="rep-actions">
          <button type="submit" formaction="<?= url('?r=reportes/ventas_periodo') ?>" class="btn-csv primary">
            <i class="bi bi-filetype-csv"></i> Descargar CSV
          </button>
          <button type="button" class="btn-csv sheets" onclick="openSheets('form-ventas','reportes/ventas_periodo')">
            <i class="bi bi-table"></i> Abrir en Sheets
          </button>
        </div>
        <p class="rep-info"><i class="bi bi-info-circle"></i> El archivo se abre directamente en Google Sheets</p>
      </form>
    </div>
  </div>

  <!-- ── Inventario actual ── -->
  <div class="rep-card">
    <div class="rep-card-header">
      <div class="rep-icon green"><i class="bi bi-boxes"></i></div>
      <div>
        <div class="rep-title">Inventario actual</div>
        <div class="rep-desc">Stock de todos los productos con costos y precios de venta</div>
      </div>
    </div>
    <div class="rep-body">
      <p class="text-muted" style="font-size:.85rem">Genera un snapshot del inventario en este momento. No requiere filtros.</p>
      <div class="rep-actions mt-3">
        <a href="<?= url('?r=reportes/inventario_actual') ?>" target="_blank" class="btn-csv primary">
          <i class="bi bi-filetype-csv"></i> Descargar CSV
        </a>
        <button type="button" class="btn-csv sheets" onclick="openSheetsUrl('<?= url('?r=reportes/inventario_actual') ?>')">
          <i class="bi bi-table"></i> Abrir en Sheets
        </button>
      </div>
      <p class="rep-info"><i class="bi bi-info-circle"></i> Incluye stock actual, mínimo, costo y precio de venta</p>
    </div>
  </div>

  <!-- ── Compras por período ── -->
  <div class="rep-card">
    <div class="rep-card-header">
      <div class="rep-icon orange"><i class="bi bi-cart-check"></i></div>
      <div>
        <div class="rep-title">Compras por período</div>
        <div class="rep-desc">Órdenes de compra recibidas en un rango de fechas</div>
      </div>
    </div>
    <div class="rep-body">
      <form class="rep-form" id="form-compras" target="_blank">
        <input type="hidden" name="r" value="reportes/compras_periodo">
        <div class="row g-2 mb-2">
          <div class="col-6">
            <label class="form-label">Desde</label>
            <input type="date" class="form-control" name="desde" value="<?= date('Y-m-01') ?>">
          </div>
          <div class="col-6">
            <label class="form-label">Hasta</label>
            <input type="date" class="form-control" name="hasta" value="<?= date('Y-m-d') ?>">
          </div>
        </div>
        <div class="rep-actions">
          <button type="submit" formaction="<?= url('?r=reportes/compras_periodo') ?>" class="btn-csv primary">
            <i class="bi bi-filetype-csv"></i> Descargar CSV
          </button>
          <button type="button" class="btn-csv sheets" onclick="openSheets('form-compras','reportes/compras_periodo')">
            <i class="bi bi-table"></i> Abrir en Sheets
          </button>
        </div>
        <p class="rep-info"><i class="bi bi-info-circle"></i> Incluye Nº Factura del proveedor</p>
      </form>
    </div>
  </div>

  <!-- ── Top productos ── -->
  <div class="rep-card">
    <div class="rep-card-header">
      <div class="rep-icon blue"><i class="bi bi-trophy"></i></div>
      <div>
        <div class="rep-title">Top productos vendidos</div>
        <div class="rep-desc">Ranking de los 100 productos más vendidos por período</div>
      </div>
    </div>
    <div class="rep-body">
      <form class="rep-form" id="form-top" target="_blank">
        <input type="hidden" name="r" value="reportes/top_productos">
        <div class="row g-2 mb-2">
          <div class="col-6">
            <label class="form-label">Desde</label>
            <input type="date" class="form-control" name="desde" value="<?= date('Y-m-01') ?>">
          </div>
          <div class="col-6">
            <label class="form-label">Hasta</label>
            <input type="date" class="form-control" name="hasta" value="<?= date('Y-m-d') ?>">
          </div>
        </div>
        <div class="rep-actions">
          <button type="submit" formaction="<?= url('?r=reportes/top_productos') ?>" class="btn-csv primary">
            <i class="bi bi-filetype-csv"></i> Descargar CSV
          </button>
          <button type="button" class="btn-csv sheets" onclick="openSheets('form-top','reportes/top_productos')">
            <i class="bi bi-table"></i> Abrir en Sheets
          </button>
        </div>
        <p class="rep-info"><i class="bi bi-info-circle"></i> Ordenado por cantidad vendida, incluye monto total</p>
      </form>
    </div>
  </div>

  <!-- ── Cotizaciones por período ── -->
  <div class="rep-card">
    <div class="rep-card-header">
      <div class="rep-icon purple"><i class="bi bi-file-earmark-text"></i></div>
      <div>
        <div class="rep-title">Cotizaciones por período</div>
        <div class="rep-desc">Histórico de cotizaciones emitidas a clientes</div>
      </div>
    </div>
    <div class="rep-body">
      <form class="rep-form" id="form-cotizaciones" target="_blank">
        <input type="hidden" name="r" value="reportes/cotizaciones_periodo">
        <div class="row g-2 mb-2">
          <div class="col-6">
            <label class="form-label">Desde</label>
            <input type="date" class="form-control" name="desde" value="<?= date('Y-m-01') ?>">
          </div>
          <div class="col-6">
            <label class="form-label">Hasta</label>
            <input type="date" class="form-control" name="hasta" value="<?= date('Y-m-d') ?>">
          </div>
        </div>
        <div class="rep-actions">
          <button type="submit" formaction="<?= url('?r=reportes/cotizaciones_periodo') ?>" class="btn-csv primary">
            <i class="bi bi-filetype-csv"></i> Descargar CSV
          </button>
          <button type="button" class="btn-csv sheets" onclick="openSheets('form-cotizaciones','reportes/cotizaciones_periodo')">
            <i class="bi bi-table"></i> Abrir en Sheets
          </button>
        </div>
        <p class="rep-info"><i class="bi bi-info-circle"></i> Incluye número de cotización, cliente, total y estado</p>
      </form>
    </div>
  </div>

  <!-- ── Bitácora de actividades (Logs) ── -->
  <div class="rep-card">
    <div class="rep-card-header">
      <div class="rep-icon orange"><i class="bi bi-journal-text"></i></div>
      <div>
        <div class="rep-title">Bitácora de actividades (Logs)</div>
        <div class="rep-desc">Exporta las acciones y cambios realizados por usuarios</div>
      </div>
    </div>
    <div class="rep-body">
      <form class="rep-form" id="form-logs" target="_blank">
        <input type="hidden" name="r" value="reportes/logs_actividades">
        <div class="row g-2 mb-2">
          <div class="col-6">
            <label class="form-label">Desde</label>
            <input type="date" class="form-control" name="desde" value="<?= date('Y-m-01') ?>">
          </div>
          <div class="col-6">
            <label class="form-label">Hasta</label>
            <input type="date" class="form-control" name="hasta" value="<?= date('Y-m-d') ?>">
          </div>
        </div>
        <div class="rep-actions">
          <button type="submit" formaction="<?= url('?r=reportes/logs_actividades') ?>" class="btn-csv primary">
            <i class="bi bi-filetype-csv"></i> Descargar CSV
          </button>
          <button type="button" class="btn-csv sheets" onclick="openSheets('form-logs','reportes/logs_actividades')">
            <i class="bi bi-table"></i> Abrir en Sheets
          </button>
        </div>
        <p class="rep-info"><i class="bi bi-info-circle"></i> Incluye usuario, rol, acción, tabla e IP</p>
      </form>
    </div>
  </div>

</div>

<div class="alert alert-info mt-4" style="font-size:.85rem">
  <i class="bi bi-google me-1"></i>

  
</div>

<script>
function buildUrl(formId, route) {
  const form = document.getElementById(formId);
  const data = new FormData(form);
  let url = '<?= url('') ?>?r=' + route;
  data.forEach((val, key) => {
    if (key !== 'r') url += '&' + encodeURIComponent(key) + '=' + encodeURIComponent(val);
  });
  return url;
}

function openSheets(formId, route) {
  const csvUrl = buildUrl(formId, route);
  openSheetsUrl(csvUrl);
}

function openSheetsUrl(csvUrl) {
  // Google Sheets puede importar un CSV desde URL pública.
  // Para red local, simplemente descargamos el CSV.
  const fullUrl = window.location.origin + csvUrl.replace(window.location.origin, '');
  // Intentar abrir Google Sheets con importación
  const sheetsUrl = 'https://docs.google.com/spreadsheets/d/create?usp=pp_url&pli=1';
  // Descargar CSV primero
  const a = document.createElement('a');
  a.href = csvUrl;
  a.download = '';
  a.click();
  // Abrir Sheets después de un momento
  setTimeout(() => {
    window.open(sheetsUrl, '_blank');
    alert('✅ El CSV se descargó. En Google Sheets:\nArchivo → Importar → Subir → selecciona el archivo descargado.\nUsando separador "punto y coma (;)".');
  }, 800);
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
