<x-app-layout title="Proveedores">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Directorio de Proveedores</h2>
            <p class="text-muted small">Proveedores para órdenes de compra y suministros</p>
        </div>
        <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalCrearProveedor">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Proveedor
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>RUC</th>
                        <th>Tipo</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proveedores as $prov)
                        <tr>
                            <td class="fw-bold">{{ $prov->nombre }}</td>
                            <td>{{ $prov->ruc ?? 'N/A' }}</td>
                            <td><span class="badge bg-info text-dark">{{ ucfirst($prov->tipo_proveedor) }}</span></td>
                            <td>{{ $prov->contacto ?? 'N/A' }}</td>
                            <td>{{ $prov->telefono ?? 'N/A' }}</td>
                            <td>{{ $prov->email ?? 'N/A' }}</td>
                            <td><x-badge :status="$prov->activo ? 'activo' : 'inactivo'" /></td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('proveedores.destroy', $prov->id) }}" class="d-inline" onsubmit="return confirm('¿Eliminar este proveedor?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay proveedores registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear Proveedor -->
    <div class="modal fade" id="modalCrearProveedor" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('proveedores.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Nuevo Proveedor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre / Empresa (*)</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">RUC</label>
                            <input type="text" name="ruc" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipo de Proveedor</label>
                            <select name="tipo_proveedor" class="form-select">
                                <option value="distribuidor">Distribuidor</option>
                                <option value="fabricante">Fabricante</option>
                                <option value="importador">Importador</option>
                                <option value="mayorista">Mayorista</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre de Contacto</label>
                            <input type="text" name="contacto" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold">Guardar Proveedor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
