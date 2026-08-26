<x-app-layout title="Historial de Ventas">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Historial de Facturas y Ventas</h2>
            <p class="text-muted small">Consulta de transacciones y estados de facturación</p>
        </div>
        <a href="{{ route('pos.terminal') }}" class="btn btn-success fw-bold">
            <i class="bi bi-calculator me-1"></i> Nueva Venta (POS)
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>N° Factura</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Subtotal</th>
                        <th>ITBMS</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $v)
                        <tr>
                            <td class="fw-bold"><a href="{{ route('ventas.show', $v->id) }}" class="text-decoration-none">{{ $v->numero_factura }}</a></td>
                            <td>{{ $v->fecha_venta }}</td>
                            <td>{{ $v->cliente->nombre ?? 'Cliente General' }}</td>
                            <td>{{ $v->vendedor->nombre ?? 'N/A' }}</td>
                            <td>${{ number_format($v->subtotal, 2) }}</td>
                            <td>${{ number_format($v->itbms, 2) }}</td>
                            <td class="fw-bold text-success">${{ number_format($v->total, 2) }}</td>
                            <td><x-badge :status="$v->estado" /></td>
                            <td class="text-end">
                                <a href="{{ route('ventas.show', $v->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver Detalle</a>
                                @if($v->estado !== 'anulada')
                                    <form method="POST" action="{{ route('ventas.anular', $v->id) }}" class="d-inline" onsubmit="return confirm('¿Anular esta factura?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i> Anular</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No hay ventas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $ventas->links() }}
        </div>
    </div>
</x-app-layout>
