<x-app-layout title="Roles del Sistema">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Roles y Permisos</h2>
            <p class="text-muted small">Configuración de perfiles de usuario</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Rol</th>
                        <th>Descripción</th>
                        <th>Usuarios Asignados</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $r)
                        <tr>
                            <td class="fw-bold text-uppercase">{{ $r->nombre }}</td>
                            <td>{{ $r->descripcion ?? 'N/A' }}</td>
                            <td><span class="badge bg-secondary fs-6">{{ $r->usuarios_count }}</span></td>
                            <td><x-badge :status="$r->activo ? 'activo' : 'inactivo'" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
