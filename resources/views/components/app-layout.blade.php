<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'scheCONtroll' }}</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
      :root {
        --crud-accent:   #6366f1;
        --crud-success:  #10b981;
        --crud-warning:  #f59e0b;
        --crud-danger:   #ef4444;
        --crud-info:     #06b6d4;
        --crud-border:   #e2e8f0;
        --crud-bg:       #f8fafc;
        --crud-card:     #ffffff;
        --crud-text:     #0f172a;
        --crud-muted:    #64748b;
        --crud-radius:   12px;
        --crud-shadow:   0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.06);
      }

      body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; margin: 0; color: #0f172a; }
      .crm-navbar { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; height: 60px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); width: 100%; }
      .crm-nav-links { display: flex; height: 100%; align-items: center; overflow-x: auto; scrollbar-width: none; }
      .crm-nav-links::-webkit-scrollbar { display: none; }
      .crm-nav-link { text-decoration: none; color: #4a5568; font-size: 0.875rem; font-weight: 500; padding: 0 16px; display: flex; align-items: center; height: 100%; border-bottom: 3px solid transparent; white-space: nowrap; transition: color 0.2s; }
      .crm-nav-link:hover:not(.disabled) { color: #3182ce; }
      .crm-nav-link.active { color: #3182ce; border-bottom-color: #3182ce; }
      .crm-nav-link.disabled { color: #cbd5e0; cursor: not-allowed; }
      .crm-nav-brand { font-weight: 700; color: #2d3748; font-size: 1.1rem; display: flex; align-items: center; margin-right: 20px; text-decoration: none; }
      .crm-nav-brand i { color: #3182ce; margin-right: 8px; font-size: 1.3rem; }
      .crm-nav-right { display: flex; align-items: center; gap: 15px; }
      .crm-icon-btn { color: #718096; background: none; border: none; font-size: 1.1rem; cursor: pointer; transition: color 0.2s; text-decoration: none; }
      .crm-icon-btn:hover { color: #2d3748; }
      .user-badge { font-size: 0.75rem; background: #ebf8ff; color: #3182ce; padding: 2px 8px; border-radius: 12px; font-weight: 600; margin-left: 8px; text-transform: uppercase; }

      /* CRUD Panel Styling */
      .crud-page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
      .crud-page-title { display: flex; align-items: center; gap: 12px; }
      .crud-page-title .title-icon { width: 44px; height: 44px; border-radius: 10px; background: linear-gradient(135deg, #6366f1, #4f46e5); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; box-shadow: 0 4px 12px rgba(99,102,241,.3); }
      .crud-page-title h1 { font-size: 1.4rem; font-weight: 800; color: var(--crud-text); margin: 0; }
      .crud-page-title p { color: var(--crud-muted); font-size: .85rem; margin: 0; }
      .btn-crud-new { display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px; background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; border-radius: 8px; font-weight: 700; font-size: .875rem; text-decoration: none; transition: all .2s; box-shadow: 0 4px 12px rgba(99,102,241,.25); border: none; }
      .btn-crud-new:hover { color: white; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,.4); }
      .crud-toolbar { background: var(--crud-card); border: 1px solid var(--crud-border); border-radius: var(--crud-radius); padding: 14px 20px; margin-bottom: 20px; display: flex; gap: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap; box-shadow: var(--crud-shadow); }
      .crud-search-wrap { position: relative; flex: 1; min-width: 200px; }
      .crud-search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--crud-muted); }
      .crud-search-wrap input { width: 100%; padding: 8px 12px 8px 36px; border: 1.5px solid var(--crud-border); border-radius: 8px; font-size: .875rem; background: var(--crud-bg); color: var(--crud-text); }
      .crud-search-wrap input:focus { outline: none; border-color: var(--crud-accent); }
      .crud-table-card { background: var(--crud-card); border: 1px solid var(--crud-border); border-radius: var(--crud-radius); overflow: hidden; box-shadow: var(--crud-shadow); margin-bottom: 20px; }
      .crud-table { width: 100%; border-collapse: collapse; }
      .crud-table thead { background: var(--crud-bg); border-bottom: 2px solid var(--crud-border); }
      .crud-table thead th { padding: 12px 16px; font-size: .72rem; font-weight: 700; color: var(--crud-muted); text-transform: uppercase; letter-spacing: .06em; }
      .crud-table tbody tr { border-bottom: 1px solid #f1f5f9; color: #0f172a; }
      .crud-table tbody tr:hover { background: #f8fafc; }
      .crud-table tbody td { padding: 14px 16px; font-size: .875rem; vertical-align: middle; color: #0f172a; }
      .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700; }
      .status-active   { background: rgba(16,185,129,.1);  color: #059669; }
      .status-inactive { background: rgba(239,68,68,.1); color: #dc2626; }
      .status-dot { width: 6px; height: 6px; border-radius: 50%; }
      .currency-val { font-weight: 600; color: #0f172a; }
      .currency-sym { color: var(--crud-muted); font-size: 0.8em; margin-right: 2px; }
    </style>
    @stack('styles')
</head>
<body>
    @auth
        @php
            $user = Auth::user();
            $role = strtolower($user->role->nombre ?? 'invitado');
            $all_modules = [
                ['Dashboard', route('dashboard'), ['admin','vendedor','almacen']],
                ['Cotizaciones', route('cotizaciones.index'), ['admin','vendedor','almacen']],
                ['Productos', route('productos.index'), ['admin','vendedor','almacen','cliente_consulta']],
                ['Categorías', route('categorias.index'), ['admin','almacen']],
                ['Inventario', route('inventario.index'), ['admin','almacen']],
                ['Pagos', route('pagos.index'), ['admin','vendedor']],
                ['Clientes', route('clientes.index'), ['admin','vendedor']],
                ['Proveedores', route('proveedores.index'), ['admin','almacen']],
                ['Compras', route('compras.index'), ['admin','almacen']],
                ['Reportes', route('reportes.index'), ['admin']],
                ['Logs', route('logs.index'), ['admin']],
                ['Usuarios', route('usuarios.index'), ['admin']],
                ['Roles', route('roles.index'), ['admin']]
            ];
        @endphp

        <div class="crm-navbar">
            <div class="d-flex align-items-center" style="flex: 1; overflow: hidden;">
                <a class="crm-nav-brand" href="{{ route('dashboard') }}">
                    <i class="bi bi-box-seam"></i> FerrePlus
                </a>
                <div class="crm-nav-links">
                    @foreach ($all_modules as $mod)
                        @php
                            $has_access = in_array($role, $mod[2]) || $role === 'admin';
                            $isActive = request()->url() === $mod[1] || str_contains(request()->url(), Str::before($mod[1], '.'));
                        @endphp
                        <a href="{{ $has_access ? $mod[1] : '#' }}" 
                           class="crm-nav-link {{ $isActive ? 'active' : '' }} {{ !$has_access ? 'disabled' : '' }}"
                           {!! !$has_access ? 'title="No tienes permiso para ver este módulo" onclick="return false;"' : '' !!}>
                           {{ $mod[0] }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="crm-nav-right">
                <div class="d-flex align-items-center ms-3 ps-3 border-start">
                    <span class="text-dark fw-medium" style="font-size: 0.875rem;">
                        {{ $user->nombre }}
                    </span>
                    <span class="user-badge">{{ $user->role->nombre ?? 'Usuario' }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline ms-3">
                        @csrf
                        <button type="submit" class="crm-icon-btn text-danger" title="Cerrar Sesión">
                            <i class="bi bi-box-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div style="min-height: calc(100vh - 60px); width: 100%;">
            <main class="p-4 w-100 mx-auto" style="max-width: 1400px;">
                @if(session('success'))
                    <div class="alert alert-success shadow-sm alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger shadow-sm alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    @else
        <main class="container py-5">
            {{ $slot }}
        </main>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
