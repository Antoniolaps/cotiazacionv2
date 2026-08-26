<x-app-layout title="Registro de Pagos">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Registro de Pagos y Pasarela</h2>
            <p class="text-muted small">Historial de transacciones de pago recibidas</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID Pago</th>
                        <th>N° Factura</th>
                        <th>Fecha</th>
                        <th>Método de Pago</th>
                        <th>Monto</th>
                        <th>Recibido / Cambio</th>
                        <th>Estado</th>
                        <th>Cajero/Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pagos as $p)
                        <tr>
                            <td>#{{ $p->id }}</td>
                            <td class="fw-bold">{{ $p->venta->numero_factura ?? 'N/A' }}</td>
                            <td>{{ $p->fecha_pago }}</td>
                            <td><span class="badge bg-secondary text-uppercase">{{ str_replace('_', ' ', $p->metodo_pago) }}</span></td>
                            <td class="fw-bold text-success">${{ number_format($p->monto, 2) }}</td>
                            <td>${{ number_format($p->monto_recibido ?? $p->monto, 2) }} / <small class="text-muted">${{ number_format($p->cambio ?? 0, 2) }}</small></td>
                            <td><x-badge :status="$p->estado" /></td>
                            <td>{{ $p->usuario->nombre ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay transacciones de pago registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $pagos->links() }}
        </div>
    </div>
</x-app-layout>
