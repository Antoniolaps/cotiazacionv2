<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización {{ $cotizacion->numero_factura }}</title>
    <style>
        @page { margin: 20mm; }
        body { font-family: Arial, sans-serif; font-size: 12pt; }
        .quote-paper { background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 40px; }
        .quote-header-brand { font-size: 1.8rem; font-weight: 800; color: #1e293b; }
        .quote-title-badge { background: #e0e7ff; color: #4338ca; padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 0.9rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; }
    </style>
</head>
<body>
    @php
@php
    // Determine header component based on selected sucursal
    // Fallback to City Mall if unknown
@endphp
    @endphp

    <div class="quote-paper">
@if($sucursal == 'City Mall')
    @include('components.sucursales.city_mall')
@elseif($sucursal == 'Outlet Regalon')
    @include('components.sucursales.outlet_regalon')
@elseif($sucursal == 'Dolar Moll 1.2.3')
    @include('components.sucursales.dolar_moll_1_2_3')
@else
    @include('components.sucursales.city_mall')
@endif
            <div class="text-end">
                <span class="quote-title-badge mb-2 d-inline-block">COTIZACIÓN DE COMPRA</span>
                <h4 class="fw-bold text-dark mb-1">{{ $cotizacion->numero_factura }}</h4>
                <p class="text-muted small mb-1"><strong>Fecha Emisión:</strong> {{ date('d/m/Y', strtotime($cotizacion->fecha_venta)) }}</p>
                <p class="text-muted small mb-0"><strong>Validez:</strong> 15 días calendario</p>
            </div>
        </div>
        @include('cotizaciones.show', ['cotizacion' => $cotizacion])
    </div>
</body>
</html>
