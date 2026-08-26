<x-app-layout title="Dashboard - FerrePlus">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-bold">Dashboard</h2>
            <p class="text-muted small mb-0">Resumen ejecutivo y métricas del sistema</p>
        </div>
        <div id="quick-actions-container" class="d-flex gap-2">
            @php $role = strtolower(Auth::user()->role->nombre ?? ''); @endphp
            @if(in_array($role, ['admin', 'vendedor']))
                <a href="{{ route('pos.terminal') }}" class="btn btn-outline-success btn-sm fw-bold">
                    <i class="bi bi-cash-coin me-1"></i> Registrar Venta (POS)
                </a>
                <a href="{{ route('cotizaciones.create') }}" class="btn btn-outline-primary btn-sm fw-bold">
                    <i class="bi bi-file-earmark-plus me-1"></i> Nueva Cotización
                </a>
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-sm fw-bold">
                    <i class="bi bi-person-plus me-1"></i> Nuevo Cliente
                </a>
            @endif
            @if(in_array($role, ['admin', 'almacen']))
                <a href="{{ route('inventario.index') }}" class="btn btn-outline-info btn-sm fw-bold">
                    <i class="bi bi-clipboard-check me-1"></i> Ajuste de Inventario
                </a>
            @endif
        </div>
    </div>

    <!-- Stats Panel Widgets -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3 border-0 shadow-sm rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Total Ventas Acumuladas</div>
                        <div class="h4 mb-0 fw-bold text-dark">${{ number_format($totalVentas, 2) }}</div>
                    </div>
                    <i class="bi bi-cash-coin text-success fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 border-0 shadow-sm rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Ventas de Hoy</div>
                        <div class="h4 mb-0 fw-bold text-dark">${{ number_format($ventasHoy, 2) }}</div>
                    </div>
                    <i class="bi bi-receipt text-primary fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 border-0 shadow-sm rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Productos Activos</div>
                        <div class="h4 mb-0 fw-bold text-dark">{{ $totalProductos }}</div>
                    </div>
                    <i class="bi bi-box text-info fs-1"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 border-0 shadow-sm rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Clientes Registrados</div>
                        <div class="h4 mb-0 fw-bold text-dark">{{ $totalClientes }}</div>
                    </div>
                    <i class="bi bi-people text-warning fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Tabla de Ventas Recientes -->
        <div class="col-md-7">
            <div class="crud-table-card">
                <div class="p-3 border-bottom bg-white d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history me-2"></i> Ventas Recientes</h5>
                    <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-link text-decoration-none">Ver Todas</a>
                </div>
                <div class="table-responsive">
                    <table class="crud-table mb-0">
                        <thead>
                            <tr>
                                <th>Factura</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimasVentas as $v)
                                <tr>
                                    <td class="fw-bold"><a href="{{ route('ventas.show', $v->id) }}" class="text-decoration-none">{{ $v->numero_factura }}</a></td>
                                    <td>{{ $v->cliente->nombre ?? 'General' }}</td>
                                    <td>{{ $v->fecha_venta }}</td>
                                    <td><x-badge :status="$v->estado" /></td>
                                    <td class="text-end fw-bold text-success">${{ number_format($v->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No hay ventas recientes</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tabla de Alerta de Bajo Stock -->
        <div class="col-md-5">
            <div class="crud-table-card">
                <div class="p-3 border-bottom bg-white d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-danger"><i class="bi bi-exclamation-triangle me-2"></i> Productos con Bajo Stock</h5>
                    <a href="{{ route('inventario.index') }}" class="btn btn-sm btn-link text-decoration-none">Inventario</a>
                </div>
                <div class="table-responsive">
                    <table class="crud-table mb-0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Stock</th>
                                <th>Mínimo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bajoStock as $inv)
                                <tr>
                                    <td class="fw-bold">{{ $inv->producto->codigo ?? 'N/A' }}</td>
                                    <td>{{ $inv->producto->nombre ?? 'N/A' }}</td>
                                    <td><span class="badge bg-danger fs-6">{{ $inv->stock_actual }}</span></td>
                                    <td>{{ $inv->producto->stock_minimo ?? 0 }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Sin alertas de stock</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
