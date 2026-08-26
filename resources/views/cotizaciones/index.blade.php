<x-app-layout title="Cotizaciones - FerrePlus">
    <div class="crud-page-header">
        <div class="crud-page-title">
            <div class="title-icon"><i class="bi bi-file-earmark-text"></i></div>
            <div>
                <h1>Gestión de Cotizaciones</h1>
                <p>Presupuestos preliminares y formato de cotización a clientes</p>
            </div>
        </div>
        <div class="crud-header-actions">
            <a href="{{ route('cotizaciones.create') }}" class="btn-crud-new">
                <i class="bi bi-plus-lg"></i> Nueva Cotización
            </a>
        </div>
    </div>

    <div class="crud-toolbar">
        <form class="d-flex w-100" method="GET" action="{{ route('cotizaciones.index') }}" style="gap:12px; align-items:center; flex-wrap:wrap; margin:0">
            <div class="crud-search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por N° de cotización..." autofocus>
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
                        <th>N° Cotización</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Subtotal</th>
                        <th>ITBMS (7%)</th>
                        <th class="text-end">Total</th>
                        <th style="text-align:right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cotizaciones as $c)
                        <tr>
                            <td style="color:#64748b;font-size:.78rem;font-weight:700">{{ $c->id }}</td>
                            <td class="fw-bold"><a href="{{ route('cotizaciones.show', $c->id) }}" class="text-decoration-none text-primary">{{ $c->numero_factura }}</a></td>
                            <td>{{ $c->fecha_venta }}</td>
                            <td class="fw-semibold">{{ $c->cliente->nombre ?? 'Cliente General' }}</td>
                            <td><span class="currency-val"><span class="currency-sym">$</span>{{ number_format($c->subtotal, 2) }}</span></td>
                            <td><span class="currency-val"><span class="currency-sym">$</span>{{ number_format($c->itbms, 2) }}</span></td>
                            <td class="text-end fw-bold text-success"><span class="currency-val"><span class="currency-sym">$</span>{{ number_format($c->total, 2) }}</span></td>
                            <td style="text-align:right">
                                <div class="crud-actions">
                                    <a href="{{ route('cotizaciones.show', $c->id) }}" class="btn-crud-action btn-view" title="Ver / Imprimir Formato"><i class="bi bi-eye"></i></a>
                                    <form method="POST" action="{{ route('cotizaciones.convertir', $c->id) }}" class="d-inline" onsubmit="return confirm('¿Convertir esta cotización en Factura/Venta formal?');">
                                        @csrf
                                        <button type="submit" class="btn-crud-action btn-edit" title="Convertir a Venta"><i class="bi bi-cart-check"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">No se encontraron cotizaciones.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $cotizaciones->links() }}
    </div>
</x-app-layout>
