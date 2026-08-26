<?php require __DIR__ . '/../layouts/header.php'; ?>
<h3>Nueva orden de compra</h3>
<form method="post" action="<?= url('?r=compras/store') ?>" onsubmit="document.getElementById('items').value=JSON.stringify(cart)">
  <?= csrf_field() ?>
  <div class="card p-2 mb-2">
    <div class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label mb-1 small">Proveedor *</label>
        <select class="form-select form-select-sm" name="proveedor_id" required>
          <option value="">— seleccionar —</option>
          <?php foreach ($proveedores as $p): ?><option value="<?= e($p['id']) ?>"><?= e($p['nombre']) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label mb-1 small">Nº Factura *</label>
        <input type="text" class="form-control form-control-sm" name="numero_factura" required>
      </div>
      <div class="col-md-7">
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
          <tr><th>Producto</th><th style="width:80px">Cant</th><th style="width:100px">Precio</th><th class="text-end" style="width:100px">Subtotal</th><th class="text-center" style="width:40px"><i class="bi bi-trash"></i></th></tr>
        </thead>
        <tbody id="cartBody">
          <tr><td colspan="5" class="text-center text-muted py-3">Vacío</td></tr>
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
        <button class="btn btn-success btn-sm w-100"><i class="bi bi-save"></i> Crear orden de compra</button>
      </div>
    </div>
  </div>
</form>
<script>
const RATE=<?= cfg('itbms_rate') ?>; let cart=[];
const productosList = <?= json_encode($productos) ?>;
const productosMap = {};
productosList.forEach(p => { 
  if(p.codigo) productosMap[String(p.codigo).toUpperCase()] = p; 
  if(p.nombre) productosMap[String(p.nombre).toUpperCase()] = p;
});

const codeInput = document.getElementById('prodCode');
const prcInput = document.getElementById('prc');
const qtyInput = document.getElementById('qty');

function updatePrice() {
  const code = codeInput.value.trim().toUpperCase();
  if (productosMap[code]) {
    prcInput.value = productosMap[code].precio_compra || productosMap[code].precio || '';
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
  if (e.key === 'Enter') { e.preventDefault(); addProd(); }
});
prcInput.addEventListener('keypress', (e) => {
  if (e.key === 'Enter') { e.preventDefault(); addProd(); }
});
function render(){
  const tb=document.getElementById('cartBody');
  if(!cart.length){tb.innerHTML='<tr><td colspan="5" class="text-center text-muted py-3">Vacío</td></tr>';}
  else tb.innerHTML=cart.map((it,i)=>`<tr><td class="align-middle">${it.nombre}</td><td class="align-middle">${it.cantidad}</td><td class="align-middle">${it.precio.toFixed(2)}</td>
    <td class="text-end align-middle">${(it.cantidad*it.precio).toFixed(2)}</td>
    <td class="text-center align-middle"><button type="button" class="btn btn-sm btn-outline-danger py-0 px-1" onclick="cart.splice(${i},1);render()">×</button></td></tr>`).join('');
  const s=cart.reduce((a,b)=>a+b.cantidad*b.precio,0); const t=s*RATE;
  document.getElementById('sub').textContent=s.toFixed(2);
  document.getElementById('itbms').textContent=t.toFixed(2);
  document.getElementById('total').textContent=(s+t).toFixed(2);
}
function addProd(){
  const code = codeInput.value.trim().toUpperCase();
  const prod = productosMap[code];
  if (!prod) {
    alert("Código de producto no válido.");
    return;
  }
  cart.push({
    id: prod.id,
    nombre: prod.nombre,
    cantidad: parseInt(qtyInput.value) || 1,
    precio: parseFloat(prcInput.value) || 0
  });
  render();
  codeInput.value = '';
  prcInput.value = '';
  qtyInput.value = 1;
  codeInput.focus();
}
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>

<