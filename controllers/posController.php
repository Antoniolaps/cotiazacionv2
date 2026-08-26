<?php
// controllers/posController.php

class PosController {
    
    public function __construct() {
        // Inicializar modelos, autenticación, etc.
        // session_start();
        // if (!isset($_SESSION['user_id'])) { header('Location: /login'); exit; }
    }

    public function terminal() {
        // Cargar vista de la terminal principal
        require_once 'views/pos/terminal.php';
    }

    public function customerDisplay() {
        // Cargar vista de la pantalla del cliente (dual screen)
        require_once 'views/pos/customer_display.php';
    }

    // Endpoints para AJAX (ejemplo)
    public function getProducts() {
        // Aquí consultarías a la BD y devolverías JSON
        $products = [
            ['id' => 1, 'barcode' => '123456789', 'name' => 'Monitor Full HD', 'price' => 150.00, 'image' => 'monitor.jpg'],
            ['id' => 2, 'barcode' => '987654321', 'name' => 'Lector RFID', 'price' => 45.00, 'image' => 'rfid.jpg'],
            ['id' => 3, 'barcode' => '111222333', 'name' => 'Teclado Mecánico', 'price' => 80.00, 'image' => 'keyboard.jpg'],
            ['id' => 4, 'barcode' => '444555666', 'name' => 'Mouse Inalámbrico', 'price' => 25.00, 'image' => 'mouse.jpg'],
        ];
        header('Content-Type: application/json');
        echo json_encode($products);
    }

    // public function index() {
    //     require_once 'views/pos/terminal.php';
    // }
    
    // public function customer_display() {
    //     require_once 'views/pos/customer_display.php';
    // }
    
    // public function test() {
    //     require_once 'views/pos/test_printer.php';
    // }
    
    // public function api_products() {
    //     $data = [
    //         ['id' => 1, 'code' => '123456789', 'name' => 'Monitor 17" Full HD', 'price' => 150.00, 'stock' => 10],
    //         ['id' => 2, 'code' => '987654321', 'name' => 'Lector RFID', 'price' => 45.00, 'stock' => 25],
    //         ['id' => 3, 'code' => '111222333', 'name' => 'Teclado Mecánico RGB', 'price' => 80.00, 'stock' => 5],
    //         ['id' => 4, 'code' => '444555666', 'name' => 'Mouse Inalámbrico', 'price' => 25.00, 'stock' => 30],
    //         ['id' => 5, 'code' => '777888999', 'name' => 'Impresora Térmica 80mm', 'price' => 120.00, 'stock' => 8],
    //         ['id' => 6, 'code' => '555666777', 'name' => 'Cajón de Dinero Metálico', 'price' => 60.00, 'stock' => 12],
    //         ['id' => 7, 'code' => '123123123', 'name' => 'Cámara HD Web', 'price' => 35.00, 'stock' => 20],
    //         ['id' => 8, 'code' => '987987987', 'name' => 'Escáner de Código de Barras 2D', 'price' => 55.00, 'stock' => 15],
    //         ['id' => 9, 'code' => '101112131', 'name' => 'Tarjeta de Memoria 64GB', 'price' => 15.00, 'stock' => 50],
    //         ['id' => 10, 'code' => '141516171', 'name' => 'SSD 256GB', 'price' => 45.00, 'stock' => 18],
    //     ];
        
    //     header('Content-Type: application/json');
    //     echo json_encode($data);
    // }
}
