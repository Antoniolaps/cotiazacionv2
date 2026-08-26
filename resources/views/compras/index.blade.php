<x-app-layout title="Órdenes de Compra">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Órdenes de Compra y Entradas de Mercancía</h2>
            <p class="text-muted small">Gestión de abastecimiento y facturas de proveedores</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>N° Orden</th>
                        <th>N° Factura Proveedor</th>
                        <th>Proveedor</th>
                        <th>Fecha Orden</th>
                        <th>Total</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compras as $c)
                        <tr>
                            <td class="fw-bold">{{ $c->numero_orden }}</td>
                            <td>{{ $c->numero_factura }}</td>
                            <td>{{ $c->proveedor->nombre ?? 'N/A' }}</td>
                            <td>{{ $c->fecha_orden }}</td>
                            <td class="fw-bold text-success">${{ number_format($c->total, 2) }}</td>
                            <td><x-badge :status="$c->estado" /></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay órdenes de compra registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $compras->links() }}
        </div>
    </div>
</x-app-layout>
