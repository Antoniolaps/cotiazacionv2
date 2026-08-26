<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>POS Terminal — FerrePlus</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= asset('css/pos.css') ?>">

    <style>
        /* ============================================================
           RESET + TOKENS
        ============================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:         #0f1117;
            --bg2:        #1a1d27;
            --bg3:        #242736;
            --border:     #2e3348;
            --accent:     #6366f1;
            --accent-h:   #818cf8;
            --success:    #10b981;
            --warning:    #f59e0b;
            --danger:     #ef4444;
            --text:       #e2e8f0;
            --text-muted: #64748b;
            --radius:     10px;
            --shadow:     0 4px 20px rgba(0,0,0,.4);
        }

        html, body { height: 100%; background: var(--bg); color: var(--text); font-family: 'Inter', sans-serif; overflow: hidden; }

        /* ============================================================
           TOP NAV
        ============================================================ */
        .top-nav {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 16px;
            height: 52px;
            background: var(--bg2);
            border-bottom: 1px solid var(--border);
        }
        .top-nav .brand {
            font-weight: 800;
            font-size: .95rem;
            color: var(--accent-h);
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .top-nav .barcode-wrap {
            flex: 1;
            position: relative;
            max-width: 460px;
        }
        .top-nav .barcode-wrap i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }
        #barcode-input {
            width: 100%;
            padding: 8px 12px 8px 36px;
            background: var(--bg3);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            color: var(--text);
            font-size: .9rem;
            transition: border-color .2s;
        }
        #barcode-input:focus { outline: none; border-color: var(--accent); }

        .top-nav .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: auto;
        }
        .nav-pill {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            border: 1.5px solid var(--border);
            background: var(--bg3);
            color: var(--text);
            transition: all .2s;
            text-decoration: none;
        }
        .nav-pill:hover { border-color: var(--accent); color: var(--accent-h); }
        .nav-pill.pill-success { border-color: var(--success); color: var(--success); }
        .user-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 10px 4px 4px;
            background: var(--bg3);
            border: 1.5px solid var(--border);
            border-radius: 20px;
            font-size: .8rem;
        }
        .user-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .75rem;
        }

        /* ============================================================
           LAYOUT PRINCIPAL
        ============================================================ */
        .pos-container {
            display: grid;
            grid-template-columns: 1fr 505px;
            gap: 0;
            height: calc(100vh - 102px);
            overflow: hidden;
        }

        /* Paneles */
        .panel {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border-right: 1px solid var(--border);
        }
        .panel:last-child { border-right: none; }

        .panel-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            background: var(--bg2);
        }
        .panel-title {
            font-weight: 700;
            font-size: .9rem;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .panel-title i { color: var(--accent); }

        /* ============================================================
           PANEL IZQUIERDO — CATÁLOGO DE PRODUCTOS
        ============================================================ */
        .catalog-panel { background: var(--bg); }

        /* Filtros de categoría */
        .cat-bar {
            display: flex;
            gap: 6px;
            padding: 10px 12px;
            overflow-x: auto;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
            scrollbar-width: none;
        }

        .cat-bar::-webkit-scrollbar { display: none; }
        .cat-chip {
            padding: 5px 14px;
            border-radius: 20px;
            border: 1.5px solid var(--border);
            background: var(--bg3);
            color: var(--text-muted);
            font-size: .78rem;
            font-weight: 600;
            white-space: nowrap;
            cursor: pointer;
            transition: all .2s;
        }

        .cat-chip:hover { border-color: var(--accent); color: var(--text); }
        .cat-chip.active { background: var(--accent); border-color: var(--accent); color: #fff; }

        /* Grid de productos */
        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 10px;
            padding: 12px;
            overflow-y: auto;
            flex: 1;
        }
        .catalog-grid::-webkit-scrollbar { width: 4px; }
        .catalog-grid::-webkit-scrollbar-track { background: transparent; }
        .catalog-grid::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        .prod-card {
            background: var(--bg2);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 12px;
            cursor: pointer;
            transition: all .18s;
            display: flex;
            flex-direction: column;
            gap: 6px;
            position: relative;
        }
        .prod-card:hover { border-color: var(--accent); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99,102,241,.2); }
        .prod-card.flash { background: rgba(16,185,129,.12); border-color: var(--success); }
        .prod-card.out-of-stock { opacity: .45; cursor: not-allowed; }
        .prod-card.out-of-stock:hover { transform: none; box-shadow: none; border-color: var(--border); }

        .prod-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--bg3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--accent);
        }
        .prod-name {
            font-weight: 600;
            font-size: .82rem;
            color: var(--text);
            line-height: 1.3;
            /* Truncate to 2 lines */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .prod-price {
            font-weight: 800;
            font-size: 1rem;
            color: var(--success);
        }
        .prod-stock-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            font-size: .65rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 10px;
        }
        .stock-ok  { background: rgba(16,185,129,.15); color: var(--success); }
        .stock-low { background: rgba(245,158,11,.15);  color: var(--warning); }
        .stock-out { background: rgba(239,68,68,.15);   color: var(--danger);  }

        .catalog-empty {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }
        .catalog-empty i { font-size: 2.5rem; margin-bottom: 10px; display: block; }

        /* Skeleton loader */
        .skeleton {
            background: linear-gradient(90deg, var(--bg3) 25%, var(--bg2) 50%, var(--bg3) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.4s infinite;
            border-radius: 8px;
        }
        @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        /* ============================================================
           PANEL CENTRAL — CARRITO
        ============================================================ */
        .cart-panel { background: var(--bg2); }

        /* Badge contador */
        .cart-count-badge {
            background: var(--accent);
            color: #fff;
            font-size: .7rem;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 10px;
        }

        .btn-clear-cart {
            padding: 5px 10px;
            border-radius: 6px;
            border: 1.5px solid var(--border);
            background: transparent;
            color: var(--text-muted);
            font-size: .78rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: all .2s;
        }
        .btn-clear-cart:hover { border-color: var(--danger); color: var(--danger); }

        /* Selector de cliente */
        .customer-bar {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }
        .customer-bar label {
            font-size: .72rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .05em;
            display: block;
            margin-bottom: 5px;
        }
        .customer-select {
            width: 100%;
            padding: 7px 10px;
            background: var(--bg3);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: .84rem;
        }
        .customer-select:focus { outline: none; border-color: var(--accent); }

        /* Tabla del carrito */
        .cart-header-row {
            display: grid;
            grid-template-columns: 1fr 70px 80px 80px 30px;
            padding: 7px 14px;
            font-size: .68rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .05em;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }
        .cart-items {
            flex: 1;
            overflow-y: auto;
        }
        .cart-items::-webkit-scrollbar { width: 3px; }
        .cart-items::-webkit-scrollbar-thumb { background: var(--border); }

        .cart-row {
            display: grid;
            grid-template-columns: 1fr 70px 80px 80px 30px;
            align-items: center;
            padding: 9px 14px;
            border-bottom: 1px solid rgba(255,255,255,.04);
            transition: background .15s;
        }
        .cart-row:hover { background: rgba(255,255,255,.03); }
        .cart-item-name {
            font-weight: 600;
            font-size: .82rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .cart-item-code {
            font-size: .68rem;
            color: var(--text-muted);
            font-family: monospace;
        }
        .qty-ctrl {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .qty-btn {
            width: 22px;
            height: 22px;
            border-radius: 5px;
            border: 1.5px solid var(--border);
            background: var(--bg3);
            color: var(--text);
            cursor: pointer;
            font-size: .85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
        }
        .qty-btn:hover { border-color: var(--accent); color: var(--accent); }
        .qty-val {
            min-width: 22px;
            text-align: center;
            font-weight: 700;
            font-size: .85rem;
        }
        .cart-price { font-size: .82rem; color: var(--text-muted); }
        .cart-total { font-weight: 700; font-size: .88rem; color: var(--success); }
        .btn-remove {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 3px;
            border-radius: 4px;
            transition: color .15s;
        }
        .btn-remove:hover { color: var(--danger); }

        .cart-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            color: var(--text-muted);
            gap: 10px;
            padding: 40px;
        }
        .cart-empty i { font-size: 2.5rem; }

        /* Resumen */
        .cart-summary {
            padding: 12px 14px;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
            background: var(--bg);
        }
        .sum-row {
            display: flex;
            justify-content: space-between;
            font-size: 1.3rem;
            padding: 3px 0;
            color: var(--text-muted);
        }
        .sum-row.total-row {
            margin-left: 20rem;
            font-size: 2.1rem;
            font-weight: 800;
            color: var(--text);
            border-top: 1px solid var(--border);
            padding-top: 8px;
            margin-top: 6px;
        }
        .sum-row.total-row span:last-child { color: var(--success); }

        /* ============================================================
           PANEL DERECHO — PAGO
        ============================================================ */
        .payment-panel { background: var(--bg2); }

        /* Método de pago */
        .pay-methods {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 4px;
            padding: 8px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }
        .pay-method-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            padding: 6px 4px;
            border-radius: 6px;
            border: 1.5px solid var(--border);
            background: var(--bg3);
            color: var(--text-muted);
            cursor: pointer;
            font-size: .65rem;
            font-weight: 600;
            transition: all .18s;
        }
        .pay-method-btn i { font-size: 1rem; }
        .pay-method-btn:hover { border-color: var(--accent); color: var(--text); }
        .pay-method-btn.active { border-color: var(--accent); background: rgba(99,102,241,.12); color: var(--accent-h); }

        /* Totales display */
        .pay-display {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 8px;
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }
        .pay-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 8px;
            background: var(--bg3);
            border-radius: 6px;
            border: 1.5px solid var(--border);
        }
        .pay-box-label { font-size: .65rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; }
        .pay-box-value { font-weight: 800; font-size: .95rem; }
        .pay-box-value.value-total { color: var(--accent-h); font-size: 1.15rem; }
        .pay-box-value.value-recib { color: var(--text); }
        .pay-box-value.value-cambio { color: var(--success); }
        .pay-box-value.value-deficit { color: var(--danger); }

        /* Numpad */
        .numpad-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 8px;
            gap: 4px;
            overflow: hidden;
        }
        .numpad {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
        }
        .submenu {
            grid-column: 4 / span 2;
            grid-row: 1 / span 4;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 4px;
        }
        .numpad-btn {
            padding: 8px;
            border-radius: 6px;
            border: 1.5px solid var(--border);
            background: var(--bg3);
            color: var(--text);
            font-size: .9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .12s;
        }
        .numpad-btn:hover { border-color: var(--accent); background: rgba(99,102,241,.08); }
        .numpad-btn:active { transform: scale(.95); }
        .numpad-btn.btn-clear { color: var(--warning); border-color: rgba(245,158,11,.3); }
        .numpad-btn.btn-back  { color: var(--text-muted); }

        .quick-amounts {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
        }
        .quick-btn {
            padding: 8px 4px;
            border-radius: 8px;
            border: 1.5px solid var(--border);
            background: var(--bg3);
            color: var(--accent-h);
            font-size: .78rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .15s;
        }
        .quick-btn:hover { border-color: var(--accent); background: rgba(99,102,241,.1); }

        /* Botón cobrar */
        .btn-cobrar {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: none;
            background: linear-gradient(135deg, var(--accent), #4f46e5);
            color: #fff;
            font-size: .9rem;
            font-weight: 800;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            box-shadow: 0 4px 10px rgba(99,102,241,.25);
        }
        .btn-cobrar:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99,102,241,.5); }
        .btn-cobrar:active { transform: translateY(0); }
        .btn-cobrar:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }

        /* ============================================================
           MODAL DE ÉXITO / ERROR
        ============================================================ */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.75);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(4px);
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: var(--bg2);
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 32px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            animation: popIn .25s ease;
        }
        @keyframes popIn {
            from { transform: scale(.85); opacity: 0; }
            to   { transform: scale(1);   opacity: 1; }
        }
        .modal-icon { font-size: 3.5rem; margin-bottom: 12px; display: block; }
        .modal-title { font-size: 1.3rem; font-weight: 800; margin-bottom: 6px; }
        .modal-subtitle { color: var(--text-muted); font-size: .9rem; margin-bottom: 20px; }
        .modal-meta { font-size: 1rem; font-weight: 700; color: var(--success); margin-bottom: 20px; }
        .modal-btns { display: flex; gap: 10px; justify-content: center; }
        .modal-btn {
            padding: 10px 22px;
            border-radius: 8px;
            border: 1.5px solid var(--border);
            background: var(--bg3);
            color: var(--text);
            font-size: .9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .modal-btn:hover { border-color: var(--accent); color: var(--accent-h); }
        .modal-btn.modal-btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .modal-btn.modal-btn-primary:hover { background: #4f46e5; }

        /* Toast */
        .toast-container {
            position: fixed;
            top: 62px;
            right: 16px;
            z-index: 8888;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .toast {
            padding: 10px 16px;
            border-radius: 8px;
            font-size: .85rem;
            font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--bg2);
            color: var(--text);
            animation: slideIn .25s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            max-width: 300px;
            box-shadow: var(--shadow);
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }
        .toast.toast-success { border-color: var(--success); color: var(--success); }
        .toast.toast-error   { border-color: var(--danger);  color: var(--danger);  }
        .toast.toast-info    { border-color: var(--accent);  color: var(--accent-h); }

        /* Spinner */
        .spinner {
            width: 18px;
            height: 18px;
            border: 2.5px solid rgba(255,255,255,.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            flex-shrink: 0;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

<!-- ================================================================
     TOP NAV
================================================================ -->
<div class="top-nav">
    <div class="brand">
        <i class="bi bi-lightning-charge-fill" style="color:var(--accent)"></i>
        POS Terminal
    </div>

    <div class="barcode-wrap">
        <i class="bi bi-upc-scan"></i>
        <input type="text"
               id="barcode-input"
               placeholder="Buscar o escanear código de barras... (Enter)"
               autocomplete="off"
               autofocus>
    </div>

    <div class="nav-right">
        <button class="nav-pill pill-success" onclick="openCustomerDisplay()">
            <i class="bi bi-display"></i> Monitor Cliente
        </button>
        <a href="<?= url('?r=dashboard/index') ?>" class="nav-pill">
            <i class="bi bi-grid"></i> Panel
        </a>
        <div class="user-chip">
            <div class="user-avatar">
                <?= strtoupper(substr(Auth::user()['nombre'] ?? 'U', 0, 1)) ?>
            </div>
            <span style="font-size:.8rem;font-weight:600"><?= e(Auth::user()['nombre'] ?? 'Usuario') ?></span>
        </div>
    </div>
</div>

<!-- ================================================================
     CONTENEDOR PRINCIPAL
================================================================ -->
<div class="pos-container">

    <!-- ================================
         PANEL 2: CARRITO
    ================================ -->
    <div class="panel cart-panel">
        <div class="panel-header">
            <div class="panel-title">
                <i class="bi bi-cart3"></i>
                Carrito
                <span class="cart-count-badge" id="cart-badge">0</span>
            </div>
            <button class="btn-clear-cart" onclick="clearCart()">
                <i class="bi bi-trash3"></i> Vaciar
            </button>
        </div>

        <!-- Selector de cliente -->
        <div class="customer-bar">
            <label><i class="bi bi-person"></i> Cliente</label>
            <select class="customer-select" id="cliente-select">
                <option value="">— Consumidor Final —</option>
            </select>
        </div>

        <!-- Encabezados -->
        <div class="cart-header-row">
            <span>Producto</span>
            <span style="text-align:center">Cant.</span>
            <span style="text-align:right">Precio</span>
            <span style="text-align:right">Total</span>
            <span></span>
        </div>

        <!-- Items -->
        <div class="cart-items" id="cart-items">
            <div class="cart-empty" id="cart-empty">
                <i class="bi bi-cart-x"></i>
                <span>Carrito vacío</span>
                <small style="font-size:.75rem;color:var(--text-muted)">Haz clic en un producto o escanea un código</small>
            </div>
        </div>

        <!-- Resumen de totales -->
        <div class="cart-summary">
            <div class="sum-row">
                <span>Subtotal</span>
                <span id="sum-subtotal">$0.00</span>
            </div>
            <div class="sum-row">
                <span>ITBMS (<?= round(cfg('itbms_rate') * 100) ?>%)</span>
                <span id="sum-itbms">$0.00</span>
            </div>
            <div class="sum-row total-row">
                <span>TOTAL</span>
                <span id="sum-total">$0.00</span>
            </div>
        </div>
    </div>

    <!-- ================================
         PANEL 3: PAGO
    ================================ -->
    <div class="panel payment-panel">
        <div class="panel-header">
            <div class="panel-title">
                <i class="bi bi-credit-card-2-front"></i>
                Pago
            </div>
        </div>

        <!-- Métodos de pago -->
        <div class="pay-methods" style="grid-template-columns: repeat(3, 1fr);">
            <button class="pay-method-btn active" data-method="efectivo" onclick="selectMethod(this)">
                <i class="bi bi-cash-stack"></i> Efectivo
            </button>
            <button class="pay-method-btn" data-method="tarjeta_credito" onclick="selectMethod(this)">
                <i class="bi bi-credit-card"></i> T. Crédito
            </button>
            <button class="pay-method-btn" data-method="tarjeta_debito" onclick="selectMethod(this)">
                <i class="bi bi-credit-card-2-back"></i> T. Débito
            </button>
            <button class="pay-method-btn" data-method="yappy" onclick="selectMethod(this)">
                <i class="bi bi-phone"></i> Yappy
            </button>
            <button class="pay-method-btn" data-method="nequi" onclick="selectMethod(this)">
                <i class="bi bi-phone-vibrate"></i> Nequi
            </button>
            <button class="pay-method-btn" data-method="transferencia" onclick="selectMethod(this)">
                <i class="bi bi-arrow-left-right"></i> Transf.
            </button>
        </div>

        <!-- Pantalla de valores -->
        <div class="pay-display">
            <div class="pay-box">
                <span class="pay-box-label"><i class="bi bi-receipt"></i> Total a cobrar</span>
                <span class="pay-box-value value-total" id="pay-total">$0.00</span>
            </div>
            <div class="pay-box" id="efectivo-box">
                <span class="pay-box-label"><i class="bi bi-cash"></i> Recibido</span>
                <span class="pay-box-value value-recib" id="pay-recibido">$0.00</span>
            </div>
            <div class="pay-box" id="cambio-box">
                <span class="pay-box-label"><i class="bi bi-arrow-return-left"></i> Cambio</span>
                <span class="pay-box-value value-cambio" id="pay-cambio">$0.00</span>
            </div>
        </div>

        <!-- Numpad + Acciones -->
        <div class="numpad-section">
            <!-- Montos rápidos -->

            <!-- Numpad -->
            <div class="numpad">
                <button class="numpad-btn btn-clear" onclick="numClear()">C</button>
                <button class="numpad-btn btn-back" onclick="numBack()">⌫</button>
                <button class="numpad-btn" onclick="numInput('%')">%</button>
                <button class="numpad-btn" onclick="numInput('/')">/</button>
                <button class="numpad-btn" onclick="numInput('7')">7</button>
                <button class="numpad-btn" onclick="numInput('8')">8</button>
                <button class="numpad-btn" onclick="numInput('9')">9</button>
                <button class="numpad-btn" onclick="numInput('*')">*</button>
                <button class="numpad-btn" onclick="numInput('4')">4</button>
                <button class="numpad-btn" onclick="numInput('5')">5</button>
                <button class="numpad-btn" onclick="numInput('6')">6</button>
                <button class="numpad-btn" onclick="numInput('-')">-</button>
                <button class="numpad-btn" onclick="numInput('1')">1</button>
                <button class="numpad-btn" onclick="numInput('2')">2</button>
                <button class="numpad-btn" onclick="numInput('3')">3</button>
                <button class="numpad-btn" onclick="numInput('+')">+</button>
                <button class="numpad-btn" onclick="numInput(',')">,</button>
                <button class="numpad-btn" onclick="numInput('0')">0</button>
                <button class="numpad-btn" onclick="numInput('.')">.</button>
                <button class="numpad-btn" onclick="numInput('=')">=</button>
            </div>

            <!-- Botón cobrar -->
            <button class="btn-cobrar" id="btn-cobrar" onclick="procesarPago()">
                <i class="bi bi-check-circle-fill"></i>
                Cobrar
            </button>
        </div>
    </div>
</div>

<!-- ================================================================
     MODAL DE ÉXITO
================================================================ -->
<div class="modal-overlay" id="modal-success">
    <div class="modal-box">
        <span class="modal-icon">✅</span>
        <div class="modal-title">¡Venta Registrada!</div>
        <div class="modal-subtitle" id="modal-subtitle">Pago procesado correctamente.</div>
        <div class="modal-meta" id="modal-meta"></div>
        <div class="modal-btns">
            <a href="#" class="modal-btn modal-btn-primary" id="modal-ver-factura">
                <i class="bi bi-receipt"></i> Ver Factura
            </a>
            <button class="modal-btn" onclick="closeModal()">
                <i class="bi bi-arrow-repeat"></i> Nueva Venta
            </button>
        </div>
    </div>
</div>

<!-- Toasts -->
<div class="toast-container" id="toast-container"></div>

<!-- CSRF token para AJAX -->
<script>
    const CSRF_TOKEN = '<?= csrf_token() ?>';
    const ITBMS_RATE = <?= cfg('itbms_rate') ?>;
    const BASE_URL   = '<?= url('') ?>';
    const CURRENCY   = '<?= cfg('currency') ?>';
</script>

<script>
/* ================================================================
   ESTADO GLOBAL
================================================================ */
let cart        = [];           // [{id, codigo, nombre, precio, stock, qty}]
let allProducts = [];           // productos cargados de la BD
let activeCat   = 0;            // categoría activa (0 = todos)
let payMethod   = 'efectivo';   // método de pago seleccionado
let tenderedStr = '0';          // string del monto ingresado en numpad

/* ================================================================
   UTILIDADES
================================================================ */
function fmt(n) {
    return CURRENCY + ' ' + parseFloat(n).toFixed(2);
}

function escHtml(unsafe) {
    if (!unsafe) return '';
    return String(unsafe)
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}

function toast(msg, type = 'info') {
    const icons = { success: '✓', error: '✕', info: 'ℹ' };
    const el = document.createElement('div');
    el.className = `toast toast-${type}`;
    el.innerHTML = `<span>${icons[type] || 'ℹ'}</span><span>${msg}</span>`;
    document.getElementById('toast-container').appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

/* ================================================================
   BÚSQUEDA POR TEXTO / SCANNER
================================================================ */
const barcodeInput = document.getElementById('barcode-input');

barcodeInput.addEventListener('keydown', async function(e) {
    if (e.key !== 'Enter') return;
    const val = this.value.trim();
    if (!val) return;

    try {
        const url = BASE_URL + '?r=pos/api_productos&barcode=' + encodeURIComponent(val);
        const res  = await fetch(url, { credentials: 'same-origin' });
        const data = await res.json();

        if (data.length === 1) {
            addToCart(data[0]);
            toast(`${data[0].nombre} agregado`, 'success');
        } else {
            const urlQ = BASE_URL + '?r=pos/api_productos&q=' + encodeURIComponent(val);
            const resQ = await fetch(urlQ, { credentials: 'same-origin' });
            const dataQ = await resQ.json();
            
            if (dataQ.length === 1) {
                addToCart(dataQ[0]);
                toast(`${dataQ[0].nombre} agregado`, 'success');
            } else if (dataQ.length > 1) {
                toast('Múltiples resultados para "' + val + '". Sea más específico o use el código exacto.', 'warning');
            } else {
                toast('Producto no encontrado: ' + val, 'error');
            }
        }
    } catch(err) {
        toast('Error de búsqueda: ' + err.message, 'error');
    }
    this.value = '';
});

/* ================================================================
   CARRITO
================================================================ */
function addToCart(product) {
    const existing = cart.find(i => i.id === product.id);
    const stock    = parseInt(product.stock) || 0;

    if (existing) {
        if (existing.qty >= stock) {
            toast(`Stock máximo alcanzado (${stock})`, 'error');
            return;
        }
        existing.qty++;
    } else {
        if (stock <= 0) {
            toast('Producto agotado', 'error');
            return;
        }
        cart.push({
            id:     product.id,
            codigo: product.codigo,
            nombre: product.nombre,
            precio: parseFloat(product.precio_venta),
            stock:  stock,
            qty:    1
        });
    }

    renderCart();
    updatePayDisplay();
}

function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;

    const newQty = item.qty + delta;
    if (newQty <= 0) {
        cart = cart.filter(i => i.id !== id);
    } else if (newQty > item.stock) {
        toast(`Stock máximo: ${item.stock}`, 'error');
        return;
    } else {
        item.qty = newQty;
    }
    renderCart();
    updatePayDisplay();
}

function removeItem(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
    updatePayDisplay();
}

function clearCart() {
    cart = [];
    tenderedStr = '0';
    renderCart();
    updatePayDisplay();
}

function renderCart() {
    const container = document.getElementById('cart-items');
    const badge     = document.getElementById('cart-badge');

    const totalItems = cart.reduce((s, i) => s + i.qty, 0);
    badge.textContent = totalItems;

    if (cart.length === 0) {
        container.innerHTML = `
            <div class="cart-empty" id="cart-empty">
                <i class="bi bi-cart-x"></i>
                <span>Carrito vacío</span>
                <small style="font-size:.75rem;color:var(--text-muted)">Haz clic en un producto o escanea un código</small>
            </div>
        `;
        document.getElementById('btn-cobrar').disabled = true;
        return;
    }

    container.innerHTML = '';
    cart.forEach(item => {
        const row = document.createElement('div');
        row.className = 'cart-row';
        row.innerHTML = `
            <div>
                <div class="cart-item-name">${escHtml(item.nombre)}</div>
                <div class="cart-item-code">${escHtml(item.codigo)}</div>
            </div>
            <div class="qty-ctrl">
                <button class="qty-btn" onclick="changeQty(${item.id}, -1)">−</button>
                <span class="qty-val">${item.qty}</span>
                <button class="qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
            </div>
            <div class="cart-price" style="text-align:right">${fmt(item.precio)}</div>
            <div class="cart-total" style="text-align:right">${fmt(item.precio * item.qty)}</div>
            <button class="btn-remove" onclick="removeItem(${item.id})"><i class="bi bi-x"></i></button>
        `;
        container.appendChild(row);
    });

    document.getElementById('btn-cobrar').disabled = false;
    updateSummary();
}

function getSubtotal() { return cart.reduce((s, i) => s + i.precio * i.qty, 0); }

function updateSummary() {
    const sub   = getSubtotal();
    const itbms = sub * ITBMS_RATE;
    const total = sub + itbms;
    document.getElementById('sum-subtotal').textContent = fmt(sub);
    document.getElementById('sum-itbms').textContent    = fmt(itbms);
    document.getElementById('sum-total').textContent    = fmt(total);
}

/* ================================================================
   PANEL DE PAGO
================================================================ */
function updatePayDisplay() {
    const sub   = getSubtotal();
    const itbms = sub * ITBMS_RATE;
    const total = sub + itbms;
    const recv  = parseFloat(tenderedStr) || 0;
    const cambio = recv - total;

    document.getElementById('pay-total').textContent    = fmt(total);
    document.getElementById('pay-recibido').textContent = fmt(recv);

    const cambioEl  = document.getElementById('pay-cambio');
    const cambioBox = document.getElementById('cambio-box');

    if (payMethod === 'efectivo') {
        cambioBox.style.display = '';
        if (cambio >= 0 && recv > 0) {
            cambioEl.textContent = fmt(cambio);
            cambioEl.className = 'pay-box-value value-cambio';
        } else if (recv > 0 && cambio < 0) {
            cambioEl.textContent = '−' + fmt(Math.abs(cambio));
            cambioEl.className = 'pay-box-value value-deficit';
        } else {
            cambioEl.textContent = fmt(0);
            cambioEl.className = 'pay-box-value value-cambio';
        }
    } else {
        cambioBox.style.display = 'none';
        document.getElementById('efectivo-box').querySelector('.pay-box-value').textContent = '— N/A —';
    }
}

function selectMethod(btn) {
    document.querySelectorAll('.pay-method-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    payMethod = btn.dataset.method;

    const numpadArea  = document.getElementById('quick-amounts');
    const efBox       = document.getElementById('efectivo-box');

    if (payMethod === 'efectivo') {
        numpadArea.style.display = '';
        efBox.style.display      = '';
        document.getElementById('cambio-box').style.display = '';
    } else {
        numpadArea.style.display = 'none';
        efBox.style.display      = 'none';
        document.getElementById('cambio-box').style.display = 'none';
        tenderedStr = '0';
    }
    updatePayDisplay();
}

/* ================================================================
   NUMPAD
================================================================ */
function numInput(ch) {
    if (tenderedStr === '0' && ch !== '.') tenderedStr = ch;
    else if (ch === '.' && tenderedStr.includes('.')) { /* ya tiene punto */ }
    else tenderedStr += ch;
    updatePayDisplay();
}
function numClear() { tenderedStr = '0'; updatePayDisplay(); }
function numBack()  {
    tenderedStr = tenderedStr.length > 1 ? tenderedStr.slice(0, -1) : '0';
    updatePayDisplay();
}
function setQuick(v) { tenderedStr = String(v); updatePayDisplay(); }
function setExact()  {
    const sub   = getSubtotal();
    const total = sub + sub * ITBMS_RATE;
    tenderedStr = total.toFixed(2);
    updatePayDisplay();
}

/* ================================================================
   PROCESAR PAGO
================================================================ */
async function procesarPago() {
    if (cart.length === 0) { toast('El carrito está vacío', 'error'); return; }

    const sub      = getSubtotal();
    const itbms    = sub * ITBMS_RATE;
    const total    = sub + itbms;
    const recibido = parseFloat(tenderedStr) || 0;

    if (payMethod === 'efectivo' && recibido < total - 0.005) {
        toast('Monto recibido insuficiente. Faltan ' + fmt(total - recibido), 'error');
        return;
    }

    const clienteId = document.getElementById('cliente-select').value;

    const payload = {
        _csrf:          CSRF_TOKEN,
        cliente_id:     parseInt(clienteId) || 0,
        metodo_pago:    payMethod,
        monto_recibido: recibido,
        items: cart.map(i => ({
            id:       i.id,
            nombre:   i.nombre,
            cantidad: i.qty,
            precio:   i.precio
        }))
    };

    // UI: loading state
    const btn = document.getElementById('btn-cobrar');
    btn.disabled = true;
    btn.innerHTML = '<div class="spinner"></div> Procesando...';

    try {
        const res = await fetch(BASE_URL + '?r=pos/api_vender', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify(payload)
        });

        // Reinject CSRF header in POST form-style approach
        // Use form POST as fallback with hidden _csrf
        const data = await res.json();

        if (data.ok) {
            // Éxito
            const cambio = parseFloat(data.cambio) || 0;
            document.getElementById('modal-subtitle').textContent =
                `Factura ${data.factura} • ${payMethod.charAt(0).toUpperCase() + payMethod.slice(1)}`;
            document.getElementById('modal-meta').textContent =
                `Total: ${fmt(data.total)}` + (payMethod === 'efectivo' ? `  |  Cambio: ${fmt(cambio)}` : '');
            document.getElementById('modal-ver-factura').href = data.url_factura;
            document.getElementById('modal-success').classList.add('open');

            // Sync pantalla cliente
            syncCustomerDisplay({ cart: [], total: 0, status: 'paid', cambio });

            clearCart();

        } else {
            toast('Error: ' + (data.error || 'Error desconocido'), 'error');
        }
    } catch (err) {
        toast('Error de conexión: ' + err.message, 'error');
    } finally {
        btn.disabled  = false;
        btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Cobrar';
    }
}

// CSRF token se envía dentro del body JSON como campo _csrf

/* ================================================================
   MODAL
================================================================ */
function closeModal() {
    document.getElementById('modal-success').classList.remove('open');
    tenderedStr = '0';
    updatePayDisplay();
    barcodeInput.focus();
}

/* ================================================================
   PANTALLA CLIENTE (dual screen)
================================================================ */
let customerWindow = null;

function openCustomerDisplay() {
    customerWindow = window.open(BASE_URL + '?r=pos/poscliente',
                                 'CustomerDisplay', 'width=1024,height=768');
}

function syncCustomerDisplay(data) {
    localStorage.setItem('pos_customer_data', JSON.stringify({
        cart:   cart,
        total:  document.getElementById('pay-total').textContent,
        status: data.status ?? 'active',
        cambio: data.cambio ?? 0
    }));
}

// Sync automático al cambiar el carrito
function updatePayDisplay_() {
    updatePayDisplay();
    syncCustomerDisplay({});
}

/* ================================================================
   OVERRIDE csrf_check con fetch que incluye el _csrf en el JSON
================================================================ */
// El backend usa: $t = $_POST['_csrf'] ?? '';  pero estamos enviando JSON.
// Necesitamos hacer que csrf_check() lea también de JSON body.
// SOLUCIÓN: Enviamos el CSRF como query param en la URL del POST.
// (Modificaremos la función procesarPago para esto)

/* ================================================================
   INIT
================================================================ */
// Mantener foco en el input de búsqueda (para scanner físico)
document.addEventListener('keydown', function(e) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT' || e.target.tagName === 'BUTTON') return;
    if (e.key.length === 1 || e.key === 'Enter') {
        barcodeInput.focus();
    }
});
</script>

</body>
</html>
