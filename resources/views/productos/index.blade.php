<x-app-layout title="Productos - FerrePlus">
    <div class="crud-page-header">
        <div class="crud-page-title">
            <div class="title-icon"><i class="bi bi-box"></i></div>
            <div>
                <h1>Catálogo de Productos</h1>
                <p>Gestión y administración del catálogo de inventario</p>
            </div>
        </div>
        <div class="crud-header-actions">
            <a href="{{ route('productos.create') }}" class="btn-crud-new">
                <i class="bi bi-plus-lg"></i> Nuevo Producto
            </a>
        </div>
    </div>

    <div class="crud-toolbar">
        <form class="d-flex w-100" method="GET" action="{{ route('productos.index') }}" style="gap:12px; align-items:center; flex-wrap:wrap; margin:0">
            <div class="crud-search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por código, código de barras o nombre..." autofocus>
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
                        <th>Categoría</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Stock Actual</th>
                        <th>Estado</th>
                        <th style="text-align:right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $p)
                        <tr>
                            <td style="color:#64748b;font-size:.78rem;font-weight:700">{{ $p->id }}</td>
                            <td style="color:#334155; font-weight:700;">{{ $p->codigo }}</td>
                            <td style="color:#0f172a; font-weight:600;">{{ $p->nombre }}</td>
                            <td><span class="badge bg-secondary px-2 py-1">{{ $p->categoria->nombre ?? 'N/A' }}</span></td>
                            <td><span class="currency-val" style="color:#475569;"><span class="currency-sym">$</span>{{ number_format($p->precio_compra, 2) }}</span></td>
                            <td><span class="currency-val text-success" style="font-weight:700;"><span class="currency-sym">$</span>{{ number_format($p->precio_venta, 2) }}</span></td>
                            <td>
                                <span class="badge {{ ($p->inventario->stock_actual ?? 0) <= $p->stock_minimo ? 'bg-danger' : 'bg-primary' }} fs-6 px-2 py-1">
                                    {{ $p->inventario->stock_actual ?? 0 }} {{ $p->unidad_medida }}
                                </span>
                            </td>
                            <td>
                                @if($p->activo)
                                    <span class="status-badge status-active"><span class="status-dot" style="background:#10b981"></span> Activo</span>
                                @else
                                    <span class="status-badge status-inactive"><span class="status-dot" style="background:#dc2626"></span> Inactivo</span>
                                @endif
                            </td>
                            <td style="text-align:right">
                                <div class="crud-actions">
                                    <a href="{{ route('productos.edit', $p->id) }}" class="btn-crud-action btn-edit" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('productos.destroy', $p->id) }}" class="d-inline" onsubmit="return confirm('¿Eliminar producto?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-crud-action btn-delete" title="Eliminar"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">No se encontraron productos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $productos->links() }}
    </div>
</x-app-layout>
