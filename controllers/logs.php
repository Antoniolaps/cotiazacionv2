<?php
/**
 * Controlador de Logs de Actividades (Bitácora)
 */
require_once __DIR__ . '/../includes/auth.php';

function index() {
    Auth::requireRole(['admin']);

    $qTabla   = trim($_GET['tabla'] ?? '');
    $qAccion  = trim($_GET['accion'] ?? '');
    $qUsuario = (int)($_GET['usuario_id'] ?? 0);
    $desde    = $_GET['desde'] ?? date('Y-m-01');
    $hasta    = $_GET['hasta'] ?? date('Y-m-d');
    $page     = max(1, (int)($_GET['p'] ?? 1));
    $limit    = 20;

    $where  = ["DATE(l.fecha) BETWEEN ? AND ?"];
    $params = [$desde, $hasta];

    if ($qTabla !== '') {
        $where[]  = "l.tabla_afectada = ?";
        $params[] = $qTabla;
    }
    if ($qAccion !== '') {
        $where[]  = "l.accion = ?";
        $params[] = $qAccion;
    }
    if ($qUsuario > 0) {
        $where[]  = "l.usuario_id = ?";
        $params[] = $qUsuario;
    }

    $whereStr = implode(' AND ', $where);

    // Count
    $countStmt = DB::conn()->prepare("SELECT COUNT(*) FROM log_actividades l WHERE $whereStr");
    $countStmt->execute($params);
    $totalRows  = (int)$countStmt->fetchColumn();
    $totalPages = max(1, ceil($totalRows / $limit));

    if ($page > $totalPages) $page = $totalPages;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT l.*, u.nombre AS usuario, r.nombre AS rol
            FROM log_actividades l
            LEFT JOIN usuarios u ON u.id = l.usuario_id
            LEFT JOIN roles r ON r.id = l.rol_id
            WHERE $whereStr
            ORDER BY l.fecha DESC LIMIT $limit OFFSET $offset";

    $stmt = DB::conn()->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $usuarios = DB::conn()->query("SELECT id, nombre FROM usuarios ORDER BY nombre")->fetchAll();
    $tablas   = DB::conn()->query("SELECT DISTINCT tabla_afectada FROM log_actividades ORDER BY tabla_afectada")->fetchAll();

    $title = 'Bitácora de Actividades (Logs)';
    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/logs/index.php';
    require __DIR__ . '/../views/layouts/footer.php';
}
