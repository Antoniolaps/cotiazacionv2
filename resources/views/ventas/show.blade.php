<x-app-layout title="Detalle de Factura {{ $venta->numero_factura }}">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card border-0 shadow-lg rounded-3 p-4">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <div>
                        <h3 class="fw-bold mb-0">scheCONtroll POS</h3>
                        <p class="text-muted small mb-0">Factura de Venta #{{ $venta->numero_factura }}</p>
                    </div>
                    <div class="text-end">
                        <x-badge :status="$venta->estado" />
                        <p class="text-muted small mt-1 mb-0">Fecha: {{ $venta->fecha_venta }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-6">
                        <h6 class="fw-bold text-muted">Datos del Cliente:</h6>
                        <p class="mb-1"><strong>Nombre:</strong> {{ $venta->cliente->nombre ?? 'Cliente General' }}</p>
                        <p class="mb-1"><strong>Cédula/RUC:</strong> {{ $venta->cliente->cedula_ruc ?? 'N/A' }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <h6 class="fw-bold text-muted">Vendedor:</h6>
                        <p class="mb-1"><strong>Nombre:</strong> {{ $venta->vendedor->nombre ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venta->detalles as $d)
                                <tr>
                                    <td>{{ $d->producto->nombre ?? 'Producto Eliminado' }}</td>
                                    <td>{{ $d->cantidad }}</td>
                                    <td>${{ number_format($d->precio_unitario, 2) }}</td>
                                    <td class="text-end fw-bold">${{ number_format($d->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Subtotal:</span>
                            <span class="fw-semibold">${{ number_format($venta->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>ITBMS (7%):</span>
                            <span class="fw-semibold">${{ number_format($venta->itbms, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between fs-4 fw-bold text-dark border-top pt-2">
                            <span>TOTAL:</span>
                            <span class="text-success">${{ number_format($venta->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top text-end">
                    <a href="{{ route('ventas.index') }}" class="btn btn-secondary me-2">Volver a Ventas</a>
                    <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer me-1"></i> Imprimir Factura</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
