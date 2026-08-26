DELIMITER $$
DROP TRIGGER IF EXISTS trg_detalle_ventas_after_insert$$
CREATE TRIGGER trg_detalle_ventas_after_insert
AFTER INSERT ON detalle_ventas
FOR EACH ROW
BEGIN
    DECLARE v_stock_actual INT DEFAULT 0;
    DECLARE v_stock_nuevo INT DEFAULT 0;
    DECLARE v_usuario_id INT DEFAULT NULL;
    DECLARE v_estado VARCHAR(50);

    SELECT estado, vendedor_id
    INTO v_estado, v_usuario_id
    FROM ventas
    WHERE id = NEW.venta_id
    LIMIT 1;

    IF v_estado != 'cotizacion' THEN
        SELECT COALESCE(stock_actual, 0)
        INTO v_stock_actual
        FROM inventario
        WHERE producto_id = NEW.producto_id;

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
DELIMITER ;
