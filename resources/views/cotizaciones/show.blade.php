<x-app-layout title="Formato de Cotización {{ $cotizacion->numero_factura }}">
    @push('styles')
    <style>
        @media print {
            .no-print, nav, footer, .crm-navbar { display: none !important; }
            body { background: white !important; color: black !important; }
            .card { border: none !important; box-shadow: none !important; }
            .quote-paper { padding: 0 !important; margin: 0 !important; width: 100% !important; }
        }
        .quote-paper { background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 40px; }
        .quote-header-brand { font-size: 1.8rem; font-weight: 800; color: #1e293b; }
        .quote-title-badge { background: #e0e7ff; color: #4338ca; padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 0.9rem; }

    </style>
    @endpush

    


<section id="">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Acciones Superiores (No Imprimible) -->
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

            <!-- Formato Imprimible de Cotización -->
            <div class="quote-paper">

                @php
                $sucursalesInfo = [
                    'Principal' => [
                        'logo' => '/public/assets/img/Logo_City.png',
                        'line1' => 'Tel. C. R. 2732-2931 / 2783-2945  Fax: 2783-2952',
                        'line2' => 'Tel. Panamá: 727-7247 / Fax: 727-6591',
                        'line3' => 'R.U.C. 1513069-1-650069  D.V. 77',
                    ],
                    'Outlet Regalon' => [
                        'logo' => '/public/assets/img/Logo_Outlet.png',
                        'line1' => 'Tel. C. R. 3211-1122 / 3333-4444  Fax: 5555-6666',
                        'line2' => 'Tel. Panamá: 800-1234 / Fax: 800-5678',
                        'line3' => 'R.U.C. 1234567-8-901234  D.V. 12',
                    ],
                    'Dolar Moll 1,2,3' => [
                        'logo' => '/public/assets/img/Logo_CentroDollar.jpeg',
                        'line1' => 'Tel. C. R. 7777-8888 / 9999-0000  Fax: 1111-2222',
                        'line2' => 'Tel. Panamá: 333-4444 / Fax: 333-5555',
                        'line3' => 'R.U.C. 9876543-2-210987  D.V. 34',
                    ],
                ];
                $suc = $sucursalesInfo[$cotizacion->punto_facturacion] ?? $sucursalesInfo['Principal'];
                @endphp

                <!-- Encabezado de la Empresa -->
                <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
                    <div>
                        <div class="quote-header-brand d-flex align-items-center gap-2">
                            <img src="{{ $suc['logo'] }}" alt="">
                        </div>
                        <p class="text-muted small mb-0 mt-1">{{ $suc['line1'] }}</p>
                        <p class="text-muted small mb-0">{{ $suc['line2'] }}</p>
                        <p class="text-muted small mb-0">{{ $suc['line3'] }}</p>
                    </div>
                    <div class="text-end">
                        <span class="quote-title-badge mb-2 d-inline-block">COTIZACIÓN DE COMPRA</span>
                        <h4 class="fw-bold text-dark mb-1">{{ $cotizacion->numero_factura }}</h4>
                        <p class="text-muted small mb-1"><strong>Fecha Emisión:</strong> {{ date('d/m/Y', strtotime($cotizacion->fecha_venta)) }}</p>
                        <p class="text-muted small mb-0"><strong>Validez:</strong> 15 días calendario</p>
                    </div>
                </div>

                <!-- Datos del Cliente y Vendedor -->
                <div class="row g-3 p-3 bg-light rounded-3 mb-4">
                    <div class="col-md-7 border-end">
                        <h6 class="fw-bold text-uppercase text-muted small mb-2">Datos del Cliente</h6>
                        <h5 class="fw-bold text-dark mb-1">{{ $cotizacion->cliente->nombre ?? ($cotizacion->cliente_nombre ?? 'Cliente General / Contado') }}</h5>
                        @if($cotizacion->cliente)
                            <p class="mb-1 text-muted small"><strong>Cédula / RUC:</strong> {{ $cotizacion->cliente->cedula_ruc ?? 'N/A' }}</p>
                            <p class="mb-1 text-muted small"><strong>Teléfono:</strong> {{ $cotizacion->cliente->telefono ?? 'N/A' }}</p>
                        @endif
                    </div>
                    <div class="col-md-5 ps-md-4">
                        <h6 class="fw-bold text-uppercase text-muted small mb-2">Información Comercial</h6>
                        <p class="mb-1 text-muted small"><strong>Moneda:</strong> USD ($)</p>
                        <p class="mb-0 text-muted small"><strong>Estado:</strong> Presupuesto Pendiente</p>
                    </div>
                </div>

                <!-- Tabla Detalla de Productos -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead style="background-color: #f1f5f9;">
                            <tr>
                                <th style="width: 50px;" class="text-center">#</th>
                                <th style="width: 120px;">Código</th>
                                <th>Descripción / Producto</th>
                                <th style="width: 80px;" class="text-center">Cant</th>
                                <th style="width: 130px;" class="text-end">Precio Unit.</th>
                                <th style="width: 130px;" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cotizacion->detalles as $idx => $d)
                                <tr>
                                    <td class="text-center text-muted fw-bold">{{ $idx + 1 }}</td>
                                    <td class="fw-bold text-secondary">{{ $d->producto->codigo ?? 'N/A' }}</td>
                                    <td class="fw-semibold text-dark">{{ $d->producto->nombre ?? 'Producto de Catálogo' }}</td>
                                    <td class="text-center fw-bold">{{ $d->cantidad }}</td>
                                    <td class="text-end">${{ number_format($d->precio_unitario, 2) }}</td>
                                    <td class="text-end fw-bold text-dark">${{ number_format($d->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Resumen Financiero de Cotizacion -->
                <div class="row justify-content-between align-items-start border-top pt-3">
                    <div class="col-md-7">
                       
                    </div>
                    <div class="col-md-5">
                        <div class="bg-light p-3 rounded-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-bold text-dark">${{ number_format($cotizacion->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">ITBMS (7%):</span>
                                <span class="fw-bold text-dark">${{ number_format($cotizacion->itbms, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fs-4 fw-bold text-dark border-top pt-2 mt-2">
                                <span>TOTAL:</span>
                                <span class="text-success">${{ number_format($cotizacion->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie de Firma Imprimible -->
                <div class="row mt-5 pt-4 text-center">
                    <div class="col-6">
                        <div class="border-top mx-auto w-75 pt-2">
                            <small class="text-muted d-block fw-semibold">Autorizado</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border-top mx-auto w-75 pt-2">
                            <small class="text-muted d-block fw-semibold">Recibido</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    
</x-app-layout>
