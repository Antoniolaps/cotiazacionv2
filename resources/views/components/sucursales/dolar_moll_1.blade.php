<div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
    <div>
        <div class="quote-header-brand d-flex align-items-center gap-2">
            <img src="/public/assets/img/Logo_DolarMoll1.png" alt="Logo Dolar Moll 1">
        </div>
        <p class="text-muted small mb-0 mt-1">Tel. C. R. 7777-8888 / 9999-0000  Fax: 1111-2222</p>
        <p class="text-muted small mb-0">Tel. Panamá: 333-4444 / Fax: 333-5555</p>
        <p class="text-muted small mb-0">R.U.C. 9876543-2-210987  D.V. 34</p>
    </div>
    <div class="text-end">
        <span class="quote-title-badge mb-2 d-inline-block">COTIZACIÓN DE COMPRA</span>
        <h4 class="fw-bold text-dark mb-1">{{ $cotizacion->numero_factura }}</h4>
        <p class="text-muted small mb-1"><strong>Fecha Emisión:</strong> {{ date('d/m/Y', strtotime($cotizacion->fecha_venta)) }}</p>
        <p class="text-muted small mb-0"><strong>Validez:</strong> 15 días calendario</p>
    </div>
</div>
