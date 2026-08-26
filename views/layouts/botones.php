<?php

// Render buttons according to user role
function renderButtons() {
    // Try common Auth APIs to detect role
    $role = null;
    if (class_exists('Auth')) {
        if (method_exists('Auth', 'user')) {
            $user = Auth::user();
            if (is_object($user) && isset($user->role)) {
                $role = $user->role;
            } elseif (is_array($user) && isset($user['role'])) {
                $role = $user['role'];
            }
        }
        if ($role === null && method_exists('Auth', 'getRole')) {
            $role = Auth::getRole();
        }
        if ($role === null && method_exists('Auth', 'role')) {
            $role = Auth::role();
        }
        if ($role === null && method_exists('Auth', 'hasRole')) {
            // fallback: test common roles
            if (Auth::hasRole('admin')) {
                $role = 'admin';
            } elseif (Auth::hasRole('editor')) {
                $role = 'editor';
            } elseif (Auth::hasRole('viewer')) {
                $role = 'viewer';
            }
        }
    }

    // Default to guest if role unknown
    if ($role === null) {
        $role = 'guest';
    }

    // Define buttons per role
    $buttons = [];
    switch ($role) {
        case 'admin':
            $buttons = [
                ['id' => 'create', 'label' => 'Crear', 'class' => 'btn btn-primary'],
                ['id' => 'edit', 'label' => 'Editar', 'class' => 'btn btn-warning'],
                ['id' => 'delete', 'label' => 'Eliminar', 'class' => 'btn btn-danger'],
            ];
            break;
        case 'editor':
            $buttons = [
                ['id' => 'create', 'label' => 'Crear', 'class' => 'btn btn-primary'],
                ['id' => 'edit', 'label' => 'Editar', 'class' => 'btn btn-warning'],
            ];
            break;
        case 'viewer':
            $buttons = [
                ['id' => 'view', 'label' => 'Ver', 'class' => 'btn btn-secondary'],
            ];
            break;
        default:
            $buttons = [];
    }

    // Output HTML
    foreach ($buttons as $btn) {
        echo sprintf('<button id="%s" class="%s">%s</button>\n', htmlspecialchars($btn['id']), htmlspecialchars($btn['class']), htmlspecialchars($btn['label']));
    }
}

// Backward-compatible alias
function index() {
    // Require admin for pages that call index directly
    if (class_exists('Auth') && method_exists('Auth', 'requireRole')) {
        Auth::requireRole(['admin']);
    }
    renderButtons();
}

