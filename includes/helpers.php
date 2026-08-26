<?php
function cfg(string $key = null) {
    static $c = null;
    if ($c === null) $c = require __DIR__ . '/../config/config.php';
    return $key ? ($c[$key] ?? null) : $c;
}

function url(string $path = ''): string {
    return rtrim(cfg('base_url'), '/') . '/public/index.php' . $path;
}

function asset(string $path): string {
    return rtrim(cfg('base_url'), '/') . '/public/assets/' . ltrim($path, '/');
}

function e(?string $v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function money($v): string {
    return cfg('currency') . ' ' . number_format((float)$v, 2);
}

function flash(string $key, ?string $msg = null) {
    if ($msg !== null) { $_SESSION['_flash'][$key] = $msg; return; }
    $m = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $m;
}

function csrf_token(): string {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void {
    // 1. Form POST
    $t = $_POST['_csrf'] ?? '';

    // 2. JSON body (AJAX requests con Content-Type: application/json)
    if ($t === '') {
        $ct = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($ct, 'application/json') !== false) {
            $body = raw_input_json();
            $t    = $body['_csrf'] ?? '';
        }
    }

    // 3. Query string (?_csrf=...) como último recurso
    if ($t === '') {
        $t = $_GET['_csrf'] ?? '';
    }

    if (!hash_equals(csrf_token(), $t)) {
        http_response_code(419);
        header('Content-Type: application/json');
        exit(json_encode(['error' => 'CSRF token inválido']));
    }
}

/**
 * Lee y cachea el body JSON de la petición actual.
 * Evita consumir php://input más de una vez.
 */
function raw_input_json(): array {
    static $cache = null;
    if ($cache === null) {
        $cache = json_decode(file_get_contents('php://input'), true) ?? [];
    }
    return $cache;
}

function redirect(string $path): void {
    header('Location: ' . url($path));
    exit;
}

function input(string $key, $default = null) {
    return $_REQUEST[$key] ?? $default;
}

function next_factura(): string {
    $row = DB::conn()->query("SELECT COUNT(*)+1 AS n FROM ventas")->fetch();
    return 'F-' . str_pad((string)$row['n'], 6, '0', STR_PAD_LEFT);
}

function next_orden(): string {
    $row = DB::conn()->query("SELECT COUNT(*)+1 AS n FROM ordenes_compra")->fetch();
    return 'OC-' . str_pad((string)$row['n'], 6, '0', STR_PAD_LEFT);
}

function getStringColor($str) {
    if (!$str) return '#6c757d'; // default gray
    $colors = ['#6B46C1', '#D69E2E', '#38B2AC', '#ED64A6', '#48BB78', '#4299E1', '#E53E3E', '#ED8936', '#9F7AEA', '#4FD1C5', '#F56565'];
    $idx = abs(crc32($str)) % count($colors);
    return $colors[$idx];
}

