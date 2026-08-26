<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\Inventario;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::firstOrCreate(['nombre' => 'admin'], ['descripcion' => 'Administrador total', 'activo' => true]);
        $vendedorRole = Role::firstOrCreate(['nombre' => 'vendedor'], ['descripcion' => 'Vendedor y POS', 'activo' => true]);
        $almacenRole = Role::firstOrCreate(['nombre' => 'almacen'], ['descripcion' => 'Gestión de almacén', 'activo' => true]);
        $clienteRole = Role::firstOrCreate(['nombre' => 'cliente_consulta'], ['descripcion' => 'Consulta de catálogo', 'activo' => true]);

        // 2. Usuarios por defecto
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'nombre' => 'Administrador del Sistema',
                'email' => 'admin@sistemasiu.local',
                'password_hash' => Hash::make('admin123'),
                'rol_id' => $adminRole->id,
                'telefono' => '6000-0000',
                'estado' => true,
            ]
        );

        User::firstOrCreate(
            ['username' => 'vendedor'],
            [
                'nombre' => 'Juan Vendedor',
                'email' => 'vendedor@sistemasiu.local',
                'password_hash' => Hash::make('vendedor123'),
                'rol_id' => $vendedorRole->id,
                'telefono' => '6000-0001',
                'estado' => true,
            ]
        );

        // 3. Categoría inicial
        $catGral = Categoria::firstOrCreate(['nombre' => 'General'], ['descripcion' => 'Categoría general por defecto']);
        $catElec = Categoria::firstOrCreate(['nombre' => 'Electrónica'], ['descripcion' => 'Dispositivos y periféricos']);

        // 4. Cliente por defecto
        Cliente::firstOrCreate(
            ['codigo' => 'CLI-0001'],
            [
                'nombre' => 'Cliente General',
                'cedula_ruc' => '0-000-000',
                'tipo_cliente' => 'regular',
                'telefono' => '0000-0000',
                'descuento_porcentaje' => 0.00,
            ]
        );

        // 5. Proveedor inicial
        $prov = Proveedor::firstOrCreate(
            ['nombre' => 'Distribuidora Central'],
            [
                'ruc' => '8-888-8888',
                'categoria_id' => $catGral->id,
                'tipo_proveedor' => 'distribuidor',
                'contacto' => 'Carlos Pérez',
                'telefono' => '222-3333',
            ]
        );

        // 6. Producto de prueba
        $prod = Producto::firstOrCreate(
            ['codigo' => 'PROD-001'],
            [
                'codigo_barras' => '7501234567890',
                'nombre' => 'Teclado USB Genérico',
                'descripcion' => 'Teclado de oficina estándar',
                'categoria_id' => $catElec->id,
                'proveedor_id' => $prov->id,
                'precio_compra' => 5.00,
                'precio_venta' => 12.50,
                'stock_minimo' => 5,
                'unidad_medida' => 'pza',
            ]
        );

        Inventario::firstOrCreate(
            ['producto_id' => $prod->id],
            ['stock_actual' => 50, 'stock_reservado' => 0]
        );
    }
}
