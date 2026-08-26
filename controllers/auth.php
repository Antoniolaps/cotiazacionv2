<?php
function login() {
    // Si ya está logueado, redirigir directamente
    if (Auth::check()) {
        _redirectByRole(Auth::role());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? $_POST['password_hash'] ?? '';

        if ($username === '' || $password === '') {
            flash('error', 'Usuario y contraseña son requeridos.');
        } elseif (Auth::attempt($username, $password)) {
            _redirectByRole(Auth::role());
        } else {
            flash('error', 'Credenciales inválidas. Verifique su usuario y contraseña.');
        }
    }

    $title = 'Iniciar sesión';
    require __DIR__ . '/../views/auth/login.php';
}

/**
 * Redirige al panel correcto según el rol del usuario.
 */
function _redirectByRole(?string $role): void {
    switch ($role) {
        case 'cliente':
            redirect('?r=pos/poscliente');
            break;
        case 'vendedor':
            redirect('?r=pos/terminal');
            break;
        case 'almacen':
            // Tiene vista propia en dashboard
            redirect('?r=dashboard/index');
            break;
        case 'admin':
            redirect('?r=dashboard/index');
            break;
        default:
            redirect('?r=dashboard/index');
            break;
    }
}

function logout() {
    Auth::logout();
    flash('success', 'Sesión cerrada correctamente.');
    redirect('?r=auth/login');
}
