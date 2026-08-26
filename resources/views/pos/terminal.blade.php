<x-app-layout title="Terminal POS">
    @push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
    @endpush

    <div class="row g-3">
        <!-- Panel Izquierdo: Buscador y Catalogo de Productos -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3">
                    <div class="row g-2">
                        <div class="col-md-7">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                                <input type="text" id="search-input" class="form-control" placeholder="Buscar por nombre, código o código de barras (Enter)" autofocus>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <select id="categoria-select" class="form-select">
                                <option value="0">-- Todas las categorías --</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3" style="max-height: 600px; overflow-y: auto;">
                    <div id="product-grid" class="row g-3">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-upc-scan fs-1"></i>
                            <p class="mt-2">Utilice el buscador o escanée un código de barras...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Derecho: Carrito de Compras y Cobro -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-cart-fill me-2"></i> Carrito de Venta</h5>
                    <button type="button" id="btn-clear-cart" class="btn btn-outline-light btn-sm"><i class="bi bi-trash me-1"></i> Limpiar</button>
                </div>

                <div class="card-body p-3 d-flex flex-column">
                    <!-- Cliente -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Cliente</label>
                        <select id="cliente-select" class="form-select form-select-sm">
                            @foreach($clientes as $cli)
                                <option value="{{ $cli->id }}">{{ $cli->nombre }} ({{ $cli->codigo }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tabla de Productos del Carrito -->
                    <div class="table-responsive flex-grow-1" style="min-height: 220px; max-height: 300px; overflow-y: auto;">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th style="width: 80px;">Cant</th>
                                    <th>Precio</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cart-items">
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">El carrito está vacío</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totales -->
                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Subtotal:</span>
                            <span id="cart-subtotal" class="fw-semibold text-dark">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">ITBMS (7%):</span>
                            <span id="cart-itbms" class="fw-semibold text-dark">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between fs-4 fw-bold text-dark">
                            <span>TOTAL:</span>
                            <span id="cart-total" class="text-success">$0.00</span>
                        </div>

                        <!-- Metodo de Pago y Cobro -->
                        <div class="mb-2">
                            <label class="form-label fw-semibold small text-dark">Método de Pago</label>
                            <select id="metodo-pago" class="form-select">
                                <option value="efectivo">Efectivo</option>
                                <option value="tarjeta_credito">Tarjeta de Crédito</option>
                                <option value="tarjeta_debito">Tarjeta de Débito</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="yappy">Yappy</option>
                                <option value="nequi">Nequi</option>
                            </select>
                        </div>

                        <div id="monto-recibido-container" class="mb-3">
                            <label class="form-label fw-semibold small text-dark">Monto Recibido ($)</label>
                            <input type="number" step="0.01" id="monto-recibido" class="form-control" placeholder="0.00">
                            <div class="d-flex justify-content-between mt-1 text-muted small">
                                <span>Cambio:</span>
                                <strong id="monto-cambio" class="text-primary">$0.00</strong>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" id="btn-completar-venta" class="btn btn-success py-3 fw-bold fs-5 shadow">
                                <i class="bi bi-check-circle-fill me-2"></i> COMPLETAR VENTA
                            </button>
                            <button type="button" id="btn-generar-cotizacion" class="btn btn-outline-primary py-2 fw-bold">
                                <i class="bi bi-file-earmark-text me-2"></i> GUARDAR COMO COTIZACIÓN
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/components/PosTerminal.js') }}"></script>
    @endpush
</x-app-layout>
