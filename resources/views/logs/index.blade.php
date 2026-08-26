<x-app-layout title="Logs de Actividad">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Logs de Actividad del Sistema</h2>
            <p class="text-muted small">Auditoría completa de operaciones CUD y logins</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Tabla Afectada</th>
                        <th>ID Registro</th>
                        <th>Dirección IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->fecha }}</td>
                            <td class="fw-bold">{{ $log->usuario->nombre ?? 'Sistema/Guest' }}</td>
                            <td>
                                <span class="badge {{ $log->accion === 'INSERT' ? 'bg-success' : ($log->accion === 'UPDATE' ? 'bg-warning text-dark' : ($log->accion === 'DELETE' ? 'bg-danger' : 'bg-info text-dark')) }}">
                                    {{ $log->accion }}
                                </span>
                            </td>
                            <td class="fw-semibold">{{ $log->tabla_afectada }}</td>
                            <td>{{ $log->registro_id ?? '-' }}</td>
                            <td><code>{{ $log->ip_address }}</code></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No hay logs registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>
