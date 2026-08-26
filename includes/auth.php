<?php
/**
 * Helpers de autenticación y autorización por rol.
 */
require_once __DIR__ . '/db.php';

class Auth {
    public static function user(): ?array {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool {
        return isset($_SESSION['user']);
    }

    public static function role(): ?string {
        return $_SESSION['user']['rol_nombre'] ?? null;
    }

    public static function requireLogin(): void {
        if (!self::check()) {
            header('Location: ' . url('?r=auth/login'));
            exit;
        }
    }

    /** @param string[] $roles */
    public static function requireRole(array $roles): void {
        self::requireLogin();
        if (!in_array(self::role(), $roles, true)) {
            http_response_code(403);
            require __DIR__ . '/../views/layouts/403.php';
            exit;
        }
    }

    public static function attempt(string $username, string $password): bool {
        $stmt = DB::conn()->prepare(
            'SELECT u.*, r.nombre AS rol_nombre
             FROM usuarios u
             LEFT JOIN roles r ON r.id = u.rol_id
             WHERE u.username = ? AND u.estado = 1
             LIMIT 1'
        );
        $stmt->execute([$username]);
        $u = $stmt->fetch();
        if (!$u) return false;
        if (!password_verify($password, $u['password_hash'])) return false;

        // Refrescar ultimo_login
        DB::conn()->prepare('UPDATE usuarios SET ultimo_login = NOW() WHERE id = ?')
            ->execute([$u['id']]);

        unset($u['password_hash']);
        $_SESSION['user'] = $u;
        Activity::log('LOGIN', 'usuarios', $u['id']);
        return true;
    }

    public static function logout(): void {
        if (self::check()) {
            Activity::log('LOGOUT', 'usuarios', self::user()['id']);
        }
        session_destroy();
    }
}

class Activity {
    public static function log(string $accion, string $tabla, ?int $registro_id = null,
                               $anterior = null, $nuevo = null): void {
        try {
            DB::conn()->prepare(
                'INSERT INTO log_actividades
                 (usuario_id, rol_id, accion, tabla_afectada, registro_id, cambios_anteriores, cambios_nuevos, ip_address)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            )->execute([
                $_SESSION['user']['id'] ?? null,
                $_SESSION['user']['rol_id'] ?? null,
                $accion, $tabla, $registro_id,
                $anterior ? json_encode($anterior) : null,
                $nuevo ? json_encode($nuevo) : null,
                $_SERVER['REMOTE_ADDR'] ?? null,
            ]);
        } catch (Throwable $e) { /* silencioso */ }
    }
}
