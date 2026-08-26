<?php
function _roles_opts() {
    return DB::conn()->query("SELECT id AS v, nombre AS l FROM roles WHERE activo=1 ORDER BY nombre")->fetchAll();
}

function index() {
    Auth::requireRole(['admin']);
    $rows = DB::conn()->query("SELECT u.*, r.nombre AS rol FROM usuarios u LEFT JOIN roles r ON r.id=u.rol_id ORDER BY u.id DESC")->fetchAll();
    $title = 'Usuarios';
    require __DIR__ . '/../views/usuarios/index.php';
}

function create() { Auth::requireRole(['admin']); $row=[]; $roles=_roles_opts(); $title='Nuevo usuario';
    require __DIR__ . '/../views/usuarios/form.php'; }

function edit() {
    Auth::requireRole(['admin']);
    $id = (int)($_GET['id'] ?? 0);
    $stmt = DB::conn()->prepare('SELECT * FROM usuarios WHERE id=?'); $stmt->execute([$id]);
    $row = $stmt->fetch() ?: []; $roles=_roles_opts(); $title='Editar usuario';
    require __DIR__ . '/../views/usuarios/form.php';
}

function store() {
    Auth::requireRole(['admin']); csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $data = [
      'nombre'   => $_POST['nombre'] ?? '',
      'username' => $_POST['username'] ?? '',
      'rol_id'   => $_POST['rol_id'] ?: null,
      'telefono' => $_POST['telefono'] ?? null,
      'estado'   => isset($_POST['estado']) ? 1 : 0,
    ];
    try {
        if ($id) {
            $sql = "UPDATE usuarios SET nombre=:nombre, username=:username, rol_id=:rol_id, telefono=:telefono, estado=:estado";
            if (!empty($_POST['password'])) { $sql .= ", password_hash=:password_hash"; $data['password_hash'] = password_hash($_POST['password'], PASSWORD_BCRYPT); }
            $sql .= " WHERE id=:id"; $data['id'] = $id;
            DB::conn()->prepare($sql)->execute($data);
            flash('success','Usuario actualizado');
        } else {
            if (empty($_POST['password'])) { flash('error','La contraseña es obligatoria'); redirect('?r=usuarios/create'); }
            $data['password_hash'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $cols = implode(',', array_keys($data)); $ph = ':' . implode(',:', array_keys($data));
            DB::conn()->prepare("INSERT INTO usuarios ($cols) VALUES ($ph)")->execute($data);
            flash('success','Usuario creado');
        }
    } catch (Throwable $e) { flash('error',$e->getMessage()); }
    redirect('?r=usuarios/index');
}

function update() { store(); }

function delete() {
    Auth::requireRole(['admin']); csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    if ($id === (int)Auth::user()['id']) { flash('error','No puedes eliminarte a ti mismo'); redirect('?r=usuarios/index'); }
    DB::conn()->prepare('DELETE FROM usuarios WHERE id=?')->execute([$id]);
    flash('success','Usuario eliminado'); redirect('?r=usuarios/index');
}
