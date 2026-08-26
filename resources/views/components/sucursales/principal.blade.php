<div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
    <div>
        <div class="quote-header-brand d-flex align-items-center gap-2">
            <img src="/public/assets/img/Logo_Principal.png" alt="Logo Principal">
        </div>
        <p class="text-muted small mb-0 mt-1">Tel. C. R. 2732-2931 / 2783-2945  Fax: 2783-2952</p>
        <p class="text-muted small mb-0">Tel. Panamá: 727-7247 / Fax: 727-6591</p>
        <p class="text-muted small mb-0">R.U.C. 1513069-1-650069  D.V. 77</p>
    </div>
    <div class="text-end">
        <span class="quote-title-badge mb-2 d-inline-block">COTIZACIÓN DE COMPRA</span>
        <h4 class="fw-bold text-dark mb-1">{{ $cotizacion->numero_factura }}</h4>
        <p class="text-muted small mb-1"><strong>Fecha Emisión:</strong> {{ date('d/m/Y', strtotime($cotizacion->fecha_venta)) }}</p>
        <p class="text-muted small mb-0"><strong>Validez:</strong> 15 días calendario</p>
    </div>
</div>
