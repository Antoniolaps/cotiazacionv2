<?php
/**
 * CRUD genérico configurable por entidad.
 * Renderiza vistas en /views/<module>/(index|form).php
 */
class Crud {
    public string $table;
    public string $module;
    public array  $fields;   // ['col' => ['label'=>..., 'type'=>'text|number|select|textarea|checkbox', 'options'=>[], 'required'=>true]]
    public array  $columns;  // columnas visibles en index ['col'=>'Label', ...]
    public array  $roles;    // roles autorizados
    public string $title;

    public function __construct(array $cfg) {
        foreach ($cfg as $k=>$v) $this->$k = $v;
    }

    public function dispatch(string $action): void {
        Auth::requireRole($this->roles);
        match($action) {
            'index'  => $this->index(),
            'create' => $this->form(),
            'edit'   => $this->form((int)($_GET['id'] ?? 0)),
            'store'  => $this->store(),
            'update' => $this->store((int)($_POST['id'] ?? 0)),
            'delete' => $this->delete((int)($_POST['id'] ?? 0)),
            'show'   => $this->show((int)($_GET['id'] ?? 0)),
            default  => $this->index(),
        };
    }

    private function index(): void {
        $q = trim((string)($_GET['q'] ?? ''));
        $page = max(1, (int)($_GET['p'] ?? 1));
        $limit = max(1, (int)($_GET['limit'] ?? 10));
        
        // Count query
        $sqlCount = "SELECT COUNT(*) FROM {$this->table}";
        $params = [];
        if ($q !== '' && isset($this->fields['nombre'])) {
            $sqlCount .= " WHERE nombre LIKE ?"; $params[] = "%$q%";
        }
        $stmt = DB::conn()->prepare($sqlCount); 
        $stmt->execute($params);
        $totalRows = (int)$stmt->fetchColumn();
        $totalPages = max(1, ceil($totalRows / $limit));
        
        if ($page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM {$this->table}";
        if ($q !== '' && isset($this->fields['nombre'])) {
            $sql .= " WHERE nombre LIKE ?"; 
        }
        $sql .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
        
        $stmt = DB::conn()->prepare($sql); 
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        
        $crud = $this; $title = $this->title;
        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/_crud_index.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    private function show(int $id): void {
        $stmt = DB::conn()->prepare("SELECT * FROM {$this->table} WHERE id=?");
        $stmt->execute([$id]); 
        $row = $stmt->fetch() ?: [];
        $crud = $this; $title = $this->title . ' - Detalle';
        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/_crud_show.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    private function form(?int $id = null): void {
        $row = [];
        if ($id) {
            $stmt = DB::conn()->prepare("SELECT * FROM {$this->table} WHERE id=?");
            $stmt->execute([$id]); $row = $stmt->fetch() ?: [];
        }
        $crud = $this; $title = $this->title . ($id ? ' - Editar' : ' - Nuevo');
        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/_crud_form.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    private function store(?int $id = null): void {
        csrf_check();
        $data = [];
        foreach ($this->fields as $col => $f) {
            $v = $_POST[$col] ?? null;
            if (($f['type'] ?? 'text') === 'checkbox') $v = isset($_POST[$col]) ? 1 : 0;
            if ($v === '') $v = null;
            $data[$col] = $v;
        }
        try {
            if ($id) {
                $set = implode(',', array_map(fn($c)=>"$c=:$c", array_keys($data)));
                $data['id'] = $id;
                DB::conn()->prepare("UPDATE {$this->table} SET $set WHERE id=:id")->execute($data);
                Activity::log('UPDATE', $this->table, $id, null, $data);
                flash('success', 'Registro actualizado');
            } else {
                $cols = implode(',', array_keys($data));
                $ph   = ':' . implode(',:', array_keys($data));
                DB::conn()->prepare("INSERT INTO {$this->table} ($cols) VALUES ($ph)")->execute($data);
                $newId = (int)DB::conn()->lastInsertId();
                Activity::log('INSERT', $this->table, $newId, null, $data);
                flash('success', 'Registro creado');
            }
        } catch (Throwable $e) {
            flash('error', 'Error: ' . $e->getMessage());
        }
        redirect("?r={$this->module}/index");
    }

    private function delete(int $id): void {
        csrf_check();
        try {
            DB::conn()->prepare("DELETE FROM {$this->table} WHERE id=?")->execute([$id]);
            Activity::log('DELETE', $this->table, $id);
            flash('success', 'Eliminado');
        } catch (Throwable $e) {
            flash('error', 'No se pudo eliminar: ' . $e->getMessage());
        }
        redirect("?r={$this->module}/index");
    }

    public function selectOptions(string $sourceTable, string $valueCol = 'id', string $labelCol = 'nombre'): array {
        return DB::conn()->query("SELECT $valueCol AS v, $labelCol AS l FROM $sourceTable WHERE activo=1 ORDER BY $labelCol")->fetchAll();
    }
}
