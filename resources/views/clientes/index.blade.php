<x-app-layout title="Clientes - FerrePlus">
    <div class="crud-page-header">
        <div class="crud-page-title">
            <div class="title-icon"><i class="bi bi-people"></i></div>
            <div>
                <h1>Directorio de Clientes</h1>
                <p>Gestión de clientes y tipos de descuento</p>
            </div>
        </div>
        <div class="crud-header-actions">
            <button type="button" class="btn-crud-new" data-bs-toggle="modal" data-bs-target="#modalCrearCliente">
                <i class="bi bi-plus-lg"></i> Nuevo Cliente
            </button>
        </div>
    </div>

    <div class="crud-toolbar">
        <form class="d-flex w-100" method="GET" action="{{ route('clientes.index') }}" style="gap:12px; align-items:center; flex-wrap:wrap; margin:0">
            <div class="crud-search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por código, nombre o cédula/RUC..." autofocus>
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Buscar</button>
        </form>
    </div>

    <div class="crud-table-card">
        <div style="overflow-x:auto;">
            <table class="crud-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Cédula/RUC</th>
                        <th>Tipo</th>
                        <th>Teléfono</th>
                        <th>Descuento</th>
                        <th>Estado</th>
                        <th style="text-align:right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cli)
                        <tr>
                            <td style="color:var(--crud-muted);font-size:.78rem;font-weight:700">{{ $cli->id }}</td>
                            <td class="fw-bold">{{ $cli->codigo }}</td>
                            <td class="fw-semibold">{{ $cli->nombre }}</td>
                            <td>{{ $cli->cedula_ruc ?? 'N/A' }}</td>
                            <td><span class="badge bg-info text-dark">{{ ucfirst($cli->tipo_cliente) }}</span></td>
                            <td>{{ $cli->telefono ?? 'N/A' }}</td>
                            <td>{{ number_format($cli->descuento_porcentaje, 2) }}%</td>
                            <td>
                                @if($cli->activo)
                                    <span class="status-badge status-active"><span class="status-dot" style="background:#10b981"></span> Activo</span>
                                @else
                                    <span class="status-badge status-inactive"><span class="status-dot" style="background:#dc2626"></span> Inactivo</span>
                                @endif
                            </td>
                            <td style="text-align:right">
                                <div class="crud-actions">
                                    <form method="POST" action="{{ route('clientes.destroy', $cli->id) }}" class="d-inline" onsubmit="return confirm('¿Eliminar cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-crud-action btn-delete" title="Eliminar"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">No hay clientes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $clientes->links() }}
    </div>

    <!-- Modal Crear Cliente -->
    <div class="modal fade" id="modalCrearCliente" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('clientes.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Nuevo Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Código (*)</label>
                            <input type="text" name="codigo" class="form-control" required value="CLI-{{ rand(1000, 9999) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre / Razón Social (*)</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Cédula / RUC</label>
                            <input type="text" name="cedula_ruc" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipo de Cliente</label>
                            <select name="tipo_cliente" class="form-select">
                                <option value="regular">Regular</option>
                                <option value="mayorista">Mayorista</option>
                                <option value="corporativo">Corporativo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Teléfono</label>
                            <input type="text" name="telefono" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary fw-bold">Guardar Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
