-- =====================================================================
-- scheCONtroll - Sistema de Inventario
-- Esquema MySQL CORREGIDO
-- =====================================================================

CREATE DATABASE IF NOT EXISTS schecontroll
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE schecontroll;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS pagos;
DROP TABLE IF EXISTS detalle_ventas;
DROP TABLE IF EXISTS ventas;
DROP TABLE IF EXISTS detalle_orden_compra;
DROP TABLE IF EXISTS ordenes_compra;
DROP TABLE IF EXISTS movimientos_inventario;
DROP TABLE IF EXISTS inventario;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS proveedores;
DROP TABLE IF EXISTS categoria;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS log_actividades;
DROP TABLE IF EXISTS respaldos_backup;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS roles;
SET FOREIGN_KEY_CHECKS = 1;

-- -------------------- ROLES --------------------
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE,
  descripcion VARCHAR(255),
  permisos TEXT,
  activo BOOLEAN DEFAULT TRUE,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -------------------- USUARIOS --------------------
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(150) UNIQUE NULL,
  password_hash VARCHAR(255) NOT NULL,
  rol_id INT,
  telefono VARCHAR(20),
  estado BOOLEAN DEFAULT TRUE,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
  ultimo_login DATETIME NULL,
  FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -------------------- CATEGORIA --------------------
CREATE TABLE categoria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  activo BOOLEAN DEFAULT TRUE,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_nombre (nombre)
) ENGINE=InnoDB;

-- -------------------- PROVEEDORES --------------------
CREATE TABLE proveedores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  descripcion TEXT,
  ruc VARCHAR(20) UNIQUE,
  categoria_id INT,
  tipo_proveedor ENUM('distribuidor','fabricante','importador','mayorista','otro') DEFAULT 'distribuidor',
  sitio_web VARCHAR(100),
  tiempo_entrega_dias INT DEFAULT 0,
  contacto VARCHAR(255),
  telefono VARCHAR(20),
  email VARCHAR(255),
  direccion TEXT,
  activo BOOLEAN DEFAULT TRUE,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_nombre (nombre),
  FOREIGN KEY (categoria_id) REFERENCES categoria(id) ON DELETE SET NULL
) ENGINE=InnoDB;



-- -------------------- CLIENTES --------------------
CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(20) NOT NULL UNIQUE,
  nombre VARCHAR(150) NOT NULL,
  cedula_ruc VARCHAR(20) UNIQUE,
  tipo_cliente ENUM('regular','mayorista','corporativo') DEFAULT 'regular',
  telefono VARCHAR(20),
  email VARCHAR(100),
  direccion TEXT,
  descuento_porcentaje DECIMAL(5,2) DEFAULT 0.00,
  activo BOOLEAN DEFAULT TRUE,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT cns_clientes_descuento CHECK (descuento_porcentaje >= 0 AND descuento_porcentaje <= 100)
) ENGINE=InnoDB;

-- -------------------- PRODUCTOS --------------------

CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(50) NOT NULL UNIQUE,
  codigo_barras VARCHAR(50) UNIQUE,
  nombre VARCHAR(150) NOT NULL,
  descripcion TEXT,
  categoria_id INT,
  proveedor_id INT,
  precio_compra DECIMAL(12,2) NOT NULL DEFAULT 0,
  precio_venta DECIMAL(12,2) NOT NULL DEFAULT 0,
  stock_minimo INT DEFAULT 0,
  unidad_medida VARCHAR(20) DEFAULT 'pza',
  activo BOOLEAN DEFAULT TRUE,
  fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_categoria (categoria_id),
  CONSTRAINT cns_productos_pc CHECK (precio_compra >= 0),
  CONSTRAINT cns_productos_pv CHECK (precio_venta >= 0),
  FOREIGN KEY (categoria_id) REFERENCES categoria(id) ON DELETE SET NULL,
  FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -------------------- INVENTARIO --------------------
CREATE TABLE inventario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  producto_id INT NOT NULL UNIQUE,
  stock_actual INT NOT NULL DEFAULT 0,
  stock_reservado INT DEFAULT 0,
  ultima_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT cns_inv_stock CHECK (stock_actual >= 0),
  CONSTRAINT cns_inv_reservado CHECK (stock_reservado >= 0),
  FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -------------------- ORDENES DE COMPRA --------------------
CREATE TABLE ordenes_compra (
  id INT AUTO_INCREMENT PRIMARY KEY,
  numero_orden VARCHAR(50) NOT NULL UNIQUE,
  numero_factura VARCHAR(50) NOT NULL UNIQUE,
  proveedor_id INT NOT NULL,
  usuario_id INT,
  fecha_orden DATETIME DEFAULT CURRENT_TIMESTAMP,
  fecha_entrega_esperada DATE,
  estado ENUM('pendiente','aprobada','recibida','cancelada','parcial') DEFAULT 'pendiente',
  subtotal DECIMAL(12,2) DEFAULT 0,
  itbms DECIMAL(12,2) DEFAULT 0,
  total DECIMAL(12,2) DEFAULT 0,
  observaciones TEXT,
  INDEX idx_estado (estado),
  FOREIGN KEY (proveedor_id) REFERENCES proveedores(id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE detalle_orden_compra (
  id INT AUTO_INCREMENT PRIMARY KEY,
  orden_compra_id INT NOT NULL,
  producto_id INT NOT NULL,
  cantidad_pedida INT NOT NULL,
  cantidad_recibida INT DEFAULT 0,
  precio_unitario DECIMAL(12,2) NOT NULL,
  subtotal DECIMAL(12,2) NOT NULL,
  estado ENUM('pendiente','recibido','parcial') DEFAULT 'pendiente',
  INDEX idx_orden (orden_compra_id),
  FOREIGN KEY (orden_compra_id) REFERENCES ordenes_compra(id) ON DELETE CASCADE,
  FOREIGN KEY (producto_id) REFERENCES productos(id)
) ENGINE=InnoDB;

-- -------------------- VENTAS --------------------
CREATE TABLE ventas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  numero_factura VARCHAR(50) NOT NULL UNIQUE,
  cliente_id INT,
  vendedor_id INT,
  fecha_venta DATETIME DEFAULT CURRENT_TIMESTAMP,
  subtotal DECIMAL(12,2) DEFAULT 0,
  descuento_total DECIMAL(12,2) DEFAULT 0,
  itbms DECIMAL(12,2) DEFAULT 0,
  total DECIMAL(12,2) DEFAULT 0,
  estado ENUM('pendiente','pagada','anulada','parcial','cotizacion') DEFAULT 'pendiente',
  observaciones TEXT,
  INDEX idx_fecha (fecha_venta),
  INDEX idx_estado (estado),
  CONSTRAINT cns_ventas_subtotal CHECK (subtotal >= 0),
  CONSTRAINT cns_ventas_desc CHECK (descuento_total >= 0),
  CONSTRAINT cns_ventas_itbms CHECK (itbms >= 0),
  CONSTRAINT cns_ventas_total CHECK (total >= 0),
  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL,
  FOREIGN KEY (vendedor_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;



CREATE TABLE detalle_ventas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venta_id INT NOT NULL,
  producto_id INT NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(12,2) NOT NULL,
  descuento DECIMAL(12,2) DEFAULT 0,
  subtotal DECIMAL(12,2) NOT NULL,
  INDEX idx_venta (venta_id),
  FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
  FOREIGN KEY (producto_id) REFERENCES productos(id)
) ENGINE=InnoDB;

-- -------------------- PAGOS --------------------
-- Ampliada para actuar como pasarela de pagos: guarda el resultado de la
-- autorización, permite pagos mixtos (varias filas por venta_id) y separa
-- el manejo de efectivo (monto recibido / cambio) del de medios electrónicos.
CREATE TABLE pagos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venta_id INT NOT NULL,
  monto DECIMAL(12,2) NOT NULL,
  moneda VARCHAR(3) NOT NULL DEFAULT 'USD',
  monto_recibido DECIMAL(12,2) NULL,
  cambio DECIMAL(12,2) NULL DEFAULT 0,
  fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
  metodo_pago ENUM('efectivo','tarjeta_credito','tarjeta_debito','transferencia','yappy','nequi','vale','gift_card','cheque','deposito') NOT NULL,
  estado ENUM('pendiente','aprobado','rechazado','anulado') NOT NULL DEFAULT 'pendiente',
  codigo_autorizacion VARCHAR(50),
  referencia VARCHAR(100),
  terminal_id VARCHAR(50),
  mensaje_respuesta VARCHAR(255),
  usuario_id INT,
  observaciones TEXT,
  INDEX idx_fecha (fecha_pago),
  INDEX idx_venta (venta_id),
  INDEX idx_estado (estado),
  CONSTRAINT cns_pagos_monto CHECK (monto > 0),
  CONSTRAINT cns_pagos_cambio CHECK (cambio IS NULL OR cambio >= 0),
  FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -------------------- MOVIMIENTOS INVENTARIO --------------------
CREATE TABLE movimientos_inventario (
  id INT AUTO_INCREMENT PRIMARY KEY,
  producto_id INT NOT NULL,
  usuario_id INT,
  tipo_movimiento ENUM('entrada','salida','ajuste','devolucion') NOT NULL,
  cantidad INT NOT NULL,
  venta_id INT NULL,
  orden_compra_id INT NULL,
  fecha_movimiento DATETIME DEFAULT CURRENT_TIMESTAMP,
  descripcion TEXT,
  stock_anterior INT NOT NULL,
  stock_nuevo INT NOT NULL,
  observaciones TEXT,
  INDEX idx_producto (producto_id),
  INDEX idx_fecha (fecha_movimiento),
  INDEX idx_tipo (tipo_movimiento),
  FOREIGN KEY (producto_id) REFERENCES productos(id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
  FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE SET NULL,
  FOREIGN KEY (orden_compra_id) REFERENCES ordenes_compra(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -------------------- LOG ACTIVIDADES --------------------
CREATE TABLE log_actividades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT,
  rol_id INT,
  accion ENUM('INSERT','UPDATE','DELETE','LOGIN','LOGOUT','CREATE','ALTER','DROP','SELECT','VIEW') NOT NULL,
  tabla_afectada VARCHAR(100) NOT NULL,
  registro_id INT,
  cambios_anteriores TEXT,
  cambios_nuevos TEXT,
  ip_address VARCHAR(45),
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_usuario (usuario_id),
  INDEX idx_rol (rol_id),
  INDEX idx_tabla (tabla_afectada),
  INDEX idx_fecha (fecha),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
  FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -------------------- RESPALDOS --------------------
CREATE TABLE respaldos_backup (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre_archivo VARCHAR(255) NOT NULL,
  ruta VARCHAR(255),
  tamano_bytes BIGINT,
  usuario_id INT,
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  tipo_respaldo ENUM('COMPLETO','PARCIAL') NOT NULL DEFAULT 'COMPLETO',
  tablas_incluidas TEXT,
  periodo_retencion_dias INT DEFAULT 60,
  fecha_expiracion DATETIME,
  observaciones TEXT,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================================
-- TRIGGER: crea automáticamente el registro de inventario al insertar
-- un producto (evita el bug que reportaste: stock que se queda
-- desincronizado al ingresar un producto nuevo).
-- =====================================================================
DELIMITER $$

CREATE TRIGGER trg_productos_after_insert
AFTER INSERT ON productos
FOR EACH ROW
BEGIN
    INSERT INTO inventario (producto_id, stock_actual, stock_reservado)
    VALUES (NEW.id, 0, 0);
END$$

CREATE TRIGGER trg_detalle_ventas_after_insert
AFTER INSERT ON detalle_ventas
FOR EACH ROW
BEGIN
    DECLARE v_stock_actual INT DEFAULT 0;
    DECLARE v_stock_nuevo INT DEFAULT 0;
    DECLARE v_usuario_id INT DEFAULT NULL;
    DECLARE v_estado VARCHAR(50);

    SELECT COALESCE(stock_actual, 0)
    INTO v_stock_actual
    FROM inventario
    WHERE producto_id = NEW.producto_id;

    SELECT estado, vendedor_id
    INTO v_estado, v_usuario_id
    FROM ventas
    WHERE id = NEW.venta_id
    LIMIT 1;

    IF v_estado != 'cotizacion' THEN
        SET v_stock_nuevo = v_stock_actual - NEW.cantidad;

        INSERT INTO inventario (producto_id, stock_actual, stock_reservado)
        VALUES (NEW.producto_id, v_stock_nuevo, 0)
        ON DUPLICATE KEY UPDATE stock_actual = v_stock_nuevo;

        INSERT INTO movimientos_inventario (
            producto_id,
            usuario_id,
            tipo_movimiento,
            cantidad,
            venta_id,
            descripcion,
            stock_anterior,
            stock_nuevo
        ) VALUES (
            NEW.producto_id,
            v_usuario_id,
            'salida',
            NEW.cantidad,
            NEW.venta_id,
            'Venta',
            v_stock_actual,
            v_stock_nuevo
        );
    END IF;
END$$

-- =====================================================================
-- TRIGGERS: confirman la venta automáticamente según lo cobrado en
-- "pagos". Esto reemplaza la necesidad de una tabla aparte de
-- transacciones: el estado de la pasarela vive en pagos.estado y aquí
-- se propaga hacia ventas.estado (pendiente -> parcial -> pagada).
-- =====================================================================
CREATE TRIGGER trg_pagos_after_insert
AFTER INSERT ON pagos
FOR EACH ROW
BEGIN
    DECLARE v_total DECIMAL(12,2) DEFAULT 0;
    DECLARE v_pagado DECIMAL(12,2) DEFAULT 0;
    DECLARE v_estado_venta VARCHAR(20);

    IF NEW.estado = 'aprobado' THEN
        SELECT total, estado INTO v_total, v_estado_venta
        FROM ventas WHERE id = NEW.venta_id;

        SELECT COALESCE(SUM(monto), 0) INTO v_pagado
        FROM pagos
        WHERE venta_id = NEW.venta_id AND estado = 'aprobado';

        IF v_estado_venta NOT IN ('anulada','cotizacion') THEN
            IF v_pagado >= v_total THEN
                UPDATE ventas SET estado = 'pagada' WHERE id = NEW.venta_id;
            ELSEIF v_pagado > 0 THEN
                UPDATE ventas SET estado = 'parcial' WHERE id = NEW.venta_id;
            END IF;
        END IF;
    END IF;
END$$

CREATE TRIGGER trg_pagos_after_update
AFTER UPDATE ON pagos
FOR EACH ROW
BEGIN
    DECLARE v_total DECIMAL(12,2) DEFAULT 0;
    DECLARE v_pagado DECIMAL(12,2) DEFAULT 0;
    DECLARE v_estado_venta VARCHAR(20);

    -- Solo recalcula si el estado del pago realmente cambió
    -- (por ejemplo, la pasarela confirma un pago que estaba 'pendiente').
    IF NEW.estado <> OLD.estado THEN
        SELECT total, estado INTO v_total, v_estado_venta
        FROM ventas WHERE id = NEW.venta_id;

        SELECT COALESCE(SUM(monto), 0) INTO v_pagado
        FROM pagos
        WHERE venta_id = NEW.venta_id AND estado = 'aprobado';

        IF v_estado_venta NOT IN ('anulada','cotizacion') THEN
            IF v_pagado >= v_total AND v_total > 0 THEN
                UPDATE ventas SET estado = 'pagada' WHERE id = NEW.venta_id;
            ELSEIF v_pagado > 0 THEN
                UPDATE ventas SET estado = 'parcial' WHERE id = NEW.venta_id;
            ELSE
                UPDATE ventas SET estado = 'pendiente' WHERE id = NEW.venta_id AND estado NOT IN ('pagada');
            END IF;
        END IF;
    END IF;
END$$

DELIMITER ;