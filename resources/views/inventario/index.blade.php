<x-app-layout title="Inventario - FerrePlus">
    <div class="crud-page-header">
        <div class="crud-page-title">
            <div class="title-icon"><i class="bi bi-clipboard-data"></i></div>
            <div>
                <h1>Control de Inventario y Stock</h1>
                <p>Monitoreo de existencias y ajustes manuales de almacén</p>
            </div>
        </div>
        <div class="crud-header-actions d-flex gap-2">
            <a href="{{ route('inventario.movimientos') }}" class="btn btn-outline-secondary font-weight-bold">
                <i class="bi bi-list-check me-1"></i> Historial de Movimientos
            </a>
            <button type="button" class="btn-crud-new" data-bs-toggle="modal" data-bs-target="#modalAjustarStock">
                <i class="bi bi-arrow-down-up me-1"></i> Ajustar Stock
            </button>
        </div>
    </div>

    <div class="crud-table-card">
        <div style="overflow-x:auto;">
            <table class="crud-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <th>Stock Reservado</th>
                        <th>Última Actualización</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventario as $inv)
                        <tr>
                            <td style="color:var(--crud-muted);font-size:.78rem;font-weight:700">{{ $inv->id }}</td>
                            <td class="fw-bold">{{ $inv->producto->nombre ?? 'N/A' }} <small class="text-muted">({{ $inv->producto->codigo ?? '' }})</small></td>
                            <td><span class="badge bg-secondary">{{ $inv->producto->categoria->nombre ?? 'N/A' }}</span></td>
                            <td>
                                <span class="badge {{ $inv->stock_actual <= ($inv->producto->stock_minimo ?? 0) ? 'bg-danger' : 'bg-success' }} fs-6">
                                    {{ $inv->stock_actual }} {{ $inv->producto->unidad_medida ?? 'pza' }}
                                </span>
                            </td>
                            <td>{{ $inv->stock_reservado }}</td>
                            <td>{{ $inv->ultima_actualizacion }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">No hay registros de inventario.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $inventario->links() }}
    </div>

    <!-- Modal Ajustar Stock -->
    <div class="modal fade" id="modalAjustarStock" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('inventario.ajustar') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Ajustar Stock Manual</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Producto (*)</label>
                            <select name="producto_id" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                @foreach($productos as $p)
                                    <option value="{{ $p->id }}">{{ $p->nombre }} (Actual: {{ $p->inventario->stock_actual ?? 0 }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nuevo Stock Total (*)</label>
                            <input type="number" name="stock_nuevo" class="form-control" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipo de Movimiento</label>
                            <select name="tipo_movimiento" class="form-select">
                                <option value="ajuste">Ajuste Manual</option>
                                <option value="entrada">Entrada</option>
                                <option value="salida">Salida</option>
                                <option value="devolucion">Devolución</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Motivo / Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2" placeholder="Ej: Conteo físico mensual"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold">Guardar Ajuste</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
