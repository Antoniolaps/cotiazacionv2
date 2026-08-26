<x-app-layout title="Reportes y Estadísticas">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Reportes Ejecutivos</h2>
            <p class="text-muted small">Estadísticas de ventas y productos más vendidos</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-primary text-white p-4">
                <h5 class="fw-bold mb-1">Ventas del Mes</h5>
                <h2 class="fw-bold my-2">${{ number_format($ventasMes, 2) }}</h2>
                <small class="opacity-75">Ventas pagadas en el mes actual</small>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-trophy me-2"></i> Top 10 Productos Más Vendidos</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Unidades Vendidas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProductos as $idx => $item)
                                <tr>
                                    <td class="fw-bold">{{ $idx + 1 }}</td>
                                    <td>{{ $item->nombre }}</td>
                                    <td><span class="badge bg-success fs-6">{{ $item->total_vendido }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">No hay datos suficientes para el reporte.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
