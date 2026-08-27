<x-app-layout title="Formato de Cotización {{ $cotizacion->numero_factura }}">
@push('styles')
<style>
    @media print {
        .no-print, nav, footer, .crm-navbar { display: none !important; }
        body { background: white !important; color: black !important; }
        .quote-paper { padding: 0 !important; margin: 0 !important; box-shadow: none !important; border: none !important; }
        table { page-break-inside: avoid; }
        thead { display: table-header-group; }
    }
    
    .quote-paper { background: white; border-radius: 12px; padding: 35px 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .quote-paper img { max-width: 260px; height: auto; }
    .quote-title-badge { background: #e0e7ff; color: #4338ca; padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 0.85rem; }
    .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px 18px; }
    
    table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    thead th { background: #1e293b; color: white; padding: 10px 8px; font-size: 0.78rem; text-transform: uppercase; font-weight: 700; }
    tbody td { border: 1px solid #e2e8f0; padding: 8px 10px; }
    tbody tr:nth-child(even) { background: #f8fafc; }

    .totales-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; }
    .firma-box { border-top: 1px solid #94a3b8; padding-top: 50px; width: 75%; margin: 0 auto; }
</style>

<style>
        @page { 
            size: A4; /* 21.59cm x 27.94cm Carta */
            margin: 15mm 15mm 20mm 15mm; 
        }
        * { box-sizing: border-box; }
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            font-size: 9.5pt; 
            color: #000; 
            line-height: 1.3;
        }

        /* ENCABEZADO FIJO EN CADA PAGINA */
        .header { 
            position: running(header);
            border-bottom: 2px solid #4338ca; 
            padding-bottom: 10px; 
            margin-bottom: 15px; 
        }
        .footer { 
            position: running(footer);
            border-top: 1px solid #ccc;
            padding-top: 5px;
            font-size: 8pt;
            color: #64748b;
        }

        @media print {
            .header { position: running(header); }
            .footer { position: running(footer); }
        }

        .d-flex { display: flex; justify-content: space-between; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: 700; }
        .text-muted { color: #64748b; }
        .small { font-size: 8.5pt; }

        /* TABLA */
        table { width: 100%; border-collapse: collapse; font-size: 9pt; }
        thead { display: table-header-group; } /* Repite encabezado en cada hoja */
        tfoot { display: table-footer-group; } /* Totales solo al final */
        thead th { 
            background-color: #1e293b; 
            color: white; 
            border: 1px solid #000; 
            padding: 6px 4px; 
            text-align: left; 
            font-size: 8.5pt;
        }
        tbody td { border: 1px solid #000; padding: 5px 4px; vertical-align: top; }
        tbody tr { page-break-inside: avoid; } /* No partir filas */

        /* TOTALES */
        .totales { width: 45%; float: right; margin-top: 10px; }
        .totales td { border: none; padding: 3px 5px; }
        .totales .total-row { font-size: 12pt; font-weight: 700; color: #15803d; }

        /* PIE FIRMA */
        .firmas { width: 100%; margin-top: 30px; }
        .firmas td { width: 50%; text-align: center; padding-top: 50px; border-top: 1px solid #000; }

        .page-number:before { content: "Página " counter(page) " de " counter(pages); }
    </style>

@endpush

<section>
<div class="row justify-content-center">
<div class="col-lg-10">

    {{-- Acciones --}}
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <a href="{{ route('cotizaciones.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a Cotizaciones
        </a>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary fw-bold">
                <i class="bi bi-printer me-1"></i> Imprimir Cotización
            </button>
            <form method="POST" action="{{ route('cotizaciones.convertir', $cotizacion->id) }}" onsubmit="return confirm('¿Desea procesar esta cotización y convertirla en Factura formal?');">
                @csrf
                <button type="submit" class="btn btn-success fw-bold">
                    <i class="bi bi-cart-check me-1"></i> Convertir en Venta
                </button>
            </form>
        </div>
    </div>

    {{-- Formato Imprimible --}}
    <div class="quote-paper">
        @php
        $sucursalesInfo = [
            'City Mall' => [
                'logo' => asset('assets/img/Logo_City.png'),
                'line1' => 'Tel. C. R. 2732-2931 / 2783-2945 Fax: 2783-2952',
                'line2' => 'Tel. Panamá: 727-7247 / Fax: 727-6591',
                'line3' => 'R.U.C. 1513069-1-650069 D.V. 77',
            ],
            'Outlet Regalon' => [
                'logo' => asset('assets/img/Logo_Outlet.jpeg'),
                'line1' => 'Tel. Panamá: 850-6890',
                'line2' => '',
                'line3' => 'R.U.C. 155703452-2-2021 D.V. 23',
            ],
            'Dolar Moll 1,2,3' => [
                'logo' => asset('assets/img/Logo_CentroDollar.jpeg'),
                'line1' => 'Tel. Panamá: 727-6574 / Fax: 727-6591',
                'line2' => '',
                'line3' => 'R.U.C. 395593-1-423610 D.V. 04',
            ],
        ];
        $suc = $sucursalesInfo[$cotizacion->punto_facturacion] ?? $sucursalesInfo['City Mall'];
        @endphp

        {{-- ENCABEZADO --}}
        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
            <div>
                <img src="{{ $suc['logo'] }}" alt="{{ $cotizacion->punto_facturacion }}">
                <p class="text-muted small mb-0 mt-2">{{ $suc['line1'] }}</p>
                @if($suc['line2'])<p class="text-muted small mb-0">{{ $suc['line2'] }}</p>@endif
                <p class="text-muted small mb-0">{{ $suc['line3'] }}</p>
            </div>
            <div class="text-end">
                <span class="quote-title-badge mb-2 d-inline-block">COTIZACIÓN DE COMPRA</span>
                <h3 class="fw-bold text-dark mb-1">{{ $cotizacion->numero_factura }}</h3>
                <p class="text-muted small mb-1"><strong>Fecha Emisión:</strong> {{ \Carbon\Carbon::parse($cotizacion->fecha_venta)->format('d/m/Y') }}</p>
                <p class="text-muted small mb-0"><strong>Validez:</strong> 30 días</p>
            </div>
        </div>

        {{-- DATOS CLIENTE Y COMERCIAL --}}
        <div class="row g-3 mb-4">
            <div class="col-md-7 info-box">
                <h6 class="fw-bold text-uppercase text-muted small mb-2">Datos del Cliente</h6>
                <h5 class="fw-bold text-dark mb-1">{{ $cotizacion->cliente->nombre ?? $cotizacion->cliente_nombre ?? 'Cliente General / Contado' }}</h5>
                @if($cotizacion->cliente)
                <p class="mb-1 text-muted small"><strong>Cédula / RUC:</strong> {{ $cotizacion->cliente->cedula_ruc ?? 'N/A' }}</p>
                <p class="mb-0 text-muted small"><strong>Teléfono:</strong> {{ $cotizacion->cliente->telefono ?? 'N/A' }}</p>
                @endif
            </div>
            <div class="col-md-5 info-box">
                <h6 class="fw-bold text-uppercase text-muted small mb-2">Información Comercial</h6>
                <p class="mb-1 text-muted small"><strong>Vendedor:</strong> {{ $cotizacion->vendedor->name ?? 'N/A' }}</p>
                <p class="mb-1 text-muted small"><strong>Moneda:</strong> USD ($)</p>
                <p class="mb-0 text-muted small"><strong>Estado:</strong> Presupuesto Pendiente</p>
            </div>
        </div>

        {{-- TABLA DE PRODUCTOS --}}
        <div class="table-responsive mb-4">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">#</th>
                        <th style="width: 15%;">Cod. Barras</th>
                        <th style="width: 12%;">Referencia</th>
                        <th>Descripción</th>
                        <th style="width: 8%;" class="text-center">Cant</th>
                        <th style="width: 12%;" class="text-end">Precio Unit.</th>
                        <th style="width: 12%;" class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cotizacion->detalles as $idx => $d)
                    <tr>
                        <td class="text-center fw-bold">{{ $idx + 1 }}</td>
                        <td class="fw-bold">{{ $d->producto->codigo_barras ?? 'N/A' }}</td>
                        <td class="fw-bold">{{ $d->producto->codigo ?? 'N/A' }}</td>
                        <td class="fw-semibold">{{ $d->producto->nombre ?? 'Producto' }}</td>
                        <td class="text-center fw-bold">{{ $d->cantidad }}</td>
                        <td class="text-end">${{ number_format($d->precio_unitario, 2) }}</td>
                        <td class="text-end fw-bold">${{ number_format($d->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- RESUMEN FINANCIERO --}}
        <div class="row justify-content-end mb-5">
            <div class="col-md-4 totales-box">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-bold">${{ number_format($cotizacion->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">ITBMS (7%)</span>
                    <span class="fw-bold">${{ number_format($cotizacion->itbms, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between fs-4 fw-bold border-top pt-2">
                    <span>TOTAL:</span>
                    <span class="text-success">${{ number_format($cotizacion->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- PIE DE FIRMA FIJO --}}
        <div class="row mt-5 pt-4 text-center">
            <div class="col-6">
                <div class="firma-box">
                    <small class="text-muted d-block fw-semibold">Autorizado</small>
                </div>
            </div>
            <div class="col-6">
                <div class="firma-box">
                    <small class="text-muted d-block fw-semibold">Recibido</small>
                </div>
            </div>
        </div>

        {{-- NOTA FINAL --}}
        <div class="mt-4 text-center">
            <small class="text-muted">Precios sujetos a cambio sin previo aviso. Esta cotización tiene una validez de 30 días.</small>
        </div>
    </div>
</div>
</div>
</section>
</x-app-layout>
