<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Roles
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 50)->unique();
                $table->string('descripcion', 255)->nullable();
                $table->text('permisos')->nullable();
                $table->boolean('activo')->default(true);
                $table->dateTime('fecha_creacion')->useCurrent();
            });
        }

        // Usuarios
        if (!Schema::hasTable('usuarios')) {
            Schema::create('usuarios', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 100);
                $table->string('username', 50)->unique();
                $table->string('email', 150)->unique()->nullable();
                $table->string('password_hash', 255);
                $table->foreignId('rol_id')->nullable()->constrained('roles')->nullOnDelete();
                $table->string('telefono', 20)->nullable();
                $table->boolean('estado')->default(true);
                $table->dateTime('fecha_creacion')->useCurrent();
                $table->dateTime('ultimo_login')->nullable();
            });
        }

        // Categoria
        if (!Schema::hasTable('categoria')) {
            Schema::create('categoria', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 100);
                $table->text('descripcion')->nullable();
                $table->boolean('activo')->default(true);
                $table->dateTime('fecha_creacion')->useCurrent();
                $table->index('nombre');
            });
        }

        // Proveedores
        if (!Schema::hasTable('proveedores')) {
            Schema::create('proveedores', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 255);
                $table->text('descripcion')->nullable();
                $table->string('ruc', 20)->unique()->nullable();
                $table->foreignId('categoria_id')->nullable()->constrained('categoria')->nullOnDelete();
                $table->enum('tipo_proveedor', ['distribuidor','fabricante','importador','mayorista','otro'])->default('distribuidor');
                $table->string('sitio_web', 100)->nullable();
                $table->integer('tiempo_entrega_dias')->default(0);
                $table->string('contacto', 255)->nullable();
                $table->string('telefono', 20)->nullable();
                $table->string('email', 255)->nullable();
                $table->text('direccion')->nullable();
                $table->boolean('activo')->default(true);
                $table->dateTime('fecha_creacion')->useCurrent();
            });
        }

        // Clientes
        if (!Schema::hasTable('clientes')) {
            Schema::create('clientes', function (Blueprint $table) {
                $table->id();
                $table->string('codigo', 20)->unique();
                $table->string('nombre', 150);
                $table->string('cedula_ruc', 20)->unique()->nullable();
                $table->enum('tipo_cliente', ['regular','mayorista','corporativo'])->default('regular');
                $table->string('telefono', 20)->nullable();
                $table->string('email', 100)->nullable();
                $table->text('direccion')->nullable();
                $table->decimal('descuento_porcentaje', 5, 2)->default(0.00);
                $table->boolean('activo')->default(true);
                $table->dateTime('fecha_creacion')->useCurrent();
            });
        }

        // Productos
        if (!Schema::hasTable('productos')) {
            Schema::create('productos', function (Blueprint $table) {
                $table->id();
                $table->string('codigo', 50)->unique();
                $table->string('codigo_barras', 50)->unique()->nullable();
                $table->string('nombre', 150);
                $table->text('descripcion')->nullable();
                $table->foreignId('categoria_id')->nullable()->constrained('categoria')->nullOnDelete();
                $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->nullOnDelete();
                $table->decimal('precio_compra', 12, 2)->default(0);
                $table->decimal('precio_venta', 12, 2)->default(0);
                $table->integer('stock_minimo')->default(0);
                $table->string('unidad_medida', 20)->default('pza');
                $table->boolean('activo')->default(true);
                $table->dateTime('fecha_creacion')->useCurrent();
            });
        }

        // Inventario
        if (!Schema::hasTable('inventario')) {
            Schema::create('inventario', function (Blueprint $table) {
                $table->id();
                $table->foreignId('producto_id')->unique()->constrained('productos')->cascadeOnDelete();
                $table->integer('stock_actual')->default(0);
                $table->integer('stock_reservado')->default(0);
                $table->dateTime('ultima_actualizacion')->useCurrent();
            });
        }

        // Ordenes de Compra
        if (!Schema::hasTable('ordenes_compra')) {
            Schema::create('ordenes_compra', function (Blueprint $table) {
                $table->id();
                $table->string('numero_orden', 50)->unique();
                $table->string('numero_factura', 50)->unique();
                $table->foreignId('proveedor_id')->constrained('proveedores');
                $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
                $table->dateTime('fecha_orden')->useCurrent();
                $table->date('fecha_entrega_esperada')->nullable();
                $table->enum('estado', ['pendiente','aprobada','recibida','cancelada','parcial'])->default('pendiente');
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('itbms', 12, 2)->default(0);
                $table->decimal('total', 12, 2)->default(0);
                $table->text('observaciones')->nullable();
            });

            Schema::create('detalle_orden_compra', function (Blueprint $table) {
                $table->id();
                $table->foreignId('orden_compra_id')->constrained('ordenes_compra')->cascadeOnDelete();
                $table->foreignId('producto_id')->constrained('productos');
                $table->integer('cantidad_pedida');
                $table->integer('cantidad_recibida')->default(0);
                $table->decimal('precio_unitario', 12, 2);
                $table->decimal('subtotal', 12, 2);
                $table->enum('estado', ['pendiente','recibido','parcial'])->default('pendiente');
            });
        }

        // Ventas
        if (!Schema::hasTable('ventas')) {
            Schema::create('ventas', function (Blueprint $table) {
                $table->id();
                $table->string('numero_factura', 50)->unique();
                $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
                $table->foreignId('vendedor_id')->nullable()->constrained('usuarios')->nullOnDelete();
                $table->dateTime('fecha_venta')->useCurrent();
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('descuento_total', 12, 2)->default(0);
                $table->decimal('itbms', 12, 2)->default(0);
                $table->decimal('total', 12, 2)->default(0);
                $table->enum('estado', ['pendiente','pagada','anulada','parcial','cotizacion'])->default('pendiente');
                $table->text('observaciones')->nullable();
            });

            Schema::create('detalle_ventas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('venta_id')->constrained('ventas')->cascadeOnDelete();
                $table->foreignId('producto_id')->constrained('productos');
                $table->integer('cantidad');
                $table->decimal('precio_unitario', 12, 2);
                $table->decimal('descuento', 12, 2)->default(0);
                $table->decimal('subtotal', 12, 2);
            });
        }

        // Pagos
        if (!Schema::hasTable('pagos')) {
            Schema::create('pagos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('venta_id')->constrained('ventas')->cascadeOnDelete();
                $table->decimal('monto', 12, 2);
                $table->string('moneda', 3)->default('USD');
                $table->decimal('monto_recibido', 12, 2)->nullable();
                $table->decimal('cambio', 12, 2)->default(0)->nullable();
                $table->dateTime('fecha_pago')->useCurrent();
                $table->enum('metodo_pago', ['efectivo','tarjeta_credito','tarjeta_debito','transferencia','yappy','nequi','vale','gift_card','cheque','deposito'])->default('efectivo');
                $table->enum('estado', ['pendiente','aprobado','rechazado','anulado'])->default('pendiente');
                $table->string('codigo_autorizacion', 50)->nullable();
                $table->string('referencia', 100)->nullable();
                $table->string('terminal_id', 50)->nullable();
                $table->string('mensaje_respuesta', 255)->nullable();
                $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
                $table->text('observaciones')->nullable();
            });
        }

        // Movimientos Inventario
        if (!Schema::hasTable('movimientos_inventario')) {
            Schema::create('movimientos_inventario', function (Blueprint $table) {
                $table->id();
                $table->foreignId('producto_id')->constrained('productos');
                $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
                $table->enum('tipo_movimiento', ['entrada','salida','ajuste','devolucion']);
                $table->integer('cantidad');
                $table->foreignId('venta_id')->nullable()->constrained('ventas')->nullOnDelete();
                $table->foreignId('orden_compra_id')->nullable()->constrained('ordenes_compra')->nullOnDelete();
                $table->dateTime('fecha_movimiento')->useCurrent();
                $table->text('descripcion')->nullable();
                $table->integer('stock_anterior');
                $table->integer('stock_nuevo');
                $table->text('observaciones')->nullable();
            });
        }

        // Log Actividades
        if (!Schema::hasTable('log_actividades')) {
            Schema::create('log_actividades', function (Blueprint $table) {
                $table->id();
                $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
                $table->foreignId('rol_id')->nullable()->constrained('roles')->nullOnDelete();
                $table->enum('accion', ['INSERT','UPDATE','DELETE','LOGIN','LOGOUT','CREATE','ALTER','DROP','SELECT','VIEW']);
                $table->string('tabla_afectada', 100);
                $table->integer('registro_id')->nullable();
                $table->text('cambios_anteriores')->nullable();
                $table->text('cambios_nuevos')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->dateTime('fecha')->useCurrent();
            });
        }

        // Respaldos Backup
        if (!Schema::hasTable('respaldos_backup')) {
            Schema::create('respaldos_backup', function (Blueprint $table) {
                $table->id();
                $table->string('nombre_archivo', 255);
                $table->string('ruta', 255)->nullable();
                $table->bigInteger('tamano_bytes')->nullable();
                $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
                $table->dateTime('fecha')->useCurrent();
                $table->enum('tipo_respaldo', ['COMPLETO','PARCIAL'])->default('COMPLETO');
                $table->text('tablas_incluidas')->nullable();
                $table->integer('periodo_retencion_dias')->default(60);
                $table->dateTime('fecha_expiracion')->nullable();
                $table->text('observaciones')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('respaldos_backup');
        Schema::dropIfExists('log_actividades');
        Schema::dropIfExists('movimientos_inventario');
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('detalle_ventas');
        Schema::dropIfExists('ventas');
        Schema::dropIfExists('detalle_orden_compra');
        Schema::dropIfExists('ordenes_compra');
        Schema::dropIfExists('inventario');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('proveedores');
        Schema::dropIfExists('categoria');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('roles');
    }
};
