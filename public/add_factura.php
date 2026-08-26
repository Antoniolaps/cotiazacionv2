<?php
require_once __DIR__ . '/../includes/db.php';
try {
    DB::conn()->exec("ALTER TABLE ordenes_compra ADD COLUMN numero_factura VARCHAR(100) NULL AFTER numero_orden");
    echo "Success: Column added.";
} catch(Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column already exists.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
