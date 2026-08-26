<x-app-layout title="Movimientos de Inventario">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Historial de Movimientos de Inventario</h2>
            <p class="text-muted small">Auditoría de entradas, salidas y ajustes de stock</p>
        </div>
        <a href="{{ route('inventario.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a Inventario
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Tipo Movimiento</th>
                        <th>Cantidad</th>
                        <th>Stock Anterior</th>
                        <th>Stock Nuevo</th>
                        <th>Usuario</th>
                        <th>Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimientos as $m)
                        <tr>
                            <td>{{ $m->fecha_movimiento }}</td>
                            <td class="fw-bold">{{ $m->producto->nombre ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $m->tipo_movimiento === 'entrada' ? 'bg-success' : ($m->tipo_movimiento === 'salida' ? 'bg-danger' : 'bg-info text-dark') }}">
                                    {{ strtoupper($m->tipo_movimiento) }}
                                </span>
                            </td>
                            <td class="fw-bold">{{ $m->cantidad }}</td>
                            <td>{{ $m->stock_anterior }}</td>
                            <td class="fw-bold text-primary">{{ $m->stock_nuevo }}</td>
                            <td>{{ $m->usuario->nombre ?? 'Sistema' }}</td>
                            <td>{{ $m->descripcion ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay movimientos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $movimientos->links() }}
        </div>
    </div>
</x-app-layout>
