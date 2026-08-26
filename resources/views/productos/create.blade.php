<x-app-layout title="Nuevo Producto">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Crear Nuevo Producto</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('productos.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Código  (*)</label>
                                <input type="text" name="codigo" class="form-control" required value="{{ old('codigo') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Código de Barras</label>
                                <input type="text" name="codigo_barras" class="form-control" value="{{ old('codigo_barras') }}">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Nombre del Producto (*)</label>
                                <input type="text" name="nombre" class="form-control" required value="{{ old('nombre') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Categoría</label>
                                <select name="categoria_id" class="form-select">
                                    <option value="">-- Seleccionar Categoría --</option>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Proveedor</label>
                                <select name="proveedor_id" class="form-select">
                                    <option value="">-- Seleccionar Proveedor --</option>
                                    @foreach($proveedores as $prov)
                                        <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Precio de Compra ($)</label>
                                <input type="number" step="0.01" name="precio_compra" class="form-control" required value="{{ old('precio_compra', 0) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Precio de Venta ($)</label>
                                <input type="number" step="0.01" name="precio_venta" class="form-control" required value="{{ old('precio_venta', 0) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stock Mínimo</label>
                                <input type="number" name="stock_minimo" class="form-control" value="{{ old('stock_minimo', 5) }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Unidad de Medida</label>
                                <input type="text" name="unidad_medida" class="form-control" value="{{ old('unidad_medida', 'pza') }}">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Guardar Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
