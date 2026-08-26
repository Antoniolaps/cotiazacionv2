# FerrePlus — Sistema de Inventario y POS (Laravel 11 + MySQL)

Sistema de gestión empresarial basado en el esquema `schecontroll`, con roles
**admin / vendedor / almacén / cliente_consulta**, terminal POS de ventas,
**módulo de cotizaciones**, facturación con ITBMS (7%), registro de pagos,
órdenes de compra y movimientos de inventario.

---

## 📋 Requisitos

- **PHP** 8.2+ con extensiones `pdo_mysql`, `zip`, `mbstring`, `openssl`, `bcmath`
- **MySQL** 5.7+ / MariaDB 10.3+
- **Composer** (gestor de dependencias PHP)
- **XAMPP** (recomendado en Windows) o Apache/Nginx

---

## 📦 1. Dependencias (Composer)

```powershell
# Descargar Composer (solo la primera vez)
cd c:\xampp\htdocs\sistemSIU
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# Instalar todos los paquetes definidos en composer.json
php composer.phar install

# Actualizar paquetes
php composer.phar update

# Regenerar autoload (al agregar clases nuevas)
php composer.phar dump-autoload

# Ver paquetes instalados
php composer.phar show
```

---

## ⚙️ 2. Instalación Inicial (Primera vez)

```powershell
# 1. Ir al directorio del proyecto
cd c:\xampp\htdocs\sistemSIU

# 2. Instalar dependencias
php composer.phar install

# 3. Crear el archivo de entorno
copy .env.example .env

# 4. Generar la clave de encriptación
php artisan key:generate

# 5. Crear las tablas en la base de datos
php artisan migrate

# 6. Poblar con datos iniciales (admin, roles, productos demo)
php artisan db:seed

# 7. Limpiar y re-cachear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## 🚀 3. Ejecución (Arrancar el Servidor)

```powershell
# Opción A — XAMPP (Recomendado en Windows)
#  1. Abrir XAMPP Control Panel
#  2. Iniciar Apache y MySQL
#  3. Abrir: http://localhost/sistemSIU/public

# Opción B — Servidor de desarrollo Laravel
cd c:\xampp\htdocs\sistemSIU
php artisan serve

# Con puerto personalizado
php artisan serve --port=8080

# Accesible desde red local
php artisan serve --host=0.0.0.0 --port=8000
```

---

## 🔧 4. Fix — Problemas Comunes

### 🔴 Class not found / Autoload
```powershell
php composer.phar dump-autoload
php artisan config:clear
```

### 🔴 No application encryption key
```powershell
php artisan key:generate
```

### 🔴 SQLSTATE / Error de conexión a DB
```powershell
# Verificar .env:
# DB_HOST=127.0.0.1
# DB_DATABASE=schecontroll
# DB_USERNAME=root
# DB_PASSWORD=

# Revertir y re-crear todas las tablas
php artisan migrate:fresh --seed
```

### 🔴 Error 500 / Vista no encontrada
```powershell
php artisan view:clear
php artisan cache:clear
php artisan config:cache
```

### 🔴 Error 404 / Rutas no encontradas
```powershell
php artisan route:clear
php artisan route:cache
php artisan route:list
```

### 🔴 Permission denied / Storage
```powershell
# PowerShell como Administrador:
icacls "c:\xampp\htdocs\sistemSIU\storage" /grant Everyone:F /T
icacls "c:\xampp\htdocs\sistemSIU\bootstrap\cache" /grant Everyone:F /T
```

### 🔴 Composer / extension=zip no habilitada
```powershell
# Abrir C:\xampp\php\php.ini y cambiar:
#   ;extension=zip   →   extension=zip
# Reiniciar Apache en XAMPP, luego:
php composer.phar install
```

---

## 🗄️ 5. Base de Datos

```powershell
# Ver estado de las migraciones
php artisan migrate:status

# Ejecutar migraciones pendientes
php artisan migrate

# Revertir última migración
php artisan migrate:rollback

# Revertir todas y volver a ejecutar
php artisan migrate:fresh

# Revertir todas, ejecutar y poblar seeders
php artisan migrate:fresh --seed

# Correr solo los seeders
php artisan db:seed

# Seeder específico
php artisan db:seed --class=DatabaseSeeder
```

---

## 🛣️ 6. Rutas

```powershell
# Ver todas las rutas registradas
php artisan route:list

# Filtrar por módulo
php artisan route:list --path=cotizaciones
php artisan route:list --path=ventas
php artisan route:list --path=pos

# Limpiar y re-cachear rutas
php artisan route:clear
php artisan route:cache
```

---

## 🏗️ 7. Generadores Artisan

```powershell
# Nuevo controlador
php artisan make:controller NombreController

# Controlador con CRUD completo
php artisan make:controller NombreController --resource

# Modelo + migración juntos
php artisan make:model Nombre -m

# Solo migración
php artisan make:migration create_nombre_table

# Seeder
php artisan make:seeder NombreSeeder

# Middleware
php artisan make:middleware NombreMiddleware

# Form Request (validación)
php artisan make:request NombreRequest

# Blade Component
php artisan make:component NombreComponente
```

---

## 🧹 8. Limpiar Caché (Fix General)

```powershell
# Limpiar TODO de una vez
php artisan optimize:clear

# Individual
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# Re-cachear todo (producción)
php artisan optimize
```

---

## 🔑 9. Credenciales por Defecto

| Usuario    | Contraseña    | Rol        | Acceso                                   |
|------------|---------------|------------|------------------------------------------|
| `admin`    | `********`    | Admin      | Todo el sistema                          |
| `vendedor` | `********`    | Vendedor   | POS, cotizaciones, facturas, pagos       |

---

## 🗂️ 10. Estructura del Proyecto

```
app/
  Actions/           Lógica de negocio por módulo (CreateSaleAction, CreateQuoteAction…)
  Http/Controllers/  Un controlador por módulo
  Models/            Modelos Eloquent mapeados a schecontroll
  Services/          Servicios compartidos (ActivityLogger)
resources/views/
  components/        Blade components (x-app-layout, x-stat-card, x-badge)
  cotizaciones/      Listado, creación y formato imprimible de cotizaciones
  pos/               Terminal POS
  productos/         CRUD de productos
  ...
routes/web.php       48 rutas agrupadas por rol
database/migrations/ Migraciones de las 15 tablas del esquema
public/assets/       CSS, JS (PosTerminal.js)
```

---

## 👥 Roles y Módulos

Sistema restringido por red local (IP local). No accesible desde internet sin VPN.

| Rol               | Acceso                                                                 |
|-------------------|------------------------------------------------------------------------|
| `admin`           | Todo el sistema                                                        |
| `vendedor`        | POS, cotizaciones, facturas, clientes, pagos, consulta de productos    |
| `almacen`         | Productos, categorías, proveedores, inventario, compras, cotizaciones  |
| `cliente_consulta`| Solo consulta de catálogo de productos                                 |

---

## 📋 Checklist de Inicio Rápido

```powershell
cd c:\xampp\htdocs\sistemSIU
php composer.phar install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan route:list
php artisan serve
```

---

## 📝 Notas Técnicas

- CSRF activo en todos los formularios POST.
- Cada venta y recepción de OC genera movimiento en `movimientos_inventario`.
- `log_actividades` registra LOGIN/LOGOUT y operaciones CRUD sobre las tablas principales.
- ITBMS por defecto: **7%** (Panamá). Ajustable en `.env` → `ITBMS_RATE=0.07`.
- Las **cotizaciones** usan `estado = 'cotizacion'` en la tabla `ventas` y **no descuentan stock** hasta ser convertidas en venta formal.
