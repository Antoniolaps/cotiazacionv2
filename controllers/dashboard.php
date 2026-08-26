<?php
function index() {
    Auth::requireLogin();
    $title = 'Dashboard';
    $role = Auth::role();
    $view = __DIR__ . '/../views/dashboard/' . $role . '.php';
    if (!file_exists($view)) {
        $view = __DIR__ . '/../views/dashboard/index.php'; // fallback
    }
    require $view;
}
