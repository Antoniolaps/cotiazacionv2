<x-app-layout title="Nueva Cotización - FerrePlus">
@push('styles')
<style>
  /* ── Campos con botón de lupa ─────────────────────────── */
  .search-field-wrap { position: relative; }
  .search-field-wrap input { padding-right: 40px; }
  .search-field-wrap .btn-open-modal {
    position: absolute; right: 6px; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: #6366f1; font-size: 1.1rem; cursor: pointer; padding: 2px 4px;
  }
  .search-field-wrap .btn-open-modal:hover { color: #4338ca; }

  /* ── Panel flotante (Modal) ───────────────────────────── */
  .float-panel-backdrop {
    display: none; position: fixed; inset: 0; background: rgba(15,23,42,.45);
    z-index: 1050; align-items: center; justify-content: center;
  }
  .float-panel-backdrop.active { display: flex; }
  .float-panel {
    background: #fff; border-radius: 14px; width: 680px; max-width: 96vw;
    max-height: 85vh; display: flex; flex-direction: column;
    box-shadow: 0 20px 60px rgba(0,0,0,.22);
    animation: panel-in .18s ease;
  }
  @keyframes panel-in { from { transform: translateY(-18px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
  .float-panel-header {
    padding: 18px 22px 14px; border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between;
  }
  .float-panel-header h5 { margin: 0; font-weight: 700; color: #1e293b; font-size: 1rem; }
  .float-panel-body { padding: 16px 22px; flex: 1; overflow-y: auto; }
  .float-panel-search { display: flex; gap: 8px; margin-bottom: 14px; }
  .float-panel-search input { flex: 1; }
  .float-panel-results { border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; }
  .float-panel-results table { width: 100%; border-collapse: collapse; font-size: .855rem; }
  .float-panel-results thead th {
    background: #f8fafc; padding: 9px 12px;
    font-size: .72rem; font-weight: 700; color: #64748b; text-transform: uppercase;
  }
  .float-panel-results tbody tr { border-top: 1px solid #f1f5f9; cursor: pointer; transition: background .12s; }
  .float-panel-results tbody tr:hover { background: #f0f4ff; }
  .float-panel-results tbody td { padding: 10px 12px; color: #0f172a; }
  .float-panel-results .empty-msg { text-align: center; color: #94a3b8; padding: 28px; font-size: .85rem; }

  /* ── Badge seleccionado ────────────────────────────────── */
  .selected-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: #e0e7ff; color: #3730a3; border-radius: 8px;
    padding: 6px 12px; font-size: .82rem; font-weight: 600; margin-top: 6px;
  }
  .selected-badge button { background: none; border: none; color: #6366f1; line-height: 1; padding: 0; font-size: .95rem; cursor: pointer; }
  .selected-badge button:hover { color: #dc2626; }

  /* ── Excel import ─────────────────────────────────────── */
  .excel-zone {
    border: 2px dashed #c7d2fe; border-radius: 10px; padding: 18px 16px;
    text-align: center; color: #818cf8; cursor: pointer; transition: all .18s;
    background: #f8f9ff;
  }
  .excel-zone:hover, .excel-zone.drag-over { border-color: #6366f1; background: #eef2ff; color: #4f46e5; }
  .excel-zone i { font-size: 2rem; display: block; margin-bottom: 6px; }
  .excel-zone small { display: block; color: #94a3b8; font-size: .75rem; margin-top: 4px; }
  #excel-file-input { display: none; }
  .reject-badge { background: #fee2e2; color: #b91c1c; border-radius: 4px; padding: 2px 8px; font-size: .75rem; font-weight: 600; }

  /* ── Spinner ───────────────────────────────────────────── */
  .spin { display: inline-block; width: 18px; height: 18px;
    border: 2px solid #c7d2fe; border-top-color: #6366f1;
    border-radius: 50%; animation: spin .65s linear infinite; }
  @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

<div class="crud-page-header">
  <div class="crud-page-title">
    <div class="title-icon"><i class="bi bi-file-earmark-plus"></i></div>
    <div>
      <h1>Nueva Cotización</h1>
    </div>
  </div>
  <a href="{{ route('cotizaciones.index') }}" class="btn btn-outline-secondary btn-sm">
    <i class="bi bi-arrow-left me-1"></i> Volver
  </a>
</div>

<form method="POST" action="{{ route('cotizaciones.store') }}" id="form-cotizacion">
  @csrf

  {{-- ── CABECERA ─────────────────────────────────────────────────────── --}}
  <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
    <div class="row g-3">

      {{-- N° Cotización (readonly) --}}
      <div class="col-md-3">
        <label class="form-label fw-semibold small">N° Cotización</label>
        <input type="text" class="form-control fw-bold text-primary" value="{{ $numeroCotizacion }}" readonly disabled>
        <input type="hidden" name="numero_cotizacion" value="{{ $numeroCotizacion }}">
      </div>

      {{-- Fecha (readonly = hoy) --}}
      <div class="col-md-2">
        <label class="form-label fw-semibold small">Fecha de Emisión</label>
        <input type="text" class="form-control" value="{{ date('d/m/Y') }}" readonly>
      </div>

      {{-- Cliente --}}
      <div class="col-md-4">
        <label class="form-label fw-semibold small">Cliente / Compañía</label>
        <div class="search-field-wrap">
          <input type="text" id="txt-cliente" name="cliente_nombre" class="form-control" placeholder="Escribe el nombre o usa la lupa" autocomplete="off">
          <button type="button" class="btn-open-modal" onclick="openPanel('cliente')" title="Buscar Cliente Registrado">
            <i class="bi bi-search"></i>
          </button>
        </div>
        <input type="hidden" name="cliente_id" id="cliente_id">
        <div id="cliente-badge"></div>
      </div>
      
      {{-- Sucursal --}}
      <div class="col-md-3">
        <label class="form-label fw-semibold small">Sucursal / Lugar <span class="text-muted"></span></label>
        <div class="search-field-wrap">
          <input type="text" id="txt-sucursal" class="form-control" placeholder="Buscar sucursal 🔍" readonly>
          <button type="button" class="btn-open-modal" onclick="openPanel('sucursal')">
            <i class="bi bi-search"></i>
          </button>
        </div>
        <input type="hidden" name="punto_facturacion" id="sucursal_id">
        <div id="sucursal-badge"></div>
      </div>

    </div>
  </div>


  {{-- ── PRODUCTOS ───────────────────────────────────────────────────── --}}
  <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold mb-0"><i class="bi bi-box-seam me-2"></i> Productos de la Cotización</h5>
      <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-primary btn-sm fw-semibold" onclick="openPanel('producto')">
          <i class="bi bi-search me-1"></i> Buscar Producto
        </button>
      </div>
    </div>

    {{-- Tabla de ítems --}}
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" id="items-table">
        <thead class="table-light">
          <tr>
            <th style="width:110px">codigo_barras</th>
            <th style="width:110px">Referencia</th>
            <th>Producto</th>
            <th style="width:100px" class="text-center">Cant.</th>
            <th style="width:130px" class="text-end">Precio Unit.</th>
            <th style="width:130px" class="text-end">Subtotal</th>
            <th style="width:44px"></th>
          </tr>
        </thead>
        <tbody id="items-body">
          <tr id="empty-row">
            <td colspan="7" class="text-center text-muted py-5">
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    {{-- Totales --}}
    <div class="row justify-content-end mt-4">
      <div class="col-md-4">
        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted">Subtotal neto:</span>
          <span id="txt-subtotal" class="fw-semibold text-dark">$0.00</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted">ITBMS (7%):</span>
          <span id="txt-itbms" class="fw-semibold text-dark">$0.00</span>
        </div>
        <div class="d-flex justify-content-between fs-4 fw-bold border-top pt-2">
          <span>TOTAL:</span>
          <span id="txt-total" class="text-success">$0.00</span>
        </div>
      </div>
    </div>

    <div class="mt-4 text-end">
      <button type="submit" id="btn-submit" class="btn btn-success px-5 py-2 fw-bold fs-5 shadow" disabled>
        <i class="bi bi-file-earmark-check me-2"></i> Generar Cotización
      </button>
    </div>
  </div>
</form>

{{-- ══════════════════════════════════════════════════════════
     PANEL FLOTANTE — CLIENTE
═══════════════════════════════════════════════════════════ --}}
<div class="float-panel-backdrop" id="panel-cliente">
  <div class="float-panel">
    <div class="float-panel-header">
      <h5><i class="bi bi-person-circle me-2 text-primary"></i>Buscar Cliente</h5>
      <button type="button" class="btn-close" onclick="closePanel('cliente')"></button>
    </div>
    <div class="float-panel-body">
      <div class="float-panel-search">
        <input type="text" id="search-cliente-input" class="form-control" placeholder="Código, nombre o cédula/RUC…">
        <button class="btn btn-primary" onclick="doSearch('cliente')">
          <i class="bi bi-search"></i> Buscar
        </button>
      </div>
      <div class="float-panel-results" id="results-cliente">
        <div class="empty-msg">Ingresa un término y presiona <strong>Buscar</strong></div>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     PANEL FLOTANTE — SUCURSAL
═══════════════════════════════════════════════════════════ --}}
<div class="float-panel-backdrop" id="panel-sucursal">
  <div class="float-panel">
    <div class="float-panel-header">
      <h5><i class="bi bi-shop me-2 text-primary"></i>Seleccionar Sucursal</h5>
      <button type="button" class="btn-close" onclick="closePanel('sucursal')"></button>
    </div>
    <div class="float-panel-body">
      <div class="float-panel-results">
        <table>
          <thead><tr><th>Lugar / Sucursal</th><th>Selec.</th></tr></thead>
          <tbody>
            <tr onclick="selectSucursal({nombre: 'City Mall'})" title="Clic para seleccionar">
              <td class="fw-semibold">City Mall</td>
            </tr>
            <tr onclick="selectSucursal({nombre: 'Outlet Regalon'})" title="Clic para seleccionar">
              <td class="fw-semibold">Outlet Regalon</td>
            </tr>
            <tr onclick="selectSucursal({nombre: 'Dolar Moll 1,2,3'})" title="Clic para seleccionar">
              <td class="fw-semibold">Dolar Moll 1,2,3</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     PANEL FLOTANTE — PRODUCTO
═══════════════════════════════════════════════════════════ --}}
<div class="float-panel-backdrop" id="panel-producto">
  <div class="float-panel" style="width:780px">
    <div class="float-panel-header">
      <h5><i class="bi bi-box-seam me-2 text-primary"></i>Buscar Producto</h5>
      <button type="button" class="btn-close" onclick="closePanel('producto')"></button>
    </div>
    <div class="float-panel-body">
      <div class="float-panel-search">
        <input type="text" id="search-producto-input" class="form-control" placeholder="Código o nombre del producto…">
        <button class="btn btn-primary" onclick="doSearch('producto')">
          <i class="bi bi-search"></i> Buscar
        </button>
      </div>
      <div class="float-panel-results" id="results-producto">
        <div class="empty-msg">Ingresa un término y presiona <strong>Buscar</strong></div>
      </div>
      <p class="text-muted mt-2" style="font-size:.78rem">
        <i class="bi bi-info-circle me-1"></i>
        Al seleccionar un producto se te pedirá la cantidad y precio antes de agregarlo.
      </p>
    </div>
  </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     MINI-MODAL — Cantidad y precio al agregar producto
═══════════════════════════════════════════════════════════ --}}
<div class="float-panel-backdrop" id="panel-qty">
  <div class="float-panel" style="width:380px">
    <div class="float-panel-header">
      <h5 id="qty-panel-title"><i class="bi bi-plus-circle me-2 text-success"></i>Agregar Producto</h5>
      <button type="button" class="btn-close" onclick="closePanel('qty')"></button>
    </div>
    <div class="float-panel-body">
      <input type="hidden" id="qty-prod-id">
      <input type="hidden" id="qty-prod-codigo">
      <input type="hidden" id="qty-prod-nombre">
      <div class="mb-3">
        <label class="form-label fw-semibold">Cantidad</label>
        <input type="number" id="qty-cant" class="form-control" value="1" min="1">
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Precio Unitario ($)</label>
        <input type="number" step="0.01" id="qty-precio" class="form-control" placeholder="0.00"  disabled >
      </div>
      <div class="d-grid">
        <button type="button" class="btn btn-success fw-bold" onclick="confirmAddProduct()">
          <i class="bi bi-plus-lg me-1"></i> Agregar a Cotización
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
{{-- SheetJS para parseo de Excel en el cliente (sin petición extra) --}}
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script>
const CSRF   = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const RATE   = 0.07;
let quoteItems = [];

// ─── URLS de búsqueda ───────────────────────────────────────────────────────
const URL_CLIENTE   = '{{ route("cotizaciones.buscar.cliente") }}';
const URL_PRODUCTO  = '{{ route("cotizaciones.buscar.producto") }}';
const URL_PROVEEDOR = '{{ route("cotizaciones.buscar.proveedor") }}';
const URL_LOTE      = '{{ route("cotizaciones.validar_lote") }}';

// ─── Panel flotante ─────────────────────────────────────────────────────────
function openPanel(type) {
  document.getElementById('panel-' + type).classList.add('active');
  const inp = document.getElementById('search-' + type + '-input');
  if (inp) setTimeout(() => inp.focus(), 80);
}
function closePanel(type) {
  document.getElementById('panel-' + type).classList.remove('active');
}

// Cerrar con Escape
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    ['cliente','producto','proveedor','sucursal','qty'].forEach(t => {
      let p = document.getElementById('panel-' + t);
      if(p) p.classList.remove('active');
    });
  }
});

// Enter en campo de búsqueda
['cliente','producto','proveedor'].forEach(type => {
  const inp = document.getElementById('search-' + type + '-input');
  if (inp) inp.addEventListener('keydown', e => { if(e.key === 'Enter') { e.preventDefault(); doSearch(type); } });
});

// ─── Un solo request por tipo ────────────────────────────────────────────────
function doSearch(type) {
  const inp  = document.getElementById('search-' + type + '-input');
  const q    = inp.value.trim();
  const box  = document.getElementById('results-' + type);
  const urls = { cliente: URL_CLIENTE, producto: URL_PRODUCTO, proveedor: URL_PROVEEDOR };

  if (!q) { box.innerHTML = '<div class="empty-msg">Escribe algo para buscar.</div>'; return; }
 
  box.innerHTML = '<div class="empty-msg"><span class="spin"></span> Buscando…</div>';

  fetch(`${urls[type]}?q=${encodeURIComponent(q)}`)
    .then(r => r.json())
    .then(data => renderResults(type, data))
    .catch(() => { box.innerHTML = '<div class="empty-msg text-danger">Error al conectar con el servidor.</div>'; });
}

// ─── Renderizar resultados en la tabla del panel ────────────────────────────
function renderResults(type, data) {
  const box = document.getElementById('results-' + type);
  if (!data.length) {
    box.innerHTML = '<div class="empty-msg">⚠️ Sin resultados. El código/nombre no existe en el sistema.</div>';
    return;
  }

  const configs = {
    cliente: {
      headers: ['Código','Nombre','Cédula/RUC','Teléfono'],
      row: (r) => `<td>${r.codigo??''}</td><td class="fw-semibold">${r.nombre}</td><td>${r.cedula_ruc??''}</td><td>${r.telefono??''}</td>`,
      onclick: (r) => selectCliente(r),
    },
    producto: {
      headers: ['codigo_barras','Código','Nombre','Precio Venta'],
      row: (r) => `<td class="fw-bold">${r.codigo_barras ?? '-'}</td><td>${r.codigo ?? '-'}</td><td>${r.nombre}</td><td class="fw-semibold text-success">$${parseFloat(r.precio_venta).toFixed(2)}</td>`,
      onclick: (r) => openQtyPanel(r),
    },
    proveedor: {
      headers: ['Código','Nombre','Teléfono'],
      row: (r) => `<td>${r.codigo??''}</td><td class="fw-semibold">${r.nombre}</td><td>${r.telefono??''}</td>`,
      onclick: (r) => selectProveedor(r),
    },
  };

  const cfg = configs[type];
  const headers = cfg.headers.map(h => `<th>${h}</th>`).join('');
  const rows = data.map(r => `
    <tr onclick='(${cfg.onclick.toString()})(${JSON.stringify(r)})' title="Clic para seleccionar">
      ${cfg.row(r)}
    </tr>
  `).join('');

  box.innerHTML = `
    <table>
      <thead><tr>${headers}<th>Selec.</th></tr></thead>
      <tbody>${rows}</tbody>
    </table>`;
}

// ─── Selección de Cliente ────────────────────────────────────────────────────
function selectCliente(r) {
  document.getElementById('cliente_id').value = r.id;
  document.getElementById('txt-cliente').value = r.nombre; // Guardamos el nombre en el input de texto también
  document.getElementById('cliente-badge').innerHTML = `
    <span class="selected-badge">
      <i class="bi bi-person-check-fill"></i> Registrado
      <button type="button" onclick="clearCliente()" title="Quitar"><i class="bi bi-x-lg"></i></button>
    </span>`;
  closePanel('cliente');
}
function clearCliente() {
  document.getElementById('cliente_id').value = '';
  document.getElementById('txt-cliente').value = '';
  document.getElementById('cliente-badge').innerHTML = '';
}

// ─── Selección de Sucursal ────────────────────────────────────────────────────
function selectSucursal(r) {
  document.getElementById('sucursal_id').value = r.nombre;
  document.getElementById('txt-sucursal').value = r.nombre;
  document.getElementById('sucursal-badge').innerHTML = `
    <span class="selected-badge">
      <i class="bi bi-shop"></i> ${r.nombre}
      <button type="button" onclick="clearSucursal()" title="Quitar"><i class="bi bi-x-lg"></i></button>
    </span>`;
  closePanel('sucursal');
}
function clearSucursal() {
  document.getElementById('sucursal_id').value = '';
  document.getElementById('txt-sucursal').value = '';
  document.getElementById('sucursal-badge').innerHTML = '';
}

// ─── Selección de Proveedor ──────────────────────────────────────────────────
function selectProveedor(r) {
  document.getElementById('proveedor_id').value = r.id;
  document.getElementById('txt-proveedor').value = `${r.codigo ?? ''} — ${r.nombre}`;
  document.getElementById('proveedor-badge').innerHTML = `
    <span class="selected-badge">
      <i class="bi bi-truck"></i> ${r.nombre}
      <button type="button" onclick="clearProveedor()" title="Quitar"><i class="bi bi-x-lg"></i></button>
    </span>`;
  closePanel('proveedor');
}
function clearProveedor() {
  document.getElementById('proveedor_id').value = '';
  document.getElementById('txt-proveedor').value = '';
  document.getElementById('proveedor-badge').innerHTML = '';
}

// ─── Mini-panel de cantidad/precio antes de agregar ─────────────────────────
function openQtyPanel(prod) {
  document.getElementById('qty-prod-id').value     = prod.id;
  document.getElementById('qty-prod-codigo').value = prod.codigo ?? '';
  document.getElementById('qty-prod-codigo').dataset.barras = prod.codigo_barras ?? ''; 
  document.getElementById('qty-prod-nombre').value = prod.nombre;
  document.getElementById('qty-precio').value       = parseFloat(prod.precio_venta).toFixed(2);
  document.getElementById('qty-cant').value         = 1;
  document.getElementById('qty-panel-title').innerHTML =
    `<i class="bi bi-plus-circle me-2 text-success"></i>${prod.nombre}`;
  closePanel('producto');
  openPanel('qty');
  setTimeout(() => document.getElementById('qty-cant').focus(), 80);
}

document.getElementById('qty-cant').addEventListener('keydown', e => {
  if (e.key === 'Enter') { e.preventDefault(); document.getElementById('qty-precio').focus(); }
});
document.getElementById('qty-precio').addEventListener('keydown', e => {
  if (e.key === 'Enter') { e.preventDefault(); confirmAddProduct(); }
});

function confirmAddProduct() {
  const id = parseInt(document.getElementById('qty-prod-id').value);
  const codigo = document.getElementById('qty-prod-codigo').value;
  const codigo_barras = document.getElementById('qty-prod-codigo').dataset.barras?? ''; 
  const nombre = document.getElementById('qty-prod-nombre').value;

  const cant = parseInt(document.getElementById('qty-cant').value) || 1;
  const precio = parseFloat(document.getElementById('qty-precio').value) || 0;

  if (cant < 1) { alert('La cantidad debe ser mayor a 0.'); return; }
  if (precio <= 0) { alert('Ingrese un precio válido.'); return; }

  const existing = quoteItems.find(i => i.id === id);
  if (existing) {
    existing.cantidad += cant;
    existing.precio = precio;
  } else {
    quoteItems.push({ id, codigo, codigo_barras, nombre, cantidad: cant, precio }); // 
  }

  closePanel('qty');
  renderTable();
}

// ─── Tabla de ítems ─────────────────────────────────────────────────────────
function renderTable() {
  const body = document.getElementById('items-body');
  document.getElementById('btn-submit').disabled = quoteItems.length === 0;

  if (!quoteItems.length) {
    body.innerHTML = `<tr id="empty-row"><td colspan="7" class="text-center text-muted py-5">
      <i class="bi bi-inbox fs-2 d-block mb-2"></i>
      Usa <strong>Buscar Producto</strong> para agregar ítems</td></tr>`;
  } else {
    body.innerHTML = quoteItems.map((item, idx) => `
      <tr>
        <td class="fw-bold text-secondary">${item.codigo_barras?? '-'}</td>
        <td class="fw-bold text-secondary">${item.codigo?? '-'}
          <input type="hidden" name="items[${idx}][id]" value="${item.id}">
          <input type="hidden" name="items[${idx}][codigo]" value="${item.codigo}">
          <input type="hidden" name="items[${idx}][codigo_barras]" value="${item.codigo_barras}">
          <input type="hidden" name="items[${idx}][cantidad]" value="${item.cantidad}">
          <input type="hidden" name="items[${idx}][precio]" value="${item.precio}">
        </td>
        <td class="fw-semibold">${item.nombre}</td>
        <td class="text-center">
          <input type="number" class="form-control form-control-sm text-center" style="width:75px;margin:0 auto"
            value="${item.cantidad}" min="1" onchange="updateQty(${idx}, this.value)">
        </td>
        <td class="text-end">
          <input type="number" step="0.01" class="form-control form-control-sm text-end" style="width:100px;margin:0 0 0 auto"
            value="${item.precio.toFixed(2)}" onchange="updatePrecio(${idx}, this.value)">
        </td>
        <td class="text-end fw-bold">$${(item.cantidad * item.precio).toFixed(2)}</td>
        <td class="text-center">
          <button type="button" class="btn btn-sm text-danger p-0" onclick="removeItem(${idx})" title="Eliminar">
            <i class="bi bi-x-circle-fill fs-5"></i>
          </button>
        </td>
      </tr>`).join('');
  }

  const sub = quoteItems.reduce((a, i) => a + i.cantidad * i.precio, 0);
  const itb = Math.round(sub * RATE * 100) / 100;
  document.getElementById('txt-subtotal').innerText = `$${sub.toFixed(2)}`;
  document.getElementById('txt-itbms').innerText = `$${itb.toFixed(2)}`;
  document.getElementById('txt-total').innerText = `$${(sub + itb).toFixed(2)}`;
}

function removeItem(idx) { quoteItems.splice(idx, 1); renderTable(); }
function updateQty(idx, v) {
  const q = parseInt(v);
  if (q > 0) {
    quoteItems[idx].cantidad = q;
    document.querySelector(`input[name="items[${idx}][cantidad]"]`).value = q; 
  } else removeItem(idx);
  renderTable();
}
function updatePrecio(idx, v) {
  const p = parseFloat(v);
  if (p >= 0) {
    quoteItems[idx].precio = p;
    document.querySelector(`input[name="items[${idx}][precio]"]`).value = p.toFixed(2); 
  }
  renderTable();
}

// ─── Importar Excel (SheetJS + validación en lote) ──────────────────────────
const excelInput = document.getElementById('excel-file-input');
const excelZone  = document.getElementById('excel-zone');

// Drag & drop
if(excelZone) {
  excelZone.addEventListener('dragover', e => { e.preventDefault(); excelZone.classList.add('drag-over'); });
  excelZone.addEventListener('dragleave', () => excelZone.classList.remove('drag-over'));
  excelZone.addEventListener('drop', e => {
    e.preventDefault(); excelZone.classList.remove('drag-over');
    const f = e.dataTransfer.files[0];
    if (f) processExcel(f);
  });
}
if(excelInput) {
  excelInput.addEventListener('change', e => { if (e.target.files[0]) processExcel(e.target.files[0]); });
}

function processExcel(file) {
  excelZone.innerHTML = '<i class="bi bi-arrow-repeat spin fs-2"></i><span>Procesando archivo…</span>';

  const reader = new FileReader();
  reader.onload = function(e) {
    try {
      const wb  = XLSX.read(e.target.result, { type: 'binary' });
      const ws  = wb.Sheets[wb.SheetNames[0]];
      const rows = XLSX.utils.sheet_to_json(ws, { defval: '' });

      if (!rows.length) {
        alert('El archivo está vacío o no tiene el formato correcto.');
        resetExcelZone(); return;
      }

      // Normalizar: buscar columnas codigo, cantidad, precio (case-insensitive)
      const normalize = (obj) => {
        const out = {};
        Object.keys(obj).forEach(k => { out[k.toLowerCase().trim()] = obj[k]; });
        return out;
      };
      const items = rows.map(normalize).filter(r => r.codigo);
      const codigos = [...new Set(items.map(r => String(r.codigo).trim().toUpperCase()))];

      if (!codigos.length) {
        alert('No se encontró la columna "codigo" en el Excel.'); resetExcelZone(); return;
      }

      // UN solo request al servidor para validar todos los códigos juntos
      fetch(URL_LOTE, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ codigos }),
      })
      .then(r => r.json())
      .then(resp => applyExcelBatch(items, resp))
      .catch(() => { alert('Error de conexión al validar lote.'); resetExcelZone(); });

    } catch(ex) {
      alert('No se pudo leer el archivo. Verifica que sea .xlsx o .csv.');
      resetExcelZone();
    }
  };
  reader.readAsBinaryString(file);
}

function applyExcelBatch(rows, resp) {
  const foundMap = {};
  resp.encontrados.forEach(p => { foundMap[p.codigo.toUpperCase()] = p; });

  let agregados = 0, rechazados = [];

  rows.forEach(r => {
    const cod = String(r.codigo).trim().toUpperCase();
    const cant = parseInt(r.cantidad) || 1;
    const prec = parseFloat(r.precio) || 0;
    const prod = foundMap[cod];

    if (prod) {
      const ex = quoteItems.find(i => i.id === prod.id);
      if (ex) { ex.cantidad += cant; }
      else {
        quoteItems.push({
          id: prod.id,
          codigo: prod.codigo,
          codigo_barras: prod.codigo_barras?? '', // <-- AGREGA ESTO
          nombre: prod.nombre,
          cantidad: cant,
          precio: prec || parseFloat(prod.precio_venta)
        });
      }
      agregados++;
    } else {
      rechazados.push(cod);
    }
  });

  renderTable();
}
  

</script>
@endpush
</x-app-layout>
