<?php require __DIR__ . '/../layouts/header.php'; ?>
<h3>Nueva Cotización</h3>
<form method="post" action="<?= url('?r=ventas/cotizacion_store') ?>" onsubmit="document.getElementById('items').value=JSON.stringify(cart)">
  <?= csrf_field() ?>
  <div class="card p-2 mb-2">
    <div class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label mb-1 small">Cliente *</label>
        <input type="text" class="form-control form-control-sm" name="cliente_nombre" id="cliente_nombre" list="clientesList" placeholder="Ingrese nombre" required autocomplete="off">
        <datalist id="clientesList">
          <?php foreach ($clientes as $c): ?>
            <option value="<?= e($c['nombre']) ?>" data-id="<?= e($c['id']) ?>"></option>
          <?php endforeach; ?>
        </datalist>
        <input type="hidden" name="cliente_id" id="cliente_id">
      </div>
      <div class="col-md-2">
        <label class="form-label mb-1 small">Proveedor</label>
        <select class="form-select form-select-sm" name="proveedor_id">
          <option value="">— Ninguno —</option>
          <?php foreach ($proveedores as $p): ?>
            <option value="<?= e($p['id']) ?>" <?= (strtolower($p['nombre']) === 'desfeer' || $p['nombre'] === 'DesFeer') ? 'selected' : '' ?>><?= e($p['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label mb-1 small">Nº Factura Prov.</label>
        <input type="text" class="form-control form-control-sm" name="factura_proveedor" value="FAC-<?= time() ?>" placeholder="Ej. FAC-001">
      </div>
      <div class="col-md-2">
        <label class="form-label mb-1 small">Observaciones</label>
        <input type="text" class="form-control form-control-sm" name="observaciones" placeholder="Opcional">
      </div>
      <div class="col-md-12 mt-2">
        <label class="form-label mb-1 small">Agregar producto</label>
        <div class="d-flex gap-1">
          <input type="text" id="prodCode" list="prodList" class="form-control form-control-sm" placeholder="Código o Producto" autocomplete="off" autofocus>
          <datalist id="prodList">
            <?php foreach ($productos as $p): ?>
              <option value="<?= e($p['codigo']) ?>"><?= e($p['nombre']) ?></option>
            <?php endforeach; ?>
          </datalist>
          <input type="number" min="1" value="1" id="qty" class="form-control form-control-sm" style="max-width:70px" placeholder="Cant">
          <input type="number" step="0.01" id="prc" class="form-control form-control-sm" style="max-width:90px" placeholder="Precio">
          <button type="button" class="btn btn-primary btn-sm" onclick="addProd()">Agregar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="card p-2">
    <div class="table-responsive" style="max-height: 55vh;">
      <table class="table table-sm table-bordered table-hover mb-0" style="font-size:0.85rem">
        <thead class="table-light sticky-top">
          <tr>
            <th>Producto</th>
            <th style="width:80px">Cant</th>
            <th style="width:100px">Precio</th>
            <th class="text-end" style="width:100px">Subtotal</th>
            <th class="text-center" style="width:40px"><i class="bi bi-trash"></i></th>
          </tr>
        </thead>
        <tbody id="cartBody">
          <tr>
            <td colspan="5" class="text-center text-muted py-3">Vacío</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="row mt-2 justify-content-end text-end" style="font-size:0.9rem">
      <div class="col-md-4 col-lg-3">
        <div class="d-flex justify-content-between border-bottom pb-1"><span>Subtotal:</span><strong id="sub">0.00</strong></div>
        <div class="d-flex justify-content-between border-bottom py-1"><span>ITBMS:</span><strong id="itbms">0.00</strong></div>
        <div class="d-flex justify-content-between py-1"><span class="fw-bold">Total:</span><span class="fw-bold text-success" id="total">0.00</span></div>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-12">
        <input type="hidden" name="items" id="items">
        <button class="btn btn-success btn-sm w-100"><i class="bi bi-file-earmark-text"></i> Guardar Cotización</button>
      </div>
    </div>
  </div>
</form>
<script>
  const RATE = <?= cfg('itbms_rate') ?>;
  let cart = [];
  const productosList = <?= json_encode($productos) ?>;
  const productosMap = {};
  productosList.forEach(p => {
    if (p.codigo) productosMap[String(p.codigo).toUpperCase()] = p;
    if (p.nombre) productosMap[String(p.nombre).toUpperCase()] = p;
  });

  const clienteNombreInput = document.getElementById('cliente_nombre');
  const clienteIdInput = document.getElementById('cliente_id');
  const clientesListOpts = document.querySelectorAll('#clientesList option');
  
  if (clienteNombreInput) {
      clienteNombreInput.addEventListener('input', function() {
          let val = this.value;
          clienteIdInput.value = '';
          for (let i = 0; i < clientesListOpts.length; i++) {
              if (clientesListOpts[i].value === val) {
                  clienteIdInput.value = clientesListOpts[i].getAttribute('data-id');
                  break;
              }
          }
      });
  }

  const codeInput = document.getElementById('prodCode');
  const prcInput = document.getElementById('prc');
  const qtyInput = document.getElementById('qty');

  function updatePrice() {
    const code = codeInput.value.trim().toUpperCase();
    if (productosMap[code]) {
      prcInput.value = productosMap[code].precio_venta || productosMap[code].precio || '';
    }
  }

  codeInput.addEventListener('input', updatePrice);
  codeInput.addEventListener('change', updatePrice);

  codeInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      updatePrice();
      const code = codeInput.value.trim().toUpperCase();
      if (productosMap[code]) qtyInput.focus();
    }
  });
  qtyInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      addProd();
    }
  });
  prcInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      addProd();
    }
  });

  function render() {
    const tb = document.getElementById('cartBody');
    if (!cart.length) {
      tb.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">Vacío</td></tr>';
    } else {
      tb.innerHTML = cart.map((i, idx) => `<tr>
      <td>${i.nombre}</td>
      <td><input type="number" class="form-control form-control-sm" value="${i.cantidad}" onchange="updateQty(${idx},this.value)"></td>
      <td><input type="number" step="0.01" class="form-control form-control-sm" value="${i.precio.toFixed(2)}" onchange="updateRowPrc(${idx},this.value)"></td>
      <td class="text-end">${(i.cantidad*i.precio).toFixed(2)}</td>
      <td class="text-center"><button type="button" class="btn btn-sm text-danger p-0" onclick="rem(${idx})"><i class="bi bi-x-circle"></i></button></td>
    </tr>`).join('');
    }
    let s = 0;
    cart.forEach(i => s += i.cantidad * i.precio);
    let i = s * RATE,
      t = s + i;
    document.getElementById('sub').innerText = s.toFixed(2);
    document.getElementById('itbms').innerText = i.toFixed(2);
    document.getElementById('total').innerText = t.toFixed(2);
  }

  function addProd() {
    const code = codeInput.value.trim().toUpperCase();
    const q = parseFloat(qtyInput.value) || 1;
    const p = parseFloat(prcInput.value) || 0;
    if (!code) return;
    const pr = productosMap[code];
    if (!pr) {
      alert('Producto no encontrado');
      return;
    }
    const ex = cart.find(i => i.id === pr.id);
    if (ex) ex.cantidad += q;
    else cart.push({
      id: pr.id,
      nombre: pr.nombre,
      codigo: pr.codigo,
      cantidad: q,
      precio: p
    });
    codeInput.value = '';
    qtyInput.value = 1;
    prcInput.value = '';
    codeInput.focus();
    render();
  }

  function rem(idx) {
    cart.splice(idx, 1);
    render();
  }

  function updateQty(idx, v) {
    const q = parseFloat(v);
    if (q > 0) cart[idx].cantidad = q;
    else rem(idx);
    render();
  }

  function updateRowPrc(idx, v) {
    const p = parseFloat(v);
    if (p >= 0) cart[idx].precio = p;
    render();
  }
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>