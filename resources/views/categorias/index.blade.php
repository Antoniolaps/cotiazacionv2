<x-app-layout title="Categorías - FerrePlus">
    <div class="crud-page-header">
        <div class="crud-page-title">
            <div class="title-icon"><i class="bi bi-tags"></i></div>
            <div>
                <h1>Categorías de Productos</h1>
                <p>Clasificación y administración del catálogo</p>
            </div>
        </div>
        <div class="crud-header-actions">
            <button type="button" class="btn-crud-new" data-bs-toggle="modal" data-bs-target="#modalCrearCategoria">
                <i class="bi bi-plus-lg"></i> Nueva Categoría
            </button>
        </div>
    </div>

    <div class="crud-table-card">
        <div style="overflow-x:auto;">
            <table class="crud-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th style="text-align:right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $cat)
                        <tr>
                            <td style="color:var(--crud-muted);font-size:.78rem;font-weight:700">{{ $cat->id }}</td>
                            <td class="fw-bold">{{ $cat->nombre }}</td>
                            <td>{{ $cat->descripcion ?? 'N/A' }}</td>
                            <td>
                                @if($cat->activo)
                                    <span class="status-badge status-active"><span class="status-dot" style="background:#10b981"></span> Activo</span>
                                @else
                                    <span class="status-badge status-inactive"><span class="status-dot" style="background:#dc2626"></span> Inactivo</span>
                                @endif
                            </td>
                            <td style="text-align:right">
                                <div class="crud-actions">
                                    <form method="POST" action="{{ route('categorias.destroy', $cat->id) }}" class="d-inline" onsubmit="return confirm('¿Eliminar categoría?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-crud-action btn-delete" title="Eliminar"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">No hay categorías registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $categorias->links() }}
    </div>

    <!-- Modal Crear Categoria -->
    <div class="modal fade" id="modalCrearCategoria" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('categorias.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Nueva Categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre (*)</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
