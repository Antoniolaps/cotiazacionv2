-- =====================================================================
-- scheCONtroll - Datos de prueba (Ferretería)
-- Respeta las FKs de schema.sql: roles->usuarios, categoria->proveedores,
-- categoria/proveedores->productos. El trigger trg_productos_after_insert
-- crea automáticamente la fila de 'inventario' por cada producto; luego
-- se actualiza con UPDATE para dejar stock realista.
-- =====================================================================
USE schecontroll;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE inventario;
TRUNCATE TABLE productos;
TRUNCATE TABLE proveedores;
TRUNCATE TABLE categoria;
TRUNCATE TABLE clientes;
TRUNCATE TABLE usuarios;
TRUNCATE TABLE roles;
SET FOREIGN_KEY_CHECKS = 1;

-- -------------------- ROLES --------------------
INSERT INTO roles (id, nombre, descripcion, permisos, activo) VALUES
(1, 'administrador', 'Acceso total al sistema', 'usuarios:CRUD,productos:CRUD,ventas:CRUD,reportes:CRUD,configuracion:CRUD', TRUE),
(2, 'almacen', 'Gestión de inventario, productos y órdenes de compra', 'productos:CRUD,inventario:CRUD,ordenes_compra:CRUD,proveedores:CRUD', TRUE),
(3, 'vendedor', 'Registro de ventas y atención al cliente', 'ventas:CRUD,clientes:CRUD,productos:READ,pagos:CRUD', TRUE),
(4, 'cliente', 'Acceso limitado de autoconsulta (portal del cliente)', 'ventas:READ_OWN,facturas:READ_OWN', TRUE);

-- -------------------- USUARIOS --------------------
INSERT INTO usuarios (id, nombre, username, email, password_hash, rol_id, telefono, estado) VALUES
(1, 'Ricardo Aizpurúa', 'radmin', 'ricardo.aizpurua@ferrepanama.com', '$2y$10$examplehash.admin0000000000000000000', 1, '6600-1001', TRUE),
(2, 'Yariela Bethancourt', 'yalmacen', 'yariela.bethancourt@ferrepanama.com', '$2y$10$examplehash.almacen000000000000000', 2, '6600-1002', TRUE),
(3, 'Manuel Quintero', 'mvendedor1', 'manuel.quintero@ferrepanama.com', '$2y$10$examplehash.vendedor10000000000000', 3, '6600-1003', TRUE),
(4, 'Lourdes Samaniego', 'lvendedor2', 'lourdes.samaniego@ferrepanama.com', '$2y$10$examplehash.vendedor20000000000000', 3, '6600-1004', TRUE),
(5, 'Cliente Portal', 'cliente.portal', 'portal@ferrepanama.com', '$2y$10$examplehash.clienteportal000000000', 4, NULL, TRUE);
-- NOTA: password_hash son valores de ejemplo (placeholder), no hashes
-- bcrypt reales. Reemplazar antes de usar en producción.

-- -------------------- CATEGORIA --------------------
INSERT INTO categoria (id, nombre, descripcion, activo) VALUES
(1, 'Herramientas Manuales', 'Productos de herramientas manuales', TRUE),
(2, 'Herramientas Eléctricas', 'Productos de herramientas eléctricas', TRUE),
(3, 'Tornillería y Fijación', 'Productos de tornillería y fijación', TRUE),
(4, 'Pinturas y Recubrimientos', 'Productos de pinturas y recubrimientos', TRUE),
(5, 'Plomería', 'Productos de plomería', TRUE),
(6, 'Electricidad', 'Productos de electricidad', TRUE),
(7, 'Cerrajería y Seguridad', 'Productos de cerrajería y seguridad', TRUE),
(8, 'Adhesivos y Selladores', 'Productos de adhesivos y selladores', TRUE),
(9, 'Jardín y Exteriores', 'Productos de jardín y exteriores', TRUE),
(10, 'Materiales de Construcción', 'Productos de materiales de construcción', TRUE),
(11, 'Iluminación', 'Productos de iluminación', TRUE),
(12, 'Medición y Nivelación', 'Productos de medición y nivelación', TRUE),
(13, 'Techos e Impermeabilización', 'Productos de techos e impermeabilización', TRUE),
(14, 'Limpieza y Químicos Industriales', 'Productos de limpieza y químicos industriales', TRUE),
(15, 'Soldadura', 'Productos de soldadura', TRUE),
(16, 'Automotriz', 'Productos de automotriz', TRUE),
(17, 'Maquinaria y Equipos', 'Productos de maquinaria y equipos', TRUE),
(18, 'Grifería y Sanitarios', 'Productos de grifería y sanitarios', TRUE),
(19, 'Ferretería General', 'Productos de ferretería general', TRUE),
(20, 'Equipo de Protección Personal (EPP)', 'Productos de equipo de protección personal (epp)', TRUE);

-- -------------------- PROVEEDORES --------------------
INSERT INTO proveedores (id, nombre, descripcion, ruc, categoria_id, tipo_proveedor, sitio_web, tiempo_entrega_dias, contacto, telefono, email, direccion, activo) VALUES
(1, 'Importadora Istmo Corp.', 'Proveedor de artículos de herramientas manuales', '1000037-2-2021', 1, 'fabricante', 'www.proveedor1.com.pa', 10, 'Encargado de Ventas 1', '2201-1001', 'ventas1@importadora1.com.pa', 'Calle 1, Zona Industrial, Panamá', TRUE),
(2, 'Ferretería Panamá', 'Proveedor de artículos de herramientas eléctricas', '1000074-3-2022', 2, 'importador', 'www.proveedor2.com.pa', 1, 'Encargado de Ventas 2', '2202-1002', 'ventas2@ferretería2.com.pa', 'Calle 2, Zona Industrial, Panamá', TRUE),
(3, 'Suministros El Constructor S.A.', 'Proveedor de artículos de tornillería y fijación', '1000111-4-2023', 3, 'mayorista', 'www.proveedor3.com.pa', 1, 'Encargado de Ventas 3', '2203-1003', 'ventas3@suministros3.com.pa', 'Calle 3, Zona Industrial, Panamá', TRUE),
(4, 'Comercial La Llave Corp.', 'Proveedor de artículos de pinturas y recubrimientos', '1000148-5-2024', 4, 'otro', 'www.proveedor4.com.pa', 10, 'Encargado de Ventas 4', '2204-1004', 'ventas4@comercial4.com.pa', 'Calle 4, Zona Industrial, Panamá', TRUE),
(5, 'Corporación El Tornillo', 'Proveedor de artículos de plomería', '1000185-6-2025', 5, 'distribuidor', 'www.proveedor5.com.pa', 3, 'Encargado de Ventas 5', '2205-1005', 'ventas5@corporación5.com.pa', 'Calle 5, Zona Industrial, Panamá', TRUE),
(6, 'Grupo Metropolitana S.A.', 'Proveedor de artículos de electricidad', '1000222-7-2020', 6, 'fabricante', 'www.proveedor6.com.pa', 2, 'Encargado de Ventas 6', '2206-1006', 'ventas6@grupo6.com.pa', 'Calle 6, Zona Industrial, Panamá', TRUE),
(7, 'Almacén Central Corp.', 'Proveedor de artículos de cerrajería y seguridad', '1000259-8-2021', 7, 'importador', 'www.proveedor7.com.pa', 2, 'Encargado de Ventas 7', '2207-1007', 'ventas7@almacén7.com.pa', 'Calle 7, Zona Industrial, Panamá', TRUE),
(8, 'Depósito Pacífico', 'Proveedor de artículos de adhesivos y selladores', '1000296-9-2022', 8, 'mayorista', 'www.proveedor8.com.pa', 2, 'Encargado de Ventas 8', '2208-1008', 'ventas8@depósito8.com.pa', 'Calle 8, Zona Industrial, Panamá', TRUE),
(9, 'Casa Atlántico S.A.', 'Proveedor de artículos de jardín y exteriores', '1000333-1-2023', 9, 'otro', 'www.proveedor9.com.pa', 10, 'Encargado de Ventas 9', '2209-1009', 'ventas9@casa9.com.pa', 'Calle 9, Zona Industrial, Panamá', TRUE),
(10, 'Distribuidora Del Sur Corp.', 'Proveedor de artículos de materiales de construcción', '1000370-2-2024', 10, 'distribuidor', 'www.proveedor10.com.pa', 1, 'Encargado de Ventas 10', '2210-1010', 'ventas10@distribuidora10.com.pa', 'Calle 10, Zona Industrial, Panamá', TRUE),
(11, 'Importadora San Miguel', 'Proveedor de artículos de iluminación', '1000407-3-2025', 11, 'fabricante', 'www.proveedor11.com.pa', 10, 'Encargado de Ventas 11', '2211-1011', 'ventas11@importadora11.com.pa', 'Calle 11, Zona Industrial, Panamá', TRUE),
(12, 'Ferretería La Fortaleza S.A.', 'Proveedor de artículos de medición y nivelación', '1000444-4-2020', 12, 'importador', 'www.proveedor12.com.pa', 10, 'Encargado de Ventas 12', '2212-1012', 'ventas12@ferretería12.com.pa', 'Calle 12, Zona Industrial, Panamá', TRUE),
(13, 'Suministros El Herrero Corp.', 'Proveedor de artículos de techos e impermeabilización', '1000481-5-2021', 13, 'mayorista', 'www.proveedor13.com.pa', 7, 'Encargado de Ventas 13', '2213-1013', 'ventas13@suministros13.com.pa', 'Calle 13, Zona Industrial, Panamá', TRUE),
(14, 'Comercial Nacional', 'Proveedor de artículos de limpieza y químicos industriales', '1000518-6-2022', 14, 'otro', 'www.proveedor14.com.pa', 1, 'Encargado de Ventas 14', '2214-1014', 'ventas14@comercial14.com.pa', 'Calle 14, Zona Industrial, Panamá', TRUE),
(15, 'Corporación Continental S.A.', 'Proveedor de artículos de soldadura', '1000555-7-2023', 15, 'distribuidor', 'www.proveedor15.com.pa', 7, 'Encargado de Ventas 15', '2215-1015', 'ventas15@corporación15.com.pa', 'Calle 15, Zona Industrial, Panamá', TRUE),
(16, 'Grupo La Unión Corp.', 'Proveedor de artículos de automotriz', '1000592-8-2024', 16, 'fabricante', 'www.proveedor16.com.pa', 5, 'Encargado de Ventas 16', '2216-1016', 'ventas16@grupo16.com.pa', 'Calle 16, Zona Industrial, Panamá', TRUE),
(17, 'Almacén El Roble', 'Proveedor de artículos de maquinaria y equipos', '1000629-9-2025', 17, 'importador', 'www.proveedor17.com.pa', 1, 'Encargado de Ventas 17', '2217-1017', 'ventas17@almacén17.com.pa', 'Calle 17, Zona Industrial, Panamá', TRUE),
(18, 'Depósito Interoceánica S.A.', 'Proveedor de artículos de grifería y sanitarios', '1000666-1-2020', 18, 'mayorista', 'www.proveedor18.com.pa', 1, 'Encargado de Ventas 18', '2218-1018', 'ventas18@depósito18.com.pa', 'Calle 18, Zona Industrial, Panamá', TRUE),
(19, 'Casa La Industria Corp.', 'Proveedor de artículos de ferretería general', '1000703-2-2021', 19, 'otro', 'www.proveedor19.com.pa', 1, 'Encargado de Ventas 19', '2219-1019', 'ventas19@casa19.com.pa', 'Calle 19, Zona Industrial, Panamá', TRUE),
(20, 'Distribuidora El Taller', 'Proveedor de artículos de equipo de protección personal (epp)', '1000740-3-2022', 20, 'distribuidor', 'www.proveedor20.com.pa', 2, 'Encargado de Ventas 20', '2220-1020', 'ventas20@distribuidora20.com.pa', 'Calle 20, Zona Industrial, Panamá', TRUE),
(21, 'Importadora Rio Abajo S.A.', 'Proveedor de artículos de herramientas manuales', '1000777-4-2023', 1, 'fabricante', 'www.proveedor21.com.pa', 2, 'Encargado de Ventas 21', '2221-1021', 'ventas21@importadora21.com.pa', 'Calle 21, Zona Industrial, Panamá', TRUE),
(22, 'Ferretería Chiriquí Corp.', 'Proveedor de artículos de herramientas eléctricas', '1000814-5-2024', 2, 'importador', 'www.proveedor22.com.pa', 7, 'Encargado de Ventas 22', '2222-1022', 'ventas22@ferretería22.com.pa', 'Calle 22, Zona Industrial, Panamá', TRUE),
(23, 'Suministros David', 'Proveedor de artículos de tornillería y fijación', '1000851-6-2025', 3, 'mayorista', 'www.proveedor23.com.pa', 7, 'Encargado de Ventas 23', '2223-1023', 'ventas23@suministros23.com.pa', 'Calle 23, Zona Industrial, Panamá', TRUE),
(24, 'Comercial Colón S.A.', 'Proveedor de artículos de pinturas y recubrimientos', '1000888-7-2020', 4, 'otro', 'www.proveedor24.com.pa', 1, 'Encargado de Ventas 24', '2224-1024', 'ventas24@comercial24.com.pa', 'Calle 24, Zona Industrial, Panamá', TRUE),
(25, 'Corporación La Chorrera Corp.', 'Proveedor de artículos de plomería', '1000925-8-2021', 5, 'distribuidor', 'www.proveedor25.com.pa', 7, 'Encargado de Ventas 25', '2225-1025', 'ventas25@corporación25.com.pa', 'Calle 25, Zona Industrial, Panamá', TRUE),
(26, 'Grupo Veraguas', 'Proveedor de artículos de electricidad', '1000962-9-2022', 6, 'fabricante', 'www.proveedor26.com.pa', 2, 'Encargado de Ventas 26', '2226-1026', 'ventas26@grupo26.com.pa', 'Calle 26, Zona Industrial, Panamá', TRUE),
(27, 'Almacén Santiago S.A.', 'Proveedor de artículos de cerrajería y seguridad', '1000999-1-2023', 7, 'importador', 'www.proveedor27.com.pa', 10, 'Encargado de Ventas 27', '2227-1027', 'ventas27@almacén27.com.pa', 'Calle 27, Zona Industrial, Panamá', TRUE),
(28, 'Depósito Coclé Corp.', 'Proveedor de artículos de adhesivos y selladores', '1001036-2-2024', 8, 'mayorista', 'www.proveedor28.com.pa', 10, 'Encargado de Ventas 28', '2228-1028', 'ventas28@depósito28.com.pa', 'Calle 28, Zona Industrial, Panamá', TRUE),
(29, 'Casa Bocas', 'Proveedor de artículos de jardín y exteriores', '1001073-3-2025', 9, 'otro', 'www.proveedor29.com.pa', 10, 'Encargado de Ventas 29', '2229-1029', 'ventas29@casa29.com.pa', 'Calle 29, Zona Industrial, Panamá', TRUE),
(30, 'Distribuidora Azuero S.A.', 'Proveedor de artículos de materiales de construcción', '1001110-4-2020', 10, 'distribuidor', 'www.proveedor30.com.pa', 7, 'Encargado de Ventas 30', '2230-1030', 'ventas30@distribuidora30.com.pa', 'Calle 30, Zona Industrial, Panamá', TRUE),
(31, 'Importadora El Dorado Corp.', 'Proveedor de artículos de iluminación', '1001147-5-2021', 11, 'fabricante', 'www.proveedor31.com.pa', 5, 'Encargado de Ventas 31', '2231-1031', 'ventas31@importadora31.com.pa', 'Calle 31, Zona Industrial, Panamá', TRUE),
(32, 'Ferretería Las Américas', 'Proveedor de artículos de medición y nivelación', '1001184-6-2022', 12, 'importador', 'www.proveedor32.com.pa', 2, 'Encargado de Ventas 32', '2232-1032', 'ventas32@ferretería32.com.pa', 'Calle 32, Zona Industrial, Panamá', TRUE),
(33, 'Suministros El Puente S.A.', 'Proveedor de artículos de techos e impermeabilización', '1001221-7-2023', 13, 'mayorista', 'www.proveedor33.com.pa', 5, 'Encargado de Ventas 33', '2233-1033', 'ventas33@suministros33.com.pa', 'Calle 33, Zona Industrial, Panamá', TRUE),
(34, 'Comercial La Cumbre Corp.', 'Proveedor de artículos de limpieza y químicos industriales', '1001258-8-2024', 14, 'otro', 'www.proveedor34.com.pa', 7, 'Encargado de Ventas 34', '2234-1034', 'ventas34@comercial34.com.pa', 'Calle 34, Zona Industrial, Panamá', TRUE),
(35, 'Corporación El Faro', 'Proveedor de artículos de soldadura', '1001295-9-2025', 15, 'distribuidor', 'www.proveedor35.com.pa', 3, 'Encargado de Ventas 35', '2235-1035', 'ventas35@corporación35.com.pa', 'Calle 35, Zona Industrial, Panamá', TRUE),
(36, 'Grupo Alfa S.A.', 'Proveedor de artículos de automotriz', '1001332-1-2020', 16, 'fabricante', 'www.proveedor36.com.pa', 15, 'Encargado de Ventas 36', '2236-1036', 'ventas36@grupo36.com.pa', 'Calle 36, Zona Industrial, Panamá', TRUE),
(37, 'Almacén Omega Corp.', 'Proveedor de artículos de maquinaria y equipos', '1001369-2-2021', 17, 'importador', 'www.proveedor37.com.pa', 15, 'Encargado de Ventas 37', '2237-1037', 'ventas37@almacén37.com.pa', 'Calle 37, Zona Industrial, Panamá', TRUE),
(38, 'Depósito Prisma', 'Proveedor de artículos de grifería y sanitarios', '1001406-3-2022', 18, 'mayorista', 'www.proveedor38.com.pa', 1, 'Encargado de Ventas 38', '2238-1038', 'ventas38@depósito38.com.pa', 'Calle 38, Zona Industrial, Panamá', TRUE),
(39, 'Casa Vértice S.A.', 'Proveedor de artículos de ferretería general', '1001443-4-2023', 19, 'otro', 'www.proveedor39.com.pa', 15, 'Encargado de Ventas 39', '2239-1039', 'ventas39@casa39.com.pa', 'Calle 39, Zona Industrial, Panamá', TRUE),
(40, 'Distribuidora Zenith Corp.', 'Proveedor de artículos de equipo de protección personal (epp)', '1001480-5-2024', 20, 'distribuidor', 'www.proveedor40.com.pa', 15, 'Encargado de Ventas 40', '2240-1040', 'ventas40@distribuidora40.com.pa', 'Calle 40, Zona Industrial, Panamá', TRUE),
(41, 'Importadora Titán', 'Proveedor de artículos de herramientas manuales', '1001517-6-2025', 1, 'fabricante', 'www.proveedor41.com.pa', 2, 'Encargado de Ventas 41', '2241-1041', 'ventas41@importadora41.com.pa', 'Calle 41, Zona Industrial, Panamá', TRUE),
(42, 'Ferretería Máxima S.A.', 'Proveedor de artículos de herramientas eléctricas', '1001554-7-2020', 2, 'importador', 'www.proveedor42.com.pa', 10, 'Encargado de Ventas 42', '2242-1042', 'ventas42@ferretería42.com.pa', 'Calle 42, Zona Industrial, Panamá', TRUE),
(43, 'Suministros Prime Corp.', 'Proveedor de artículos de tornillería y fijación', '1001591-8-2021', 3, 'mayorista', 'www.proveedor43.com.pa', 5, 'Encargado de Ventas 43', '2243-1043', 'ventas43@suministros43.com.pa', 'Calle 43, Zona Industrial, Panamá', TRUE),
(44, 'Comercial Global', 'Proveedor de artículos de pinturas y recubrimientos', '1001628-9-2022', 4, 'otro', 'www.proveedor44.com.pa', 3, 'Encargado de Ventas 44', '2244-1044', 'ventas44@comercial44.com.pa', 'Calle 44, Zona Industrial, Panamá', TRUE),
(45, 'Corporación Universal S.A.', 'Proveedor de artículos de plomería', '1001665-1-2023', 5, 'distribuidor', 'www.proveedor45.com.pa', 3, 'Encargado de Ventas 45', '2245-1045', 'ventas45@corporación45.com.pa', 'Calle 45, Zona Industrial, Panamá', TRUE),
(46, 'Grupo Elite Corp.', 'Proveedor de artículos de electricidad', '1001702-2-2024', 6, 'fabricante', 'www.proveedor46.com.pa', 2, 'Encargado de Ventas 46', '2246-1046', 'ventas46@grupo46.com.pa', 'Calle 46, Zona Industrial, Panamá', TRUE),
(47, 'Almacén Progreso', 'Proveedor de artículos de cerrajería y seguridad', '1001739-3-2025', 7, 'importador', 'www.proveedor47.com.pa', 2, 'Encargado de Ventas 47', '2247-1047', 'ventas47@almacén47.com.pa', 'Calle 47, Zona Industrial, Panamá', TRUE),
(48, 'Depósito Horizonte S.A.', 'Proveedor de artículos de adhesivos y selladores', '1001776-4-2020', 8, 'mayorista', 'www.proveedor48.com.pa', 15, 'Encargado de Ventas 48', '2248-1048', 'ventas48@depósito48.com.pa', 'Calle 48, Zona Industrial, Panamá', TRUE),
(49, 'Casa Nueva Era Corp.', 'Proveedor de artículos de jardín y exteriores', '1001813-5-2021', 9, 'otro', 'www.proveedor49.com.pa', 3, 'Encargado de Ventas 49', '2249-1049', 'ventas49@casa49.com.pa', 'Calle 49, Zona Industrial, Panamá', TRUE),
(50, 'Distribuidora Fortaleza', 'Proveedor de artículos de materiales de construcción', '1001850-6-2022', 10, 'distribuidor', 'www.proveedor50.com.pa', 1, 'Encargado de Ventas 50', '2250-1050', 'ventas50@distribuidora50.com.pa', 'Calle 50, Zona Industrial, Panamá', TRUE);

-- -------------------- CLIENTES --------------------
INSERT INTO clientes (id, codigo, nombre, cedula_ruc, tipo_cliente, telefono, email, direccion, descuento_porcentaje, activo) VALUES
(1, 'CLI-0001', 'Constructora Istmeña S.A.', '2-701-1001', 'corporativo', '6101-2001', 'cliente1@correo.com', 'Vía 1, Ciudad de Panamá', 3.00, TRUE),
(2, 'CLI-0002', 'José Luis Barrios', '3-702-1002', 'regular', '6102-2002', 'cliente2@correo.com', 'Vía 2, Ciudad de Panamá', 0.00, TRUE),
(3, 'CLI-0003', 'Ferretería El Buen Precio', '4-703-1003', 'mayorista', '6103-2003', 'cliente3@correo.com', 'Vía 3, Ciudad de Panamá', 5.00, TRUE),
(4, 'CLI-0004', 'María Fernanda Ríos', '5-704-1004', 'regular', '6104-2004', 'cliente4@correo.com', 'Vía 4, Ciudad de Panamá', 0.00, TRUE),
(5, 'CLI-0005', 'Inversiones Delta S.A.', '6-705-1005', 'corporativo', '6105-2005', 'cliente5@correo.com', 'Vía 5, Ciudad de Panamá', 3.00, TRUE),
(6, 'CLI-0006', 'Carlos Alberto Nuñez', '7-706-1006', 'regular', '6106-2006', 'cliente6@correo.com', 'Vía 6, Ciudad de Panamá', 0.00, TRUE),
(7, 'CLI-0007', 'Talleres Unidos S.A.', '8-707-1007', 'mayorista', '6107-2007', 'cliente7@correo.com', 'Vía 7, Ciudad de Panamá', 5.00, TRUE),
(8, 'CLI-0008', 'Ana Isabel Cortés', '1-708-1008', 'regular', '6108-2008', 'cliente8@correo.com', 'Vía 8, Ciudad de Panamá', 0.00, TRUE),
(9, 'CLI-0009', 'Remodelaciones del Pacífico', '2-709-1009', 'corporativo', '6109-2009', 'cliente9@correo.com', 'Vía 9, Ciudad de Panamá', 3.00, TRUE),
(10, 'CLI-0010', 'Pedro Antonio Solís', '3-710-1010', 'regular', '6110-2010', 'cliente10@correo.com', 'Vía 10, Ciudad de Panamá', 0.00, TRUE),
(11, 'CLI-0011', 'Grupo Constructor Panamá', '4-711-1011', 'corporativo', '6111-2011', 'cliente11@correo.com', 'Vía 11, Ciudad de Panamá', 3.00, TRUE),
(12, 'CLI-0012', 'Yesenia Morales', '5-712-1012', 'regular', '6112-2012', 'cliente12@correo.com', 'Vía 12, Ciudad de Panamá', 0.00, TRUE),
(13, 'CLI-0013', 'Electricidad y Más S.A.', '6-713-1013', 'mayorista', '6113-2013', 'cliente13@correo.com', 'Vía 13, Ciudad de Panamá', 5.00, TRUE),
(14, 'CLI-0014', 'Roberto Carlos Jaén', '7-714-1014', 'regular', '6114-2014', 'cliente14@correo.com', 'Vía 14, Ciudad de Panamá', 0.00, TRUE),
(15, 'CLI-0015', 'Plomería Express', '8-715-1015', 'mayorista', '6115-2015', 'cliente15@correo.com', 'Vía 15, Ciudad de Panamá', 5.00, TRUE),
(16, 'CLI-0016', 'Diana Patricia Vega', '1-716-1016', 'regular', '6116-2016', 'cliente16@correo.com', 'Vía 16, Ciudad de Panamá', 0.00, TRUE),
(17, 'CLI-0017', 'Consumidor Final', NULL, 'regular', NULL, NULL, 'Panamá', 0.00, TRUE),
(18, 'CLI-0018', 'Hoteles del Istmo S.A.', '3-718-1018', 'corporativo', '6118-2018', 'cliente18@correo.com', 'Vía 18, Ciudad de Panamá', 3.00, TRUE),
(19, 'CLI-0019', 'Fernando José Castillo', '4-719-1019', 'regular', '6119-2019', 'cliente19@correo.com', 'Vía 19, Ciudad de Panamá', 0.00, TRUE),
(20, 'CLI-0020', 'Condominios Bahía S.A.', '5-720-1020', 'corporativo', '6120-2020', 'cliente20@correo.com', 'Vía 20, Ciudad de Panamá', 3.00, TRUE);

-- -------------------- PRODUCTOS --------------------
-- Total productos generados: 500
INSERT INTO productos (id, codigo, codigo_barras, nombre, descripcion, categoria_id, proveedor_id, precio_compra, precio_venta, stock_minimo, unidad_medida, activo) VALUES
(1, 'PROD-0001', '77800000001', 'Segueta Black+Decker', 'Segueta Black+Decker - categoría Herramientas Manuales', 1, 21, 22.79, 36.46, 10, 'pza', TRUE),
(2, 'PROD-0002', '77800000002', 'Alicate universal Truper', 'Alicate universal Truper - categoría Herramientas Manuales', 1, 41, 12.59, 20.14, 25, 'pza', TRUE),
(3, 'PROD-0003', '77800000003', 'Prensa manual 25cm', 'Prensa manual 25cm - categoría Herramientas Manuales', 1, 41, 18.83, 30.13, 10, 'pza', TRUE),
(4, 'PROD-0004', '77800000004', 'Serrucho Ingco', 'Serrucho Ingco - categoría Herramientas Manuales', 1, 1, 23.88, 38.21, 15, 'pza', TRUE),
(5, 'PROD-0005', '77800000005', 'Martillo de uña Pretul', 'Martillo de uña Pretul - categoría Herramientas Manuales', 1, 41, 22.80, 36.48, 25, 'pza', TRUE),
(6, 'PROD-0006', '77800000006', 'Llave ajustable 5kg', 'Llave ajustable 5kg - categoría Herramientas Manuales', 1, 21, 11.85, 18.96, 15, 'pza', TRUE),
(7, 'PROD-0007', '77800000007', 'Nivel de burbuja 1"', 'Nivel de burbuja 1" - categoría Herramientas Manuales', 1, 1, 12.25, 19.60, 20, 'pza', TRUE),
(8, 'PROD-0008', '77800000008', 'Serrucho Chico', 'Serrucho Chico - categoría Herramientas Manuales', 1, 41, 15.52, 24.83, 20, 'pza', TRUE),
(9, 'PROD-0009', '77800000009', 'Llave ajustable 110V', 'Llave ajustable 110V - categoría Herramientas Manuales', 1, 21, 9.31, 14.90, 20, 'pza', TRUE),
(10, 'PROD-0010', '77800000010', 'Llave ajustable 12V', 'Llave ajustable 12V - categoría Herramientas Manuales', 1, 41, 17.40, 27.84, 20, 'pza', TRUE),
(11, 'PROD-0011', '77800000011', 'Espátula 5kg', 'Espátula 5kg - categoría Herramientas Manuales', 1, 41, 7.70, 12.32, 20, 'pza', TRUE),
(12, 'PROD-0012', '77800000012', 'Destornillador plano 220V', 'Destornillador plano 220V - categoría Herramientas Manuales', 1, 41, 4.87, 7.79, 25, 'pza', TRUE),
(13, 'PROD-0013', '77800000013', 'Llave ajustable 10cm', 'Llave ajustable 10cm - categoría Herramientas Manuales', 1, 21, 5.05, 8.08, 10, 'pza', TRUE),
(14, 'PROD-0014', '77800000014', 'Destornillador de estrella 10cm', 'Destornillador de estrella 10cm - categoría Herramientas Manuales', 1, 1, 20.74, 33.18, 10, 'pza', TRUE),
(15, 'PROD-0015', '77800000015', 'Llave ajustable Mediano', 'Llave ajustable Mediano - categoría Herramientas Manuales', 1, 1, 24.65, 39.44, 25, 'pza', TRUE),
(16, 'PROD-0016', '77800000016', 'Espátula Urrea', 'Espátula Urrea - categoría Herramientas Manuales', 1, 21, 12.12, 19.39, 25, 'pza', TRUE),
(17, 'PROD-0017', '77800000017', 'Serrucho Bosch', 'Serrucho Bosch - categoría Herramientas Manuales', 1, 21, 11.79, 18.86, 10, 'pza', TRUE),
(18, 'PROD-0018', '77800000018', 'Formón Urrea', 'Formón Urrea - categoría Herramientas Manuales', 1, 1, 20.13, 32.21, 10, 'pza', TRUE),
(19, 'PROD-0019', '77800000019', 'Destornillador plano 1/2"', 'Destornillador plano 1/2" - categoría Herramientas Manuales', 1, 21, 4.10, 6.56, 10, 'pza', TRUE),
(20, 'PROD-0020', '77800000020', 'Playo de corte Mediano', 'Playo de corte Mediano - categoría Herramientas Manuales', 1, 1, 20.63, 33.01, 25, 'pza', TRUE),
(21, 'PROD-0021', '77800000021', 'Formón Black+Decker', 'Formón Black+Decker - categoría Herramientas Manuales', 1, 21, 23.90, 38.24, 20, 'pza', TRUE),
(22, 'PROD-0022', '77800000022', 'Playo de corte 220V', 'Playo de corte 220V - categoría Herramientas Manuales', 1, 21, 21.27, 34.03, 25, 'pza', TRUE),
(23, 'PROD-0023', '77800000023', 'Playo de corte Ingco', 'Playo de corte Ingco - categoría Herramientas Manuales', 1, 41, 21.95, 35.12, 20, 'pza', TRUE),
(24, 'PROD-0024', '77800000024', 'Nivel de burbuja Chico', 'Nivel de burbuja Chico - categoría Herramientas Manuales', 1, 41, 9.10, 14.56, 25, 'pza', TRUE),
(25, 'PROD-0025', '77800000025', 'Destornillador plano Total', 'Destornillador plano Total - categoría Herramientas Manuales', 1, 21, 12.68, 20.29, 15, 'pza', TRUE),
(26, 'PROD-0026', '77800000026', 'Taladro percutor Makita', 'Taladro percutor Makita - categoría Herramientas Eléctricas', 2, 42, 292.42, 424.01, 5, 'pza', TRUE),
(27, 'PROD-0027', '77800000027', 'Rotomartillo Ingco', 'Rotomartillo Ingco - categoría Herramientas Eléctricas', 2, 22, 143.33, 207.83, 5, 'pza', TRUE),
(28, 'PROD-0028', '77800000028', 'Rotomartillo Truper', 'Rotomartillo Truper - categoría Herramientas Eléctricas', 2, 22, 255.04, 369.81, 15, 'pza', TRUE),
(29, 'PROD-0029', '77800000029', 'Router 3/4"', 'Router 3/4" - categoría Herramientas Eléctricas', 2, 2, 181.00, 262.45, 15, 'pza', TRUE),
(30, 'PROD-0030', '77800000030', 'Compresor de aire Makita', 'Compresor de aire Makita - categoría Herramientas Eléctricas', 2, 2, 340.76, 494.10, 20, 'pza', TRUE),
(31, 'PROD-0031', '77800000031', 'Atornillador inalámbrico Mediano', 'Atornillador inalámbrico Mediano - categoría Herramientas Eléctricas', 2, 42, 309.50, 448.77, 20, 'pza', TRUE),
(32, 'PROD-0032', '77800000032', 'Caladora DeWalt', 'Caladora DeWalt - categoría Herramientas Eléctricas', 2, 2, 200.72, 291.04, 25, 'pza', TRUE),
(33, 'PROD-0033', '77800000033', 'Pistola de calor Genérico', 'Pistola de calor Genérico - categoría Herramientas Eléctricas', 2, 42, 177.03, 256.69, 5, 'pza', TRUE),
(34, 'PROD-0034', '77800000034', 'Atornillador inalámbrico 5kg', 'Atornillador inalámbrico 5kg - categoría Herramientas Eléctricas', 2, 42, 80.61, 116.88, 15, 'pza', TRUE),
(35, 'PROD-0035', '77800000035', 'Router Black+Decker', 'Router Black+Decker - categoría Herramientas Eléctricas', 2, 2, 48.95, 70.98, 25, 'pza', TRUE),
(36, 'PROD-0036', '77800000036', 'Pulidora 5 gal', 'Pulidora 5 gal - categoría Herramientas Eléctricas', 2, 42, 89.10, 129.19, 25, 'pza', TRUE),
(37, 'PROD-0037', '77800000037', 'Sierra circular 12V', 'Sierra circular 12V - categoría Herramientas Eléctricas', 2, 22, 68.89, 99.89, 5, 'pza', TRUE),
(38, 'PROD-0038', '77800000038', 'Caladora 5 gal', 'Caladora 5 gal - categoría Herramientas Eléctricas', 2, 42, 297.96, 432.04, 20, 'pza', TRUE),
(39, 'PROD-0039', '77800000039', 'Lijadora orbital Ingco', 'Lijadora orbital Ingco - categoría Herramientas Eléctricas', 2, 42, 258.68, 375.09, 20, 'pza', TRUE),
(40, 'PROD-0040', '77800000040', 'Compresor de aire Chico', 'Compresor de aire Chico - categoría Herramientas Eléctricas', 2, 2, 181.60, 263.32, 10, 'pza', TRUE),
(41, 'PROD-0041', '77800000041', 'Caladora #12', 'Caladora #12 - categoría Herramientas Eléctricas', 2, 42, 197.55, 286.45, 10, 'pza', TRUE),
(42, 'PROD-0042', '77800000042', 'Taladro percutor Grande', 'Taladro percutor Grande - categoría Herramientas Eléctricas', 2, 22, 145.36, 210.77, 25, 'pza', TRUE),
(43, 'PROD-0043', '77800000043', 'Compresor de aire Ingco', 'Compresor de aire Ingco - categoría Herramientas Eléctricas', 2, 22, 265.08, 384.37, 25, 'pza', TRUE),
(44, 'PROD-0044', '77800000044', 'Pistola de calor Truper', 'Pistola de calor Truper - categoría Herramientas Eléctricas', 2, 2, 178.43, 258.72, 20, 'pza', TRUE),
(45, 'PROD-0045', '77800000045', 'Atornillador inalámbrico 220V', 'Atornillador inalámbrico 220V - categoría Herramientas Eléctricas', 2, 42, 276.49, 400.91, 20, 'pza', TRUE),
(46, 'PROD-0046', '77800000046', 'Pistola de calor Total', 'Pistola de calor Total - categoría Herramientas Eléctricas', 2, 2, 344.04, 498.86, 10, 'pza', TRUE),
(47, 'PROD-0047', '77800000047', 'Pulidora Ingco', 'Pulidora Ingco - categoría Herramientas Eléctricas', 2, 2, 305.36, 442.77, 20, 'pza', TRUE),
(48, 'PROD-0048', '77800000048', 'Taladro percutor Stanley', 'Taladro percutor Stanley - categoría Herramientas Eléctricas', 2, 42, 186.62, 270.60, 20, 'pza', TRUE),
(49, 'PROD-0049', '77800000049', 'Lijadora orbital Grande', 'Lijadora orbital Grande - categoría Herramientas Eléctricas', 2, 22, 339.10, 491.69, 10, 'pza', TRUE),
(50, 'PROD-0050', '77800000050', 'Lijadora orbital 3/4"', 'Lijadora orbital 3/4" - categoría Herramientas Eléctricas', 2, 22, 309.61, 448.93, 20, 'pza', TRUE),
(51, 'PROD-0051', '77800000051', 'Clavo de acero Genérico', 'Clavo de acero Genérico - categoría Tornillería y Fijación', 3, 23, 7.41, 13.34, 5, 'caja', TRUE),
(52, 'PROD-0052', '77800000052', 'Perno galvanizado Pretul', 'Perno galvanizado Pretul - categoría Tornillería y Fijación', 3, 23, 10.65, 19.17, 15, 'caja', TRUE),
(53, 'PROD-0053', '77800000053', 'Perno galvanizado Total', 'Perno galvanizado Total - categoría Tornillería y Fijación', 3, 3, 3.19, 5.74, 5, 'caja', TRUE),
(54, 'PROD-0054', '77800000054', 'Tornillo para madera 10cm', 'Tornillo para madera 10cm - categoría Tornillería y Fijación', 3, 3, 11.68, 21.02, 15, 'caja', TRUE),
(55, 'PROD-0055', '77800000055', 'Tuerca hexagonal #10', 'Tuerca hexagonal #10 - categoría Tornillería y Fijación', 3, 3, 9.62, 17.32, 25, 'caja', TRUE),
(56, 'PROD-0056', '77800000056', 'Perno galvanizado DeWalt', 'Perno galvanizado DeWalt - categoría Tornillería y Fijación', 3, 43, 3.59, 6.46, 15, 'caja', TRUE),
(57, 'PROD-0057', '77800000057', 'Tarugo plástico 110V', 'Tarugo plástico 110V - categoría Tornillería y Fijación', 3, 23, 9.81, 17.66, 15, 'caja', TRUE),
(58, 'PROD-0058', '77800000058', 'Anclaje químico Chico', 'Anclaje químico Chico - categoría Tornillería y Fijación', 3, 43, 4.77, 8.59, 20, 'caja', TRUE),
(59, 'PROD-0059', '77800000059', 'Tornillo autorroscante 3/4"', 'Tornillo autorroscante 3/4" - categoría Tornillería y Fijación', 3, 23, 12.99, 23.38, 15, 'caja', TRUE),
(60, 'PROD-0060', '77800000060', 'Tornillo autorroscante 5 gal', 'Tornillo autorroscante 5 gal - categoría Tornillería y Fijación', 3, 43, 4.94, 8.89, 20, 'caja', TRUE),
(61, 'PROD-0061', '77800000061', 'Espárrago roscado 18V', 'Espárrago roscado 18V - categoría Tornillería y Fijación', 3, 43, 10.77, 19.39, 25, 'caja', TRUE),
(62, 'PROD-0062', '77800000062', 'Tornillo para madera 25cm', 'Tornillo para madera 25cm - categoría Tornillería y Fijación', 3, 43, 12.78, 23.00, 15, 'caja', TRUE),
(63, 'PROD-0063', '77800000063', 'Perno galvanizado Urrea', 'Perno galvanizado Urrea - categoría Tornillería y Fijación', 3, 23, 11.14, 20.05, 15, 'caja', TRUE),
(64, 'PROD-0064', '77800000064', 'Tuerca hexagonal #12', 'Tuerca hexagonal #12 - categoría Tornillería y Fijación', 3, 3, 4.36, 7.85, 10, 'caja', TRUE),
(65, 'PROD-0065', '77800000065', 'Clavo de acero Makita', 'Clavo de acero Makita - categoría Tornillería y Fijación', 3, 3, 3.84, 6.91, 5, 'caja', TRUE),
(66, 'PROD-0066', '77800000066', 'Clavo de acero 3/4"', 'Clavo de acero 3/4" - categoría Tornillería y Fijación', 3, 3, 6.89, 12.40, 20, 'caja', TRUE),
(67, 'PROD-0067', '77800000067', 'Espárrago roscado Surtek', 'Espárrago roscado Surtek - categoría Tornillería y Fijación', 3, 23, 6.05, 10.89, 15, 'caja', TRUE),
(68, 'PROD-0068', '77800000068', 'Remache pop #8', 'Remache pop #8 - categoría Tornillería y Fijación', 3, 3, 13.48, 24.26, 10, 'caja', TRUE),
(69, 'PROD-0069', '77800000069', 'Tornillo autorroscante 25cm', 'Tornillo autorroscante 25cm - categoría Tornillería y Fijación', 3, 3, 10.76, 19.37, 15, 'caja', TRUE),
(70, 'PROD-0070', '77800000070', 'Tornillo para madera Urrea', 'Tornillo para madera Urrea - categoría Tornillería y Fijación', 3, 23, 9.89, 17.80, 20, 'caja', TRUE),
(71, 'PROD-0071', '77800000071', 'Tornillo autorroscante 1 gal', 'Tornillo autorroscante 1 gal - categoría Tornillería y Fijación', 3, 43, 3.48, 6.26, 20, 'caja', TRUE),
(72, 'PROD-0072', '77800000072', 'Espárrago roscado Truper', 'Espárrago roscado Truper - categoría Tornillería y Fijación', 3, 43, 10.82, 19.48, 20, 'caja', TRUE),
(73, 'PROD-0073', '77800000073', 'Clavo de acero Surtek', 'Clavo de acero Surtek - categoría Tornillería y Fijación', 3, 3, 4.87, 8.77, 25, 'caja', TRUE),
(74, 'PROD-0074', '77800000074', 'Clavo de acero 1/2"', 'Clavo de acero 1/2" - categoría Tornillería y Fijación', 3, 3, 5.92, 10.66, 10, 'caja', TRUE),
(75, 'PROD-0075', '77800000075', 'Tuerca hexagonal Genérico', 'Tuerca hexagonal Genérico - categoría Tornillería y Fijación', 3, 23, 5.76, 10.37, 5, 'caja', TRUE),
(76, 'PROD-0076', '77800000076', 'Anticorrosivo Stanley', 'Anticorrosivo Stanley - categoría Pinturas y Recubrimientos', 4, 4, 23.53, 35.30, 25, 'galón', TRUE),
(77, 'PROD-0077', '77800000077', 'Anticorrosivo Genérico', 'Anticorrosivo Genérico - categoría Pinturas y Recubrimientos', 4, 4, 38.96, 58.44, 20, 'galón', TRUE),
(78, 'PROD-0078', '77800000078', 'Base niveladora Grande', 'Base niveladora Grande - categoría Pinturas y Recubrimientos', 4, 4, 31.38, 47.07, 25, 'galón', TRUE),
(79, 'PROD-0079', '77800000079', 'Pintura de aceite Total', 'Pintura de aceite Total - categoría Pinturas y Recubrimientos', 4, 24, 23.88, 35.82, 25, 'galón', TRUE),
(80, 'PROD-0080', '77800000080', 'Pintura látex blanca Pretul', 'Pintura látex blanca Pretul - categoría Pinturas y Recubrimientos', 4, 44, 23.41, 35.12, 5, 'galón', TRUE),
(81, 'PROD-0081', '77800000081', 'Pintura de tráfico Pretul', 'Pintura de tráfico Pretul - categoría Pinturas y Recubrimientos', 4, 4, 43.28, 64.92, 25, 'galón', TRUE),
(82, 'PROD-0082', '77800000082', 'Sellador acrílico 25cm', 'Sellador acrílico 25cm - categoría Pinturas y Recubrimientos', 4, 44, 8.75, 13.12, 20, 'galón', TRUE),
(83, 'PROD-0083', '77800000083', 'Base niveladora 5 gal', 'Base niveladora 5 gal - categoría Pinturas y Recubrimientos', 4, 24, 17.39, 26.09, 5, 'galón', TRUE),
(84, 'PROD-0084', '77800000084', 'Barniz para madera 220V', 'Barniz para madera 220V - categoría Pinturas y Recubrimientos', 4, 24, 16.92, 25.38, 5, 'galón', TRUE),
(85, 'PROD-0085', '77800000085', 'Pintura látex blanca 1/2"', 'Pintura látex blanca 1/2" - categoría Pinturas y Recubrimientos', 4, 44, 36.00, 54.00, 10, 'galón', TRUE),
(86, 'PROD-0086', '77800000086', 'Pintura de aceite Black+Decker', 'Pintura de aceite Black+Decker - categoría Pinturas y Recubrimientos', 4, 44, 20.53, 30.80, 10, 'galón', TRUE),
(87, 'PROD-0087', '77800000087', 'Pintura látex blanca Genérico', 'Pintura látex blanca Genérico - categoría Pinturas y Recubrimientos', 4, 44, 25.71, 38.56, 10, 'galón', TRUE),
(88, 'PROD-0088', '77800000088', 'Pintura epóxica Total', 'Pintura epóxica Total - categoría Pinturas y Recubrimientos', 4, 44, 36.72, 55.08, 20, 'galón', TRUE),
(89, 'PROD-0089', '77800000089', 'Pintura látex blanca #12', 'Pintura látex blanca #12 - categoría Pinturas y Recubrimientos', 4, 4, 9.62, 14.43, 10, 'galón', TRUE),
(90, 'PROD-0090', '77800000090', 'Anticorrosivo 1 gal', 'Anticorrosivo 1 gal - categoría Pinturas y Recubrimientos', 4, 24, 27.07, 40.61, 25, 'galón', TRUE),
(91, 'PROD-0091', '77800000091', 'Impermeabilizante acrílico 12V', 'Impermeabilizante acrílico 12V - categoría Pinturas y Recubrimientos', 4, 4, 35.87, 53.80, 10, 'galón', TRUE),
(92, 'PROD-0092', '77800000092', 'Pintura epóxica Grande', 'Pintura epóxica Grande - categoría Pinturas y Recubrimientos', 4, 4, 40.03, 60.05, 15, 'galón', TRUE),
(93, 'PROD-0093', '77800000093', 'Pintura de aceite Makita', 'Pintura de aceite Makita - categoría Pinturas y Recubrimientos', 4, 24, 24.17, 36.26, 20, 'galón', TRUE),
(94, 'PROD-0094', '77800000094', 'Impermeabilizante acrílico Stanley', 'Impermeabilizante acrílico Stanley - categoría Pinturas y Recubrimientos', 4, 24, 20.54, 30.81, 10, 'galón', TRUE),
(95, 'PROD-0095', '77800000095', 'Pintura látex blanca Truper', 'Pintura látex blanca Truper - categoría Pinturas y Recubrimientos', 4, 24, 42.20, 63.30, 25, 'galón', TRUE),
(96, 'PROD-0096', '77800000096', 'Anticorrosivo 1"', 'Anticorrosivo 1" - categoría Pinturas y Recubrimientos', 4, 44, 23.71, 35.56, 20, 'galón', TRUE),
(97, 'PROD-0097', '77800000097', 'Pintura de tráfico #8', 'Pintura de tráfico #8 - categoría Pinturas y Recubrimientos', 4, 44, 18.87, 28.30, 5, 'galón', TRUE),
(98, 'PROD-0098', '77800000098', 'Pintura de aceite 5kg', 'Pintura de aceite 5kg - categoría Pinturas y Recubrimientos', 4, 44, 18.94, 28.41, 20, 'galón', TRUE),
(99, 'PROD-0099', '77800000099', 'Sellador acrílico Surtek', 'Sellador acrílico Surtek - categoría Pinturas y Recubrimientos', 4, 4, 33.52, 50.28, 15, 'galón', TRUE),
(100, 'PROD-0100', '77800000100', 'Pintura de aceite Ingco', 'Pintura de aceite Ingco - categoría Pinturas y Recubrimientos', 4, 44, 40.19, 60.28, 15, 'galón', TRUE);
INSERT INTO productos (id, codigo, codigo_barras, nombre, descripcion, categoria_id, proveedor_id, precio_compra, precio_venta, stock_minimo, unidad_medida, activo) VALUES
(101, 'PROD-0101', '77800000101', 'Reducción PVC Ingco', 'Reducción PVC Ingco - categoría Plomería', 5, 45, 2.49, 4.23, 20, 'pza', TRUE),
(102, 'PROD-0102', '77800000102', 'Manguera flexible 1/2"', 'Manguera flexible 1/2" - categoría Plomería', 5, 45, 21.91, 37.25, 20, 'pza', TRUE),
(103, 'PROD-0103', '77800000103', 'Llave de paso Black+Decker', 'Llave de paso Black+Decker - categoría Plomería', 5, 5, 17.60, 29.92, 5, 'pza', TRUE),
(104, 'PROD-0104', '77800000104', 'Tubo PVC 1/2" Bosch', 'Tubo PVC 1/2" Bosch - categoría Plomería', 5, 25, 10.10, 17.17, 10, 'pza', TRUE),
(105, 'PROD-0105', '77800000105', 'Manguera flexible 5 gal', 'Manguera flexible 5 gal - categoría Plomería', 5, 25, 15.55, 26.44, 25, 'pza', TRUE),
(106, 'PROD-0106', '77800000106', 'Tee sanitaria 10cm', 'Tee sanitaria 10cm - categoría Plomería', 5, 45, 22.09, 37.55, 10, 'pza', TRUE),
(107, 'PROD-0107', '77800000107', 'Reducción PVC Stanley', 'Reducción PVC Stanley - categoría Plomería', 5, 45, 10.43, 17.73, 20, 'pza', TRUE),
(108, 'PROD-0108', '77800000108', 'Tubo PVC 1/2" DeWalt', 'Tubo PVC 1/2" DeWalt - categoría Plomería', 5, 25, 28.74, 48.86, 20, 'pza', TRUE),
(109, 'PROD-0109', '77800000109', 'Manguera flexible 110V', 'Manguera flexible 110V - categoría Plomería', 5, 45, 11.27, 19.16, 15, 'pza', TRUE),
(110, 'PROD-0110', '77800000110', 'Sifón plástico 1/2"', 'Sifón plástico 1/2" - categoría Plomería', 5, 5, 9.09, 15.45, 15, 'pza', TRUE),
(111, 'PROD-0111', '77800000111', 'Tee sanitaria Truper', 'Tee sanitaria Truper - categoría Plomería', 5, 25, 21.44, 36.45, 20, 'pza', TRUE),
(112, 'PROD-0112', '77800000112', 'Tubo PVC 1/2" 18V', 'Tubo PVC 1/2" 18V - categoría Plomería', 5, 25, 17.25, 29.32, 15, 'pza', TRUE),
(113, 'PROD-0113', '77800000113', 'Tubo PVC 1/2" Truper', 'Tubo PVC 1/2" Truper - categoría Plomería', 5, 45, 20.62, 35.05, 20, 'pza', TRUE),
(114, 'PROD-0114', '77800000114', 'Válvula check 10cm', 'Válvula check 10cm - categoría Plomería', 5, 5, 9.42, 16.01, 15, 'pza', TRUE),
(115, 'PROD-0115', '77800000115', 'Reducción PVC Makita', 'Reducción PVC Makita - categoría Plomería', 5, 25, 20.02, 34.03, 20, 'pza', TRUE),
(116, 'PROD-0116', '77800000116', 'Sifón plástico 1"', 'Sifón plástico 1" - categoría Plomería', 5, 45, 8.86, 15.06, 25, 'pza', TRUE),
(117, 'PROD-0117', '77800000117', 'Sifón plástico 18V', 'Sifón plástico 18V - categoría Plomería', 5, 5, 4.17, 7.09, 25, 'pza', TRUE),
(118, 'PROD-0118', '77800000118', 'Manguera flexible Makita', 'Manguera flexible Makita - categoría Plomería', 5, 5, 20.43, 34.73, 25, 'pza', TRUE),
(119, 'PROD-0119', '77800000119', 'Cinta teflón Total', 'Cinta teflón Total - categoría Plomería', 5, 5, 3.91, 6.65, 15, 'pza', TRUE),
(120, 'PROD-0120', '77800000120', 'Sifón plástico 3/4"', 'Sifón plástico 3/4" - categoría Plomería', 5, 45, 23.35, 39.70, 5, 'pza', TRUE),
(121, 'PROD-0121', '77800000121', 'Llave de paso 220V', 'Llave de paso 220V - categoría Plomería', 5, 25, 19.93, 33.88, 20, 'pza', TRUE),
(122, 'PROD-0122', '77800000122', 'Sifón plástico Surtek', 'Sifón plástico Surtek - categoría Plomería', 5, 25, 10.31, 17.53, 5, 'pza', TRUE),
(123, 'PROD-0123', '77800000123', 'Tee sanitaria 25cm', 'Tee sanitaria 25cm - categoría Plomería', 5, 45, 17.65, 30.00, 25, 'pza', TRUE),
(124, 'PROD-0124', '77800000124', 'Codo PVC 90° 10cm', 'Codo PVC 90° 10cm - categoría Plomería', 5, 5, 13.12, 22.30, 10, 'pza', TRUE),
(125, 'PROD-0125', '77800000125', 'Tubo PVC 1/2" 110V', 'Tubo PVC 1/2" 110V - categoría Plomería', 5, 25, 6.40, 10.88, 15, 'pza', TRUE),
(126, 'PROD-0126', '77800000126', 'Balastro electrónico 3/4"', 'Balastro electrónico 3/4" - categoría Electricidad', 6, 26, 36.66, 58.66, 15, 'pza', TRUE),
(127, 'PROD-0127', '77800000127', 'Cable eléctrico THHN #12 Stanley', 'Cable eléctrico THHN #12 Stanley - categoría Electricidad', 6, 6, 22.94, 36.70, 10, 'pza', TRUE),
(128, 'PROD-0128', '77800000128', 'Foco LED 9W 15cm', 'Foco LED 9W 15cm - categoría Electricidad', 6, 6, 11.17, 17.87, 25, 'pza', TRUE),
(129, 'PROD-0129', '77800000129', 'Extensión eléctrica 25cm', 'Extensión eléctrica 25cm - categoría Electricidad', 6, 26, 6.94, 11.10, 15, 'pza', TRUE),
(130, 'PROD-0130', '77800000130', 'Caja octogonal Chico', 'Caja octogonal Chico - categoría Electricidad', 6, 46, 33.50, 53.60, 20, 'pza', TRUE),
(131, 'PROD-0131', '77800000131', 'Breaker termomagnético Total', 'Breaker termomagnético Total - categoría Electricidad', 6, 26, 30.77, 49.23, 20, 'pza', TRUE),
(132, 'PROD-0132', '77800000132', 'Tomacorriente doble 10cm', 'Tomacorriente doble 10cm - categoría Electricidad', 6, 26, 7.56, 12.10, 15, 'pza', TRUE),
(133, 'PROD-0133', '77800000133', 'Foco LED 9W Black+Decker', 'Foco LED 9W Black+Decker - categoría Electricidad', 6, 46, 22.32, 35.71, 10, 'pza', TRUE),
(134, 'PROD-0134', '77800000134', 'Regleta de conexiones 110V', 'Regleta de conexiones 110V - categoría Electricidad', 6, 46, 2.32, 3.71, 5, 'pza', TRUE),
(135, 'PROD-0135', '77800000135', 'Interruptor sencillo DeWalt', 'Interruptor sencillo DeWalt - categoría Electricidad', 6, 6, 17.08, 27.33, 10, 'pza', TRUE),
(136, 'PROD-0136', '77800000136', 'Interruptor sencillo Genérico', 'Interruptor sencillo Genérico - categoría Electricidad', 6, 6, 9.53, 15.25, 20, 'pza', TRUE),
(137, 'PROD-0137', '77800000137', 'Interruptor sencillo 1kg', 'Interruptor sencillo 1kg - categoría Electricidad', 6, 46, 25.87, 41.39, 25, 'pza', TRUE),
(138, 'PROD-0138', '77800000138', 'Regleta de conexiones Truper', 'Regleta de conexiones Truper - categoría Electricidad', 6, 46, 20.03, 32.05, 5, 'pza', TRUE),
(139, 'PROD-0139', '77800000139', 'Caja octogonal 1"', 'Caja octogonal 1" - categoría Electricidad', 6, 26, 1.46, 2.34, 25, 'pza', TRUE),
(140, 'PROD-0140', '77800000140', 'Extensión eléctrica Makita', 'Extensión eléctrica Makita - categoría Electricidad', 6, 46, 39.12, 62.59, 5, 'pza', TRUE),
(141, 'PROD-0141', '77800000141', 'Extensión eléctrica Surtek', 'Extensión eléctrica Surtek - categoría Electricidad', 6, 6, 5.17, 8.27, 5, 'pza', TRUE),
(142, 'PROD-0142', '77800000142', 'Cable eléctrico THHN #12 Ingco', 'Cable eléctrico THHN #12 Ingco - categoría Electricidad', 6, 6, 8.49, 13.58, 25, 'pza', TRUE),
(143, 'PROD-0143', '77800000143', 'Caja octogonal #10', 'Caja octogonal #10 - categoría Electricidad', 6, 26, 32.00, 51.20, 5, 'pza', TRUE),
(144, 'PROD-0144', '77800000144', 'Regleta de conexiones 1"', 'Regleta de conexiones 1" - categoría Electricidad', 6, 26, 23.04, 36.86, 10, 'pza', TRUE),
(145, 'PROD-0145', '77800000145', 'Regleta de conexiones 1kg', 'Regleta de conexiones 1kg - categoría Electricidad', 6, 46, 39.68, 63.49, 5, 'pza', TRUE),
(146, 'PROD-0146', '77800000146', 'Tomacorriente doble 1kg', 'Tomacorriente doble 1kg - categoría Electricidad', 6, 26, 15.71, 25.14, 20, 'pza', TRUE),
(147, 'PROD-0147', '77800000147', 'Caja octogonal 3/4"', 'Caja octogonal 3/4" - categoría Electricidad', 6, 46, 7.68, 12.29, 20, 'pza', TRUE),
(148, 'PROD-0148', '77800000148', 'Interruptor sencillo #10', 'Interruptor sencillo #10 - categoría Electricidad', 6, 46, 39.12, 62.59, 5, 'pza', TRUE),
(149, 'PROD-0149', '77800000149', 'Cable eléctrico THHN #12 12V', 'Cable eléctrico THHN #12 12V - categoría Electricidad', 6, 6, 9.25, 14.80, 25, 'pza', TRUE),
(150, 'PROD-0150', '77800000150', 'Tomacorriente doble Ingco', 'Tomacorriente doble Ingco - categoría Electricidad', 6, 6, 37.04, 59.26, 15, 'pza', TRUE),
(151, 'PROD-0151', '77800000151', 'Cerradura de pomo 25cm', 'Cerradura de pomo 25cm - categoría Cerrajería y Seguridad', 7, 7, 14.87, 22.30, 10, 'pza', TRUE),
(152, 'PROD-0152', '77800000152', 'Bisagra reforzada 5 gal', 'Bisagra reforzada 5 gal - categoría Cerrajería y Seguridad', 7, 27, 43.70, 65.55, 10, 'pza', TRUE),
(153, 'PROD-0153', '77800000153', 'Aldaba metálica Ingco', 'Aldaba metálica Ingco - categoría Cerrajería y Seguridad', 7, 27, 45.47, 68.20, 20, 'pza', TRUE),
(154, 'PROD-0154', '77800000154', 'Aldaba metálica 25cm', 'Aldaba metálica 25cm - categoría Cerrajería y Seguridad', 7, 27, 10.71, 16.07, 5, 'pza', TRUE),
(155, 'PROD-0155', '77800000155', 'Bisagra reforzada 1 gal', 'Bisagra reforzada 1 gal - categoría Cerrajería y Seguridad', 7, 7, 77.18, 115.77, 5, 'pza', TRUE),
(156, 'PROD-0156', '77800000156', 'Caja fuerte pequeña Ingco', 'Caja fuerte pequeña Ingco - categoría Cerrajería y Seguridad', 7, 47, 25.73, 38.59, 10, 'pza', TRUE),
(157, 'PROD-0157', '77800000157', 'Candado de bronce Genérico', 'Candado de bronce Genérico - categoría Cerrajería y Seguridad', 7, 27, 52.59, 78.89, 20, 'pza', TRUE),
(158, 'PROD-0158', '77800000158', 'Cilindro de seguridad 1"', 'Cilindro de seguridad 1" - categoría Cerrajería y Seguridad', 7, 47, 20.68, 31.02, 20, 'pza', TRUE),
(159, 'PROD-0159', '77800000159', 'Bisagra reforzada 15cm', 'Bisagra reforzada 15cm - categoría Cerrajería y Seguridad', 7, 47, 57.20, 85.80, 10, 'pza', TRUE),
(160, 'PROD-0160', '77800000160', 'Cerradura de pomo Chico', 'Cerradura de pomo Chico - categoría Cerrajería y Seguridad', 7, 27, 38.05, 57.07, 10, 'pza', TRUE),
(161, 'PROD-0161', '77800000161', 'Cerradura de pomo 1/2"', 'Cerradura de pomo 1/2" - categoría Cerrajería y Seguridad', 7, 47, 73.74, 110.61, 5, 'pza', TRUE),
(162, 'PROD-0162', '77800000162', 'Cilindro de seguridad 110V', 'Cilindro de seguridad 110V - categoría Cerrajería y Seguridad', 7, 47, 20.89, 31.34, 15, 'pza', TRUE),
(163, 'PROD-0163', '77800000163', 'Aldaba metálica Truper', 'Aldaba metálica Truper - categoría Cerrajería y Seguridad', 7, 7, 27.30, 40.95, 20, 'pza', TRUE),
(164, 'PROD-0164', '77800000164', 'Caja fuerte pequeña 12V', 'Caja fuerte pequeña 12V - categoría Cerrajería y Seguridad', 7, 47, 38.03, 57.05, 20, 'pza', TRUE),
(165, 'PROD-0165', '77800000165', 'Caja fuerte pequeña Grande', 'Caja fuerte pequeña Grande - categoría Cerrajería y Seguridad', 7, 27, 34.92, 52.38, 20, 'pza', TRUE),
(166, 'PROD-0166', '77800000166', 'Cadena de seguridad Genérico', 'Cadena de seguridad Genérico - categoría Cerrajería y Seguridad', 7, 47, 65.01, 97.52, 15, 'pza', TRUE),
(167, 'PROD-0167', '77800000167', 'Cilindro de seguridad Black+Decker', 'Cilindro de seguridad Black+Decker - categoría Cerrajería y Seguridad', 7, 47, 16.39, 24.59, 5, 'pza', TRUE),
(168, 'PROD-0168', '77800000168', 'Cerradura de sobreponer Black+Decker', 'Cerradura de sobreponer Black+Decker - categoría Cerrajería y Seguridad', 7, 7, 20.56, 30.84, 10, 'pza', TRUE),
(169, 'PROD-0169', '77800000169', 'Cilindro de seguridad #10', 'Cilindro de seguridad #10 - categoría Cerrajería y Seguridad', 7, 27, 55.70, 83.55, 25, 'pza', TRUE),
(170, 'PROD-0170', '77800000170', 'Cerradura de pomo 110V', 'Cerradura de pomo 110V - categoría Cerrajería y Seguridad', 7, 7, 46.37, 69.55, 25, 'pza', TRUE),
(171, 'PROD-0171', '77800000171', 'Aldaba metálica DeWalt', 'Aldaba metálica DeWalt - categoría Cerrajería y Seguridad', 7, 47, 19.18, 28.77, 15, 'pza', TRUE),
(172, 'PROD-0172', '77800000172', 'Bisagra reforzada 1/2"', 'Bisagra reforzada 1/2" - categoría Cerrajería y Seguridad', 7, 27, 76.57, 114.85, 25, 'pza', TRUE),
(173, 'PROD-0173', '77800000173', 'Cadena de seguridad 1kg', 'Cadena de seguridad 1kg - categoría Cerrajería y Seguridad', 7, 7, 46.19, 69.28, 10, 'pza', TRUE),
(174, 'PROD-0174', '77800000174', 'Cadena de seguridad Stanley', 'Cadena de seguridad Stanley - categoría Cerrajería y Seguridad', 7, 47, 60.51, 90.77, 20, 'pza', TRUE),
(175, 'PROD-0175', '77800000175', 'Caja fuerte pequeña 1/2"', 'Caja fuerte pequeña 1/2" - categoría Cerrajería y Seguridad', 7, 27, 65.36, 98.04, 20, 'pza', TRUE),
(176, 'PROD-0176', '77800000176', 'Pegamento para PVC 5kg', 'Pegamento para PVC 5kg - categoría Adhesivos y Selladores', 8, 28, 16.24, 27.61, 10, 'pza', TRUE),
(177, 'PROD-0177', '77800000177', 'Pegamento epóxico #12', 'Pegamento epóxico #12 - categoría Adhesivos y Selladores', 8, 8, 11.96, 20.33, 25, 'pza', TRUE),
(178, 'PROD-0178', '77800000178', 'Cinta doble cara 1"', 'Cinta doble cara 1" - categoría Adhesivos y Selladores', 8, 28, 16.02, 27.23, 5, 'pza', TRUE),
(179, 'PROD-0179', '77800000179', 'Silicón transparente #12', 'Silicón transparente #12 - categoría Adhesivos y Selladores', 8, 8, 11.32, 19.24, 25, 'pza', TRUE),
(180, 'PROD-0180', '77800000180', 'Adhesivo de contacto Chico', 'Adhesivo de contacto Chico - categoría Adhesivos y Selladores', 8, 28, 13.63, 23.17, 10, 'pza', TRUE),
(181, 'PROD-0181', '77800000181', 'Silicón transparente 25cm', 'Silicón transparente 25cm - categoría Adhesivos y Selladores', 8, 8, 6.34, 10.78, 25, 'pza', TRUE),
(182, 'PROD-0182', '77800000182', 'Sellador de poliuretano Stanley', 'Sellador de poliuretano Stanley - categoría Adhesivos y Selladores', 8, 8, 19.76, 33.59, 15, 'pza', TRUE),
(183, 'PROD-0183', '77800000183', 'Silicón transparente Stanley', 'Silicón transparente Stanley - categoría Adhesivos y Selladores', 8, 48, 9.58, 16.29, 25, 'pza', TRUE),
(184, 'PROD-0184', '77800000184', 'Silicón transparente Bosch', 'Silicón transparente Bosch - categoría Adhesivos y Selladores', 8, 28, 19.18, 32.61, 5, 'pza', TRUE),
(185, 'PROD-0185', '77800000185', 'Sellador de poliuretano Truper', 'Sellador de poliuretano Truper - categoría Adhesivos y Selladores', 8, 8, 2.25, 3.82, 10, 'pza', TRUE),
(186, 'PROD-0186', '77800000186', 'Silicón transparente Total', 'Silicón transparente Total - categoría Adhesivos y Selladores', 8, 28, 11.12, 18.90, 25, 'pza', TRUE),
(187, 'PROD-0187', '77800000187', 'Cinta doble cara 1 gal', 'Cinta doble cara 1 gal - categoría Adhesivos y Selladores', 8, 28, 14.63, 24.87, 5, 'pza', TRUE),
(188, 'PROD-0188', '77800000188', 'Cinta de aluminio 5kg', 'Cinta de aluminio 5kg - categoría Adhesivos y Selladores', 8, 48, 13.09, 22.25, 15, 'pza', TRUE),
(189, 'PROD-0189', '77800000189', 'Cinta doble cara Black+Decker', 'Cinta doble cara Black+Decker - categoría Adhesivos y Selladores', 8, 8, 11.20, 19.04, 10, 'pza', TRUE),
(190, 'PROD-0190', '77800000190', 'Pegamento epóxico Stanley', 'Pegamento epóxico Stanley - categoría Adhesivos y Selladores', 8, 28, 9.03, 15.35, 5, 'pza', TRUE),
(191, 'PROD-0191', '77800000191', 'Pegamento epóxico 220V', 'Pegamento epóxico 220V - categoría Adhesivos y Selladores', 8, 48, 19.16, 32.57, 10, 'pza', TRUE),
(192, 'PROD-0192', '77800000192', 'Sellador de poliuretano Urrea', 'Sellador de poliuretano Urrea - categoría Adhesivos y Selladores', 8, 8, 18.64, 31.69, 5, 'pza', TRUE),
(193, 'PROD-0193', '77800000193', 'Cinta doble cara Stanley', 'Cinta doble cara Stanley - categoría Adhesivos y Selladores', 8, 48, 2.99, 5.08, 10, 'pza', TRUE),
(194, 'PROD-0194', '77800000194', 'Cinta de aluminio 10cm', 'Cinta de aluminio 10cm - categoría Adhesivos y Selladores', 8, 48, 4.47, 7.60, 5, 'pza', TRUE),
(195, 'PROD-0195', '77800000195', 'Cinta doble cara Makita', 'Cinta doble cara Makita - categoría Adhesivos y Selladores', 8, 28, 11.41, 19.40, 15, 'pza', TRUE),
(196, 'PROD-0196', '77800000196', 'Silicón transparente 1kg', 'Silicón transparente 1kg - categoría Adhesivos y Selladores', 8, 8, 15.50, 26.35, 15, 'pza', TRUE),
(197, 'PROD-0197', '77800000197', 'Cinta doble cara 3/4"', 'Cinta doble cara 3/4" - categoría Adhesivos y Selladores', 8, 28, 16.53, 28.10, 15, 'pza', TRUE),
(198, 'PROD-0198', '77800000198', 'Pegamento epóxico 25cm', 'Pegamento epóxico 25cm - categoría Adhesivos y Selladores', 8, 28, 14.82, 25.19, 25, 'pza', TRUE),
(199, 'PROD-0199', '77800000199', 'Pegamento para PVC 25cm', 'Pegamento para PVC 25cm - categoría Adhesivos y Selladores', 8, 28, 10.19, 17.32, 15, 'pza', TRUE),
(200, 'PROD-0200', '77800000200', 'Cinta de aluminio 15cm', 'Cinta de aluminio 15cm - categoría Adhesivos y Selladores', 8, 48, 5.00, 8.50, 10, 'pza', TRUE);
INSERT INTO productos (id, codigo, codigo_barras, nombre, descripcion, categoria_id, proveedor_id, precio_compra, precio_venta, stock_minimo, unidad_medida, activo) VALUES
(201, 'PROD-0201', '77800000201', 'Regadera plástica 5kg', 'Regadera plástica 5kg - categoría Jardín y Exteriores', 9, 9, 21.86, 33.88, 10, 'pza', TRUE),
(202, 'PROD-0202', '77800000202', 'Carretilla 110V', 'Carretilla 110V - categoría Jardín y Exteriores', 9, 9, 49.28, 76.38, 20, 'pza', TRUE),
(203, 'PROD-0203', '77800000203', 'Pala punta cuadrada Ingco', 'Pala punta cuadrada Ingco - categoría Jardín y Exteriores', 9, 49, 59.98, 92.97, 10, 'pza', TRUE),
(204, 'PROD-0204', '77800000204', 'Pala punta cuadrada 25cm', 'Pala punta cuadrada 25cm - categoría Jardín y Exteriores', 9, 49, 17.50, 27.12, 25, 'pza', TRUE),
(205, 'PROD-0205', '77800000205', 'Carretilla #12', 'Carretilla #12 - categoría Jardín y Exteriores', 9, 9, 36.24, 56.17, 20, 'pza', TRUE),
(206, 'PROD-0206', '77800000206', 'Manguera de jardín 1"', 'Manguera de jardín 1" - categoría Jardín y Exteriores', 9, 9, 17.25, 26.74, 15, 'pza', TRUE),
(207, 'PROD-0207', '77800000207', 'Fertilizante granulado 12V', 'Fertilizante granulado 12V - categoría Jardín y Exteriores', 9, 9, 43.39, 67.25, 15, 'pza', TRUE),
(208, 'PROD-0208', '77800000208', 'Tijera podadora Bosch', 'Tijera podadora Bosch - categoría Jardín y Exteriores', 9, 9, 35.53, 55.07, 10, 'pza', TRUE),
(209, 'PROD-0209', '77800000209', 'Pala punta cuadrada 15cm', 'Pala punta cuadrada 15cm - categoría Jardín y Exteriores', 9, 29, 13.83, 21.44, 5, 'pza', TRUE),
(210, 'PROD-0210', '77800000210', 'Machete 1kg', 'Machete 1kg - categoría Jardín y Exteriores', 9, 9, 37.69, 58.42, 5, 'pza', TRUE),
(211, 'PROD-0211', '77800000211', 'Fertilizante granulado Pretul', 'Fertilizante granulado Pretul - categoría Jardín y Exteriores', 9, 9, 22.58, 35.00, 25, 'pza', TRUE),
(212, 'PROD-0212', '77800000212', 'Carretilla Mediano', 'Carretilla Mediano - categoría Jardín y Exteriores', 9, 29, 44.15, 68.43, 15, 'pza', TRUE),
(213, 'PROD-0213', '77800000213', 'Pala punta cuadrada Makita', 'Pala punta cuadrada Makita - categoría Jardín y Exteriores', 9, 9, 36.85, 57.12, 10, 'pza', TRUE),
(214, 'PROD-0214', '77800000214', 'Pala punta cuadrada Mediano', 'Pala punta cuadrada Mediano - categoría Jardín y Exteriores', 9, 49, 52.20, 80.91, 5, 'pza', TRUE),
(215, 'PROD-0215', '77800000215', 'Tijera podadora 12V', 'Tijera podadora 12V - categoría Jardín y Exteriores', 9, 49, 9.66, 14.97, 15, 'pza', TRUE),
(216, 'PROD-0216', '77800000216', 'Fertilizante granulado Mediano', 'Fertilizante granulado Mediano - categoría Jardín y Exteriores', 9, 9, 45.01, 69.77, 20, 'pza', TRUE),
(217, 'PROD-0217', '77800000217', 'Machete 12V', 'Machete 12V - categoría Jardín y Exteriores', 9, 49, 21.77, 33.74, 25, 'pza', TRUE),
(218, 'PROD-0218', '77800000218', 'Fertilizante granulado #8', 'Fertilizante granulado #8 - categoría Jardín y Exteriores', 9, 29, 16.50, 25.57, 25, 'pza', TRUE),
(219, 'PROD-0219', '77800000219', 'Carretilla Ingco', 'Carretilla Ingco - categoría Jardín y Exteriores', 9, 29, 21.18, 32.83, 15, 'pza', TRUE),
(220, 'PROD-0220', '77800000220', 'Pala punta cuadrada #8', 'Pala punta cuadrada #8 - categoría Jardín y Exteriores', 9, 49, 4.10, 6.35, 25, 'pza', TRUE),
(221, 'PROD-0221', '77800000221', 'Manguera de jardín DeWalt', 'Manguera de jardín DeWalt - categoría Jardín y Exteriores', 9, 9, 14.20, 22.01, 15, 'pza', TRUE),
(222, 'PROD-0222', '77800000222', 'Carretilla 25cm', 'Carretilla 25cm - categoría Jardín y Exteriores', 9, 9, 56.45, 87.50, 10, 'pza', TRUE),
(223, 'PROD-0223', '77800000223', 'Regadera plástica #10', 'Regadera plástica #10 - categoría Jardín y Exteriores', 9, 9, 23.42, 36.30, 15, 'pza', TRUE),
(224, 'PROD-0224', '77800000224', 'Pala punta cuadrada Truper', 'Pala punta cuadrada Truper - categoría Jardín y Exteriores', 9, 49, 41.52, 64.36, 15, 'pza', TRUE),
(225, 'PROD-0225', '77800000225', 'Pala punta cuadrada Genérico', 'Pala punta cuadrada Genérico - categoría Jardín y Exteriores', 9, 29, 15.74, 24.40, 5, 'pza', TRUE),
(226, 'PROD-0226', '77800000226', 'Bloque de concreto #12', 'Bloque de concreto #12 - categoría Materiales de Construcción', 10, 10, 8.29, 11.61, 25, 'saco', TRUE),
(227, 'PROD-0227', '77800000227', 'Cal hidratada DeWalt', 'Cal hidratada DeWalt - categoría Materiales de Construcción', 10, 50, 17.95, 25.13, 5, 'saco', TRUE),
(228, 'PROD-0228', '77800000228', 'Cal hidratada Stanley', 'Cal hidratada Stanley - categoría Materiales de Construcción', 10, 10, 17.23, 24.12, 10, 'saco', TRUE),
(229, 'PROD-0229', '77800000229', 'Malla electrosoldada 10cm', 'Malla electrosoldada 10cm - categoría Materiales de Construcción', 10, 10, 18.61, 26.05, 10, 'saco', TRUE),
(230, 'PROD-0230', '77800000230', 'Alambre de amarre 3/4"', 'Alambre de amarre 3/4" - categoría Materiales de Construcción', 10, 30, 11.59, 16.23, 5, 'saco', TRUE),
(231, 'PROD-0231', '77800000231', 'Varilla de acero 3/8" Makita', 'Varilla de acero 3/8" Makita - categoría Materiales de Construcción', 10, 10, 9.33, 13.06, 25, 'saco', TRUE),
(232, 'PROD-0232', '77800000232', 'Cal hidratada 3/4"', 'Cal hidratada 3/4" - categoría Materiales de Construcción', 10, 30, 12.98, 18.17, 15, 'saco', TRUE),
(233, 'PROD-0233', '77800000233', 'Varilla de acero 3/8" Stanley', 'Varilla de acero 3/8" Stanley - categoría Materiales de Construcción', 10, 30, 15.73, 22.02, 5, 'saco', TRUE),
(234, 'PROD-0234', '77800000234', 'Malla electrosoldada #10', 'Malla electrosoldada #10 - categoría Materiales de Construcción', 10, 30, 3.84, 5.38, 25, 'saco', TRUE),
(235, 'PROD-0235', '77800000235', 'Cal hidratada Surtek', 'Cal hidratada Surtek - categoría Materiales de Construcción', 10, 10, 14.01, 19.61, 10, 'saco', TRUE),
(236, 'PROD-0236', '77800000236', 'Varilla de acero 3/8" 18V', 'Varilla de acero 3/8" 18V - categoría Materiales de Construcción', 10, 50, 3.34, 4.68, 5, 'saco', TRUE),
(237, 'PROD-0237', '77800000237', 'Piedra picada (saco) Surtek', 'Piedra picada (saco) Surtek - categoría Materiales de Construcción', 10, 50, 5.94, 8.32, 5, 'saco', TRUE),
(238, 'PROD-0238', '77800000238', 'Varilla de acero 3/8" 3/4"', 'Varilla de acero 3/8" 3/4" - categoría Materiales de Construcción', 10, 50, 16.69, 23.37, 15, 'saco', TRUE),
(239, 'PROD-0239', '77800000239', 'Malla electrosoldada 15cm', 'Malla electrosoldada 15cm - categoría Materiales de Construcción', 10, 30, 17.48, 24.47, 20, 'saco', TRUE),
(240, 'PROD-0240', '77800000240', 'Bloque de concreto Grande', 'Bloque de concreto Grande - categoría Materiales de Construcción', 10, 30, 8.14, 11.40, 25, 'saco', TRUE),
(241, 'PROD-0241', '77800000241', 'Arena lavada (saco) Pretul', 'Arena lavada (saco) Pretul - categoría Materiales de Construcción', 10, 30, 12.94, 18.12, 5, 'saco', TRUE),
(242, 'PROD-0242', '77800000242', 'Malla electrosoldada Total', 'Malla electrosoldada Total - categoría Materiales de Construcción', 10, 50, 14.00, 19.60, 25, 'saco', TRUE),
(243, 'PROD-0243', '77800000243', 'Malla electrosoldada Chico', 'Malla electrosoldada Chico - categoría Materiales de Construcción', 10, 50, 4.84, 6.78, 5, 'saco', TRUE),
(244, 'PROD-0244', '77800000244', 'Arena lavada (saco) 1/2"', 'Arena lavada (saco) 1/2" - categoría Materiales de Construcción', 10, 50, 8.53, 11.94, 15, 'saco', TRUE),
(245, 'PROD-0245', '77800000245', 'Piedra picada (saco) Genérico', 'Piedra picada (saco) Genérico - categoría Materiales de Construcción', 10, 10, 5.55, 7.77, 10, 'saco', TRUE),
(246, 'PROD-0246', '77800000246', 'Bloque de concreto Truper', 'Bloque de concreto Truper - categoría Materiales de Construcción', 10, 30, 14.23, 19.92, 10, 'saco', TRUE),
(247, 'PROD-0247', '77800000247', 'Varilla de acero 3/8" 1kg', 'Varilla de acero 3/8" 1kg - categoría Materiales de Construcción', 10, 50, 11.46, 16.04, 15, 'saco', TRUE),
(248, 'PROD-0248', '77800000248', 'Piedra picada (saco) Truper', 'Piedra picada (saco) Truper - categoría Materiales de Construcción', 10, 30, 9.60, 13.44, 5, 'saco', TRUE),
(249, 'PROD-0249', '77800000249', 'Malla electrosoldada 25cm', 'Malla electrosoldada 25cm - categoría Materiales de Construcción', 10, 10, 8.83, 12.36, 10, 'saco', TRUE),
(250, 'PROD-0250', '77800000250', 'Piedra picada (saco) 110V', 'Piedra picada (saco) 110V - categoría Materiales de Construcción', 10, 50, 16.34, 22.88, 15, 'saco', TRUE),
(251, 'PROD-0251', '77800000251', 'Tira LED 5m 220V', 'Tira LED 5m 220V - categoría Iluminación', 11, 31, 24.69, 39.50, 15, 'pza', TRUE),
(252, 'PROD-0252', '77800000252', 'Lámpara de emergencia 5kg', 'Lámpara de emergencia 5kg - categoría Iluminación', 11, 11, 55.63, 89.01, 5, 'pza', TRUE),
(253, 'PROD-0253', '77800000253', 'Lámpara de emergencia Urrea', 'Lámpara de emergencia Urrea - categoría Iluminación', 11, 11, 22.71, 36.34, 20, 'pza', TRUE),
(254, 'PROD-0254', '77800000254', 'Lámpara de exteriores Surtek', 'Lámpara de exteriores Surtek - categoría Iluminación', 11, 11, 25.48, 40.77, 15, 'pza', TRUE),
(255, 'PROD-0255', '77800000255', 'Bombillo LED 12W Mediano', 'Bombillo LED 12W Mediano - categoría Iluminación', 11, 31, 27.22, 43.55, 20, 'pza', TRUE),
(256, 'PROD-0256', '77800000256', 'Tira LED 5m Genérico', 'Tira LED 5m Genérico - categoría Iluminación', 11, 31, 52.39, 83.82, 10, 'pza', TRUE),
(257, 'PROD-0257', '77800000257', 'Reflector LED 50W 15cm', 'Reflector LED 50W 15cm - categoría Iluminación', 11, 11, 63.84, 102.14, 10, 'pza', TRUE),
(258, 'PROD-0258', '77800000258', 'Poste de luz solar Urrea', 'Poste de luz solar Urrea - categoría Iluminación', 11, 31, 52.54, 84.06, 20, 'pza', TRUE),
(259, 'PROD-0259', '77800000259', 'Bombillo LED 12W 12V', 'Bombillo LED 12W 12V - categoría Iluminación', 11, 31, 51.09, 81.74, 25, 'pza', TRUE),
(260, 'PROD-0260', '77800000260', 'Lámpara de emergencia 220V', 'Lámpara de emergencia 220V - categoría Iluminación', 11, 31, 59.99, 95.98, 5, 'pza', TRUE),
(261, 'PROD-0261', '77800000261', 'Lámpara de emergencia Genérico', 'Lámpara de emergencia Genérico - categoría Iluminación', 11, 11, 5.99, 9.58, 15, 'pza', TRUE),
(262, 'PROD-0262', '77800000262', 'Tira LED 5m Stanley', 'Tira LED 5m Stanley - categoría Iluminación', 11, 11, 63.61, 101.78, 15, 'pza', TRUE),
(263, 'PROD-0263', '77800000263', 'Poste de luz solar Total', 'Poste de luz solar Total - categoría Iluminación', 11, 11, 29.27, 46.83, 10, 'pza', TRUE),
(264, 'PROD-0264', '77800000264', 'Lámpara de exteriores Total', 'Lámpara de exteriores Total - categoría Iluminación', 11, 31, 11.37, 18.19, 25, 'pza', TRUE),
(265, 'PROD-0265', '77800000265', 'Bombillo LED 12W Genérico', 'Bombillo LED 12W Genérico - categoría Iluminación', 11, 11, 2.73, 4.37, 10, 'pza', TRUE),
(266, 'PROD-0266', '77800000266', 'Tira LED 5m 18V', 'Tira LED 5m 18V - categoría Iluminación', 11, 31, 56.77, 90.83, 5, 'pza', TRUE),
(267, 'PROD-0267', '77800000267', 'Reflector LED 50W Urrea', 'Reflector LED 50W Urrea - categoría Iluminación', 11, 31, 17.18, 27.49, 15, 'pza', TRUE),
(268, 'PROD-0268', '77800000268', 'Lámpara de emergencia Truper', 'Lámpara de emergencia Truper - categoría Iluminación', 11, 11, 36.68, 58.69, 20, 'pza', TRUE),
(269, 'PROD-0269', '77800000269', 'Tira LED 5m Ingco', 'Tira LED 5m Ingco - categoría Iluminación', 11, 11, 30.92, 49.47, 25, 'pza', TRUE),
(270, 'PROD-0270', '77800000270', 'Panel LED cuadrado Black+Decker', 'Panel LED cuadrado Black+Decker - categoría Iluminación', 11, 31, 30.17, 48.27, 5, 'pza', TRUE),
(271, 'PROD-0271', '77800000271', 'Lámpara de emergencia 15cm', 'Lámpara de emergencia 15cm - categoría Iluminación', 11, 11, 27.42, 43.87, 5, 'pza', TRUE),
(272, 'PROD-0272', '77800000272', 'Panel LED cuadrado 1kg', 'Panel LED cuadrado 1kg - categoría Iluminación', 11, 11, 54.03, 86.45, 25, 'pza', TRUE),
(273, 'PROD-0273', '77800000273', 'Lámpara de exteriores 12V', 'Lámpara de exteriores 12V - categoría Iluminación', 11, 31, 58.08, 92.93, 25, 'pza', TRUE),
(274, 'PROD-0274', '77800000274', 'Reflector LED 50W Makita', 'Reflector LED 50W Makita - categoría Iluminación', 11, 31, 24.89, 39.82, 5, 'pza', TRUE),
(275, 'PROD-0275', '77800000275', 'Bombillo LED 12W Truper', 'Bombillo LED 12W Truper - categoría Iluminación', 11, 31, 37.29, 59.66, 25, 'pza', TRUE),
(276, 'PROD-0276', '77800000276', 'Nivel láser Chico', 'Nivel láser Chico - categoría Medición y Nivelación', 12, 12, 80.61, 120.91, 15, 'pza', TRUE),
(277, 'PROD-0277', '77800000277', 'Nivel láser Makita', 'Nivel láser Makita - categoría Medición y Nivelación', 12, 12, 114.19, 171.28, 25, 'pza', TRUE),
(278, 'PROD-0278', '77800000278', 'Plomada 15cm', 'Plomada 15cm - categoría Medición y Nivelación', 12, 12, 16.42, 24.63, 15, 'pza', TRUE),
(279, 'PROD-0279', '77800000279', 'Plomada #12', 'Plomada #12 - categoría Medición y Nivelación', 12, 12, 115.19, 172.78, 15, 'pza', TRUE),
(280, 'PROD-0280', '77800000280', 'Escuadra metálica 5kg', 'Escuadra metálica 5kg - categoría Medición y Nivelación', 12, 32, 33.02, 49.53, 15, 'pza', TRUE),
(281, 'PROD-0281', '77800000281', 'Plomada 5kg', 'Plomada 5kg - categoría Medición y Nivelación', 12, 32, 63.17, 94.75, 25, 'pza', TRUE),
(282, 'PROD-0282', '77800000282', 'Escuadra metálica 3/4"', 'Escuadra metálica 3/4" - categoría Medición y Nivelación', 12, 12, 37.68, 56.52, 10, 'pza', TRUE),
(283, 'PROD-0283', '77800000283', 'Escuadra metálica #10', 'Escuadra metálica #10 - categoría Medición y Nivelación', 12, 12, 113.94, 170.91, 10, 'pza', TRUE),
(284, 'PROD-0284', '77800000284', 'Nivel de mano 60cm DeWalt', 'Nivel de mano 60cm DeWalt - categoría Medición y Nivelación', 12, 12, 76.35, 114.52, 10, 'pza', TRUE),
(285, 'PROD-0285', '77800000285', 'Nivel láser Mediano', 'Nivel láser Mediano - categoría Medición y Nivelación', 12, 32, 100.77, 151.16, 10, 'pza', TRUE),
(286, 'PROD-0286', '77800000286', 'Calibrador vernier 18V', 'Calibrador vernier 18V - categoría Medición y Nivelación', 12, 12, 90.39, 135.59, 10, 'pza', TRUE),
(287, 'PROD-0287', '77800000287', 'Nivel láser 1"', 'Nivel láser 1" - categoría Medición y Nivelación', 12, 32, 7.43, 11.14, 5, 'pza', TRUE),
(288, 'PROD-0288', '77800000288', 'Plomada 25cm', 'Plomada 25cm - categoría Medición y Nivelación', 12, 32, 68.30, 102.45, 25, 'pza', TRUE),
(289, 'PROD-0289', '77800000289', 'Nivel de mano 60cm Makita', 'Nivel de mano 60cm Makita - categoría Medición y Nivelación', 12, 32, 50.69, 76.03, 20, 'pza', TRUE),
(290, 'PROD-0290', '77800000290', 'Flexómetro 5m 18V', 'Flexómetro 5m 18V - categoría Medición y Nivelación', 12, 32, 40.02, 60.03, 20, 'pza', TRUE),
(291, 'PROD-0291', '77800000291', 'Flexómetro 5m 1 gal', 'Flexómetro 5m 1 gal - categoría Medición y Nivelación', 12, 12, 78.05, 117.07, 25, 'pza', TRUE),
(292, 'PROD-0292', '77800000292', 'Nivel de mano 60cm 15cm', 'Nivel de mano 60cm 15cm - categoría Medición y Nivelación', 12, 12, 85.00, 127.50, 5, 'pza', TRUE),
(293, 'PROD-0293', '77800000293', 'Flexómetro 5m 220V', 'Flexómetro 5m 220V - categoría Medición y Nivelación', 12, 12, 36.91, 55.36, 15, 'pza', TRUE),
(294, 'PROD-0294', '77800000294', 'Flexómetro 5m Total', 'Flexómetro 5m Total - categoría Medición y Nivelación', 12, 32, 47.63, 71.45, 5, 'pza', TRUE),
(295, 'PROD-0295', '77800000295', 'Escuadra metálica 10cm', 'Escuadra metálica 10cm - categoría Medición y Nivelación', 12, 12, 18.96, 28.44, 20, 'pza', TRUE),
(296, 'PROD-0296', '77800000296', 'Nivel láser 10cm', 'Nivel láser 10cm - categoría Medición y Nivelación', 12, 12, 37.87, 56.80, 15, 'pza', TRUE),
(297, 'PROD-0297', '77800000297', 'Plomada Total', 'Plomada Total - categoría Medición y Nivelación', 12, 12, 17.42, 26.13, 5, 'pza', TRUE),
(298, 'PROD-0298', '77800000298', 'Flexómetro 5m 110V', 'Flexómetro 5m 110V - categoría Medición y Nivelación', 12, 32, 65.85, 98.77, 5, 'pza', TRUE),
(299, 'PROD-0299', '77800000299', 'Detector de metales Total', 'Detector de metales Total - categoría Medición y Nivelación', 12, 32, 27.77, 41.66, 25, 'pza', TRUE),
(300, 'PROD-0300', '77800000300', 'Calibrador vernier Stanley', 'Calibrador vernier Stanley - categoría Medición y Nivelación', 12, 12, 28.83, 43.24, 15, 'pza', TRUE);
INSERT INTO productos (id, codigo, codigo_barras, nombre, descripcion, categoria_id, proveedor_id, precio_compra, precio_venta, stock_minimo, unidad_medida, activo) VALUES
(301, 'PROD-0301', '77800000301', 'Canaleta PVC Total', 'Canaleta PVC Total - categoría Techos e Impermeabilización', 13, 33, 15.54, 22.53, 10, 'pza', TRUE),
(302, 'PROD-0302', '77800000302', 'Canaleta PVC Grande', 'Canaleta PVC Grande - categoría Techos e Impermeabilización', 13, 13, 17.18, 24.91, 10, 'pza', TRUE),
(303, 'PROD-0303', '77800000303', 'Lámina de zinc Ingco', 'Lámina de zinc Ingco - categoría Techos e Impermeabilización', 13, 13, 11.74, 17.02, 25, 'pza', TRUE),
(304, 'PROD-0304', '77800000304', 'Membrana impermeabilizante Stanley', 'Membrana impermeabilizante Stanley - categoría Techos e Impermeabilización', 13, 13, 7.80, 11.31, 15, 'pza', TRUE),
(305, 'PROD-0305', '77800000305', 'Tornillo autoperforante con arandela 110V', 'Tornillo autoperforante con arandela 110V - categoría Techos e Impermeabilización', 13, 33, 34.97, 50.71, 25, 'pza', TRUE),
(306, 'PROD-0306', '77800000306', 'Tornillo autoperforante con arandela Bosch', 'Tornillo autoperforante con arandela Bosch - categoría Techos e Impermeabilización', 13, 33, 52.36, 75.92, 10, 'pza', TRUE),
(307, 'PROD-0307', '77800000307', 'Tornillo autoperforante con arandela Genérico', 'Tornillo autoperforante con arandela Genérico - categoría Techos e Impermeabilización', 13, 33, 48.37, 70.14, 10, 'pza', TRUE),
(308, 'PROD-0308', '77800000308', 'Teja asfáltica 1 gal', 'Teja asfáltica 1 gal - categoría Techos e Impermeabilización', 13, 13, 44.73, 64.86, 10, 'pza', TRUE),
(309, 'PROD-0309', '77800000309', 'Manto asfáltico Mediano', 'Manto asfáltico Mediano - categoría Techos e Impermeabilización', 13, 33, 13.39, 19.42, 15, 'pza', TRUE),
(310, 'PROD-0310', '77800000310', 'Canaleta PVC #8', 'Canaleta PVC #8 - categoría Techos e Impermeabilización', 13, 13, 16.49, 23.91, 20, 'pza', TRUE),
(311, 'PROD-0311', '77800000311', 'Tornillo autoperforante con arandela Makita', 'Tornillo autoperforante con arandela Makita - categoría Techos e Impermeabilización', 13, 13, 15.12, 21.92, 25, 'pza', TRUE),
(312, 'PROD-0312', '77800000312', 'Lámina de zinc 1/2"', 'Lámina de zinc 1/2" - categoría Techos e Impermeabilización', 13, 13, 35.28, 51.16, 10, 'pza', TRUE),
(313, 'PROD-0313', '77800000313', 'Membrana impermeabilizante 110V', 'Membrana impermeabilizante 110V - categoría Techos e Impermeabilización', 13, 13, 48.89, 70.89, 25, 'pza', TRUE),
(314, 'PROD-0314', '77800000314', 'Manto asfáltico Black+Decker', 'Manto asfáltico Black+Decker - categoría Techos e Impermeabilización', 13, 13, 29.11, 42.21, 10, 'pza', TRUE),
(315, 'PROD-0315', '77800000315', 'Canaleta PVC Mediano', 'Canaleta PVC Mediano - categoría Techos e Impermeabilización', 13, 13, 49.85, 72.28, 20, 'pza', TRUE),
(316, 'PROD-0316', '77800000316', 'Manto asfáltico DeWalt', 'Manto asfáltico DeWalt - categoría Techos e Impermeabilización', 13, 33, 48.74, 70.67, 25, 'pza', TRUE),
(317, 'PROD-0317', '77800000317', 'Lámina de zinc #12', 'Lámina de zinc #12 - categoría Techos e Impermeabilización', 13, 33, 44.48, 64.50, 20, 'pza', TRUE),
(318, 'PROD-0318', '77800000318', 'Teja asfáltica Chico', 'Teja asfáltica Chico - categoría Techos e Impermeabilización', 13, 13, 26.27, 38.09, 15, 'pza', TRUE),
(319, 'PROD-0319', '77800000319', 'Lámina de zinc Pretul', 'Lámina de zinc Pretul - categoría Techos e Impermeabilización', 13, 13, 19.16, 27.78, 25, 'pza', TRUE),
(320, 'PROD-0320', '77800000320', 'Manto asfáltico Grande', 'Manto asfáltico Grande - categoría Techos e Impermeabilización', 13, 13, 5.27, 7.64, 5, 'pza', TRUE),
(321, 'PROD-0321', '77800000321', 'Tornillo autoperforante con arandela Chico', 'Tornillo autoperforante con arandela Chico - categoría Techos e Impermeabilización', 13, 13, 30.99, 44.94, 20, 'pza', TRUE),
(322, 'PROD-0322', '77800000322', 'Teja asfáltica Urrea', 'Teja asfáltica Urrea - categoría Techos e Impermeabilización', 13, 33, 25.44, 36.89, 5, 'pza', TRUE),
(323, 'PROD-0323', '77800000323', 'Tornillo autoperforante con arandela Black+Decker', 'Tornillo autoperforante con arandela Black+Decker - categoría Techos e Impermeabilización', 13, 33, 18.07, 26.20, 25, 'pza', TRUE),
(324, 'PROD-0324', '77800000324', 'Lámina de zinc Truper', 'Lámina de zinc Truper - categoría Techos e Impermeabilización', 13, 33, 35.59, 51.61, 20, 'pza', TRUE),
(325, 'PROD-0325', '77800000325', 'Canaleta PVC Pretul', 'Canaleta PVC Pretul - categoría Techos e Impermeabilización', 13, 33, 32.61, 47.28, 25, 'pza', TRUE),
(326, 'PROD-0326', '77800000326', 'Escoba industrial Ingco', 'Escoba industrial Ingco - categoría Limpieza y Químicos Industriales', 14, 34, 15.36, 24.58, 15, 'pza', TRUE),
(327, 'PROD-0327', '77800000327', 'Detergente concentrado #8', 'Detergente concentrado #8 - categoría Limpieza y Químicos Industriales', 14, 34, 6.30, 10.08, 15, 'pza', TRUE),
(328, 'PROD-0328', '77800000328', 'Cloro industrial 5 gal', 'Cloro industrial 5 gal - categoría Limpieza y Químicos Industriales', 14, 34, 24.05, 38.48, 15, 'pza', TRUE),
(329, 'PROD-0329', '77800000329', 'Guantes de limpieza 15cm', 'Guantes de limpieza 15cm - categoría Limpieza y Químicos Industriales', 14, 34, 11.75, 18.80, 20, 'pza', TRUE),
(330, 'PROD-0330', '77800000330', 'Detergente concentrado 15cm', 'Detergente concentrado 15cm - categoría Limpieza y Químicos Industriales', 14, 34, 21.65, 34.64, 15, 'pza', TRUE),
(331, 'PROD-0331', '77800000331', 'Ambientador industrial Stanley', 'Ambientador industrial Stanley - categoría Limpieza y Químicos Industriales', 14, 14, 24.52, 39.23, 10, 'pza', TRUE),
(332, 'PROD-0332', '77800000332', 'Detergente concentrado 1/2"', 'Detergente concentrado 1/2" - categoría Limpieza y Químicos Industriales', 14, 14, 14.77, 23.63, 20, 'pza', TRUE),
(333, 'PROD-0333', '77800000333', 'Desengrasante industrial 18V', 'Desengrasante industrial 18V - categoría Limpieza y Químicos Industriales', 14, 14, 12.88, 20.61, 5, 'pza', TRUE),
(334, 'PROD-0334', '77800000334', 'Guantes de limpieza #10', 'Guantes de limpieza #10 - categoría Limpieza y Químicos Industriales', 14, 34, 5.09, 8.14, 25, 'pza', TRUE),
(335, 'PROD-0335', '77800000335', 'Ambientador industrial 5kg', 'Ambientador industrial 5kg - categoría Limpieza y Químicos Industriales', 14, 34, 8.90, 14.24, 25, 'pza', TRUE),
(336, 'PROD-0336', '77800000336', 'Ambientador industrial Ingco', 'Ambientador industrial Ingco - categoría Limpieza y Químicos Industriales', 14, 14, 11.16, 17.86, 10, 'pza', TRUE),
(337, 'PROD-0337', '77800000337', 'Cloro industrial Urrea', 'Cloro industrial Urrea - categoría Limpieza y Químicos Industriales', 14, 34, 12.96, 20.74, 5, 'pza', TRUE),
(338, 'PROD-0338', '77800000338', 'Desengrasante industrial Ingco', 'Desengrasante industrial Ingco - categoría Limpieza y Químicos Industriales', 14, 34, 10.30, 16.48, 25, 'pza', TRUE),
(339, 'PROD-0339', '77800000339', 'Desengrasante industrial 5kg', 'Desengrasante industrial 5kg - categoría Limpieza y Químicos Industriales', 14, 34, 2.46, 3.94, 5, 'pza', TRUE),
(340, 'PROD-0340', '77800000340', 'Detergente concentrado #12', 'Detergente concentrado #12 - categoría Limpieza y Químicos Industriales', 14, 14, 15.27, 24.43, 25, 'pza', TRUE),
(341, 'PROD-0341', '77800000341', 'Escoba industrial Genérico', 'Escoba industrial Genérico - categoría Limpieza y Químicos Industriales', 14, 34, 9.48, 15.17, 25, 'pza', TRUE),
(342, 'PROD-0342', '77800000342', 'Desengrasante industrial 5 gal', 'Desengrasante industrial 5 gal - categoría Limpieza y Químicos Industriales', 14, 14, 10.76, 17.22, 15, 'pza', TRUE),
(343, 'PROD-0343', '77800000343', 'Ambientador industrial 18V', 'Ambientador industrial 18V - categoría Limpieza y Químicos Industriales', 14, 14, 16.73, 26.77, 15, 'pza', TRUE),
(344, 'PROD-0344', '77800000344', 'Detergente concentrado Ingco', 'Detergente concentrado Ingco - categoría Limpieza y Químicos Industriales', 14, 34, 21.52, 34.43, 5, 'pza', TRUE),
(345, 'PROD-0345', '77800000345', 'Ambientador industrial Total', 'Ambientador industrial Total - categoría Limpieza y Químicos Industriales', 14, 34, 7.25, 11.60, 15, 'pza', TRUE),
(346, 'PROD-0346', '77800000346', 'Ambientador industrial Mediano', 'Ambientador industrial Mediano - categoría Limpieza y Químicos Industriales', 14, 34, 8.24, 13.18, 20, 'pza', TRUE),
(347, 'PROD-0347', '77800000347', 'Detergente concentrado 10cm', 'Detergente concentrado 10cm - categoría Limpieza y Químicos Industriales', 14, 34, 11.52, 18.43, 10, 'pza', TRUE),
(348, 'PROD-0348', '77800000348', 'Desengrasante industrial DeWalt', 'Desengrasante industrial DeWalt - categoría Limpieza y Químicos Industriales', 14, 14, 17.23, 27.57, 5, 'pza', TRUE),
(349, 'PROD-0349', '77800000349', 'Desengrasante industrial 1kg', 'Desengrasante industrial 1kg - categoría Limpieza y Químicos Industriales', 14, 34, 4.37, 6.99, 5, 'pza', TRUE),
(350, 'PROD-0350', '77800000350', 'Escoba industrial Chico', 'Escoba industrial Chico - categoría Limpieza y Químicos Industriales', 14, 14, 2.61, 4.18, 20, 'pza', TRUE),
(351, 'PROD-0351', '77800000351', 'Careta fotosensible Black+Decker', 'Careta fotosensible Black+Decker - categoría Soldadura', 15, 15, 81.23, 121.84, 25, 'pza', TRUE),
(352, 'PROD-0352', '77800000352', 'Soldadora inversora 12V', 'Soldadora inversora 12V - categoría Soldadura', 15, 35, 184.10, 276.15, 5, 'pza', TRUE),
(353, 'PROD-0353', '77800000353', 'Careta de soldar 1/2"', 'Careta de soldar 1/2" - categoría Soldadura', 15, 35, 181.93, 272.89, 15, 'pza', TRUE),
(354, 'PROD-0354', '77800000354', 'Boquilla para soplete 1/2"', 'Boquilla para soplete 1/2" - categoría Soldadura', 15, 35, 141.08, 211.62, 25, 'pza', TRUE),
(355, 'PROD-0355', '77800000355', 'Guantes de soldar 1/2"', 'Guantes de soldar 1/2" - categoría Soldadura', 15, 15, 142.98, 214.47, 25, 'pza', TRUE),
(356, 'PROD-0356', '77800000356', 'Guantes de soldar 25cm', 'Guantes de soldar 25cm - categoría Soldadura', 15, 35, 71.01, 106.52, 25, 'pza', TRUE),
(357, 'PROD-0357', '77800000357', 'Guantes de soldar #8', 'Guantes de soldar #8 - categoría Soldadura', 15, 15, 20.01, 30.02, 10, 'pza', TRUE),
(358, 'PROD-0358', '77800000358', 'Boquilla para soplete 220V', 'Boquilla para soplete 220V - categoría Soldadura', 15, 35, 136.78, 205.17, 20, 'pza', TRUE),
(359, 'PROD-0359', '77800000359', 'Guantes de soldar #12', 'Guantes de soldar #12 - categoría Soldadura', 15, 35, 184.21, 276.31, 5, 'pza', TRUE),
(360, 'PROD-0360', '77800000360', 'Soldadora inversora Stanley', 'Soldadora inversora Stanley - categoría Soldadura', 15, 15, 82.21, 123.31, 5, 'pza', TRUE),
(361, 'PROD-0361', '77800000361', 'Alambre MIG 18V', 'Alambre MIG 18V - categoría Soldadura', 15, 35, 49.90, 74.85, 20, 'pza', TRUE),
(362, 'PROD-0362', '77800000362', 'Careta de soldar 1 gal', 'Careta de soldar 1 gal - categoría Soldadura', 15, 15, 154.42, 231.63, 15, 'pza', TRUE),
(363, 'PROD-0363', '77800000363', 'Boquilla para soplete 5 gal', 'Boquilla para soplete 5 gal - categoría Soldadura', 15, 15, 26.78, 40.17, 5, 'pza', TRUE),
(364, 'PROD-0364', '77800000364', 'Guantes de soldar 220V', 'Guantes de soldar 220V - categoría Soldadura', 15, 15, 218.75, 328.12, 10, 'pza', TRUE),
(365, 'PROD-0365', '77800000365', 'Boquilla para soplete #12', 'Boquilla para soplete #12 - categoría Soldadura', 15, 15, 27.39, 41.09, 25, 'pza', TRUE),
(366, 'PROD-0366', '77800000366', 'Guantes de soldar 3/4"', 'Guantes de soldar 3/4" - categoría Soldadura', 15, 35, 132.88, 199.32, 25, 'pza', TRUE),
(367, 'PROD-0367', '77800000367', 'Electrodo 6013 Makita', 'Electrodo 6013 Makita - categoría Soldadura', 15, 35, 57.52, 86.28, 5, 'pza', TRUE),
(368, 'PROD-0368', '77800000368', 'Electrodo 6013 110V', 'Electrodo 6013 110V - categoría Soldadura', 15, 35, 232.32, 348.48, 5, 'pza', TRUE),
(369, 'PROD-0369', '77800000369', 'Careta de soldar 5kg', 'Careta de soldar 5kg - categoría Soldadura', 15, 35, 140.83, 211.25, 20, 'pza', TRUE),
(370, 'PROD-0370', '77800000370', 'Careta fotosensible Stanley', 'Careta fotosensible Stanley - categoría Soldadura', 15, 35, 133.67, 200.50, 5, 'pza', TRUE),
(371, 'PROD-0371', '77800000371', 'Boquilla para soplete Genérico', 'Boquilla para soplete Genérico - categoría Soldadura', 15, 15, 240.26, 360.39, 5, 'pza', TRUE),
(372, 'PROD-0372', '77800000372', 'Boquilla para soplete Pretul', 'Boquilla para soplete Pretul - categoría Soldadura', 15, 35, 49.80, 74.70, 25, 'pza', TRUE),
(373, 'PROD-0373', '77800000373', 'Alambre MIG Truper', 'Alambre MIG Truper - categoría Soldadura', 15, 15, 140.93, 211.40, 25, 'pza', TRUE),
(374, 'PROD-0374', '77800000374', 'Careta fotosensible 110V', 'Careta fotosensible 110V - categoría Soldadura', 15, 35, 169.60, 254.40, 20, 'pza', TRUE),
(375, 'PROD-0375', '77800000375', 'Guantes de soldar 1"', 'Guantes de soldar 1" - categoría Soldadura', 15, 15, 138.84, 208.26, 25, 'pza', TRUE),
(376, 'PROD-0376', '77800000376', 'Aceite de motor 20W50 220V', 'Aceite de motor 20W50 220V - categoría Automotriz', 16, 36, 78.19, 121.19, 5, 'pza', TRUE),
(377, 'PROD-0377', '77800000377', 'Foco H4 5 gal', 'Foco H4 5 gal - categoría Automotriz', 16, 16, 88.24, 136.77, 25, 'pza', TRUE),
(378, 'PROD-0378', '77800000378', 'Filtro de aceite Truper', 'Filtro de aceite Truper - categoría Automotriz', 16, 36, 45.17, 70.01, 25, 'pza', TRUE),
(379, 'PROD-0379', '77800000379', 'Batería de auto Mediano', 'Batería de auto Mediano - categoría Automotriz', 16, 16, 20.30, 31.47, 20, 'pza', TRUE),
(380, 'PROD-0380', '77800000380', 'Líquido de frenos #8', 'Líquido de frenos #8 - categoría Automotriz', 16, 16, 24.52, 38.01, 25, 'pza', TRUE),
(381, 'PROD-0381', '77800000381', 'Filtro de aceite 10cm', 'Filtro de aceite 10cm - categoría Automotriz', 16, 36, 37.23, 57.71, 10, 'pza', TRUE),
(382, 'PROD-0382', '77800000382', 'Limpiador de inyectores Bosch', 'Limpiador de inyectores Bosch - categoría Automotriz', 16, 36, 33.92, 52.58, 15, 'pza', TRUE),
(383, 'PROD-0383', '77800000383', 'Limpiador de inyectores Surtek', 'Limpiador de inyectores Surtek - categoría Automotriz', 16, 36, 73.63, 114.13, 15, 'pza', TRUE),
(384, 'PROD-0384', '77800000384', 'Batería de auto Ingco', 'Batería de auto Ingco - categoría Automotriz', 16, 36, 22.63, 35.08, 15, 'pza', TRUE),
(385, 'PROD-0385', '77800000385', 'Líquido de frenos 25cm', 'Líquido de frenos 25cm - categoría Automotriz', 16, 16, 66.50, 103.08, 25, 'pza', TRUE),
(386, 'PROD-0386', '77800000386', 'Batería de auto Black+Decker', 'Batería de auto Black+Decker - categoría Automotriz', 16, 16, 39.31, 60.93, 25, 'pza', TRUE),
(387, 'PROD-0387', '77800000387', 'Filtro de aceite 5 gal', 'Filtro de aceite 5 gal - categoría Automotriz', 16, 36, 82.15, 127.33, 15, 'pza', TRUE),
(388, 'PROD-0388', '77800000388', 'Filtro de aceite Surtek', 'Filtro de aceite Surtek - categoría Automotriz', 16, 16, 20.99, 32.53, 10, 'pza', TRUE),
(389, 'PROD-0389', '77800000389', 'Batería de auto 1 gal', 'Batería de auto 1 gal - categoría Automotriz', 16, 16, 78.05, 120.98, 15, 'pza', TRUE),
(390, 'PROD-0390', '77800000390', 'Kit de herramientas automotriz 10cm', 'Kit de herramientas automotriz 10cm - categoría Automotriz', 16, 36, 89.62, 138.91, 10, 'pza', TRUE),
(391, 'PROD-0391', '77800000391', 'Limpiador de inyectores 15cm', 'Limpiador de inyectores 15cm - categoría Automotriz', 16, 16, 30.62, 47.46, 25, 'pza', TRUE),
(392, 'PROD-0392', '77800000392', 'Foco H4 25cm', 'Foco H4 25cm - categoría Automotriz', 16, 16, 30.63, 47.48, 10, 'pza', TRUE),
(393, 'PROD-0393', '77800000393', 'Foco H4 Stanley', 'Foco H4 Stanley - categoría Automotriz', 16, 36, 79.57, 123.33, 20, 'pza', TRUE),
(394, 'PROD-0394', '77800000394', 'Filtro de aceite Ingco', 'Filtro de aceite Ingco - categoría Automotriz', 16, 36, 67.87, 105.20, 15, 'pza', TRUE),
(395, 'PROD-0395', '77800000395', 'Foco H4 #8', 'Foco H4 #8 - categoría Automotriz', 16, 16, 14.44, 22.38, 5, 'pza', TRUE),
(396, 'PROD-0396', '77800000396', 'Filtro de aceite DeWalt', 'Filtro de aceite DeWalt - categoría Automotriz', 16, 36, 55.69, 86.32, 25, 'pza', TRUE),
(397, 'PROD-0397', '77800000397', 'Batería de auto Grande', 'Batería de auto Grande - categoría Automotriz', 16, 16, 23.09, 35.79, 5, 'pza', TRUE),
(398, 'PROD-0398', '77800000398', 'Aceite de motor 20W50 18V', 'Aceite de motor 20W50 18V - categoría Automotriz', 16, 36, 14.67, 22.74, 25, 'pza', TRUE),
(399, 'PROD-0399', '77800000399', 'Líquido de frenos Truper', 'Líquido de frenos Truper - categoría Automotriz', 16, 16, 77.14, 119.57, 20, 'pza', TRUE),
(400, 'PROD-0400', '77800000400', 'Batería de auto DeWalt', 'Batería de auto DeWalt - categoría Automotriz', 16, 16, 19.81, 30.71, 5, 'pza', TRUE);
INSERT INTO productos (id, codigo, codigo_barras, nombre, descripcion, categoria_id, proveedor_id, precio_compra, precio_venta, stock_minimo, unidad_medida, activo) VALUES
(401, 'PROD-0401', '77800000401', 'Vibrador de concreto 5 gal', 'Vibrador de concreto 5 gal - categoría Maquinaria y Equipos', 17, 37, 503.11, 679.20, 5, 'pza', TRUE),
(402, 'PROD-0402', '77800000402', 'Generador eléctrico Surtek', 'Generador eléctrico Surtek - categoría Maquinaria y Equipos', 17, 37, 545.29, 736.14, 25, 'pza', TRUE),
(403, 'PROD-0403', '77800000403', 'Cortadora de concreto Ingco', 'Cortadora de concreto Ingco - categoría Maquinaria y Equipos', 17, 17, 231.98, 313.17, 25, 'pza', TRUE),
(404, 'PROD-0404', '77800000404', 'Cortadora de concreto Urrea', 'Cortadora de concreto Urrea - categoría Maquinaria y Equipos', 17, 37, 470.29, 634.89, 15, 'pza', TRUE),
(405, 'PROD-0405', '77800000405', 'Cortadora de concreto 1"', 'Cortadora de concreto 1" - categoría Maquinaria y Equipos', 17, 37, 845.33, 1141.20, 25, 'pza', TRUE),
(406, 'PROD-0406', '77800000406', 'Vibrador de concreto Mediano', 'Vibrador de concreto Mediano - categoría Maquinaria y Equipos', 17, 37, 519.38, 701.16, 10, 'pza', TRUE),
(407, 'PROD-0407', '77800000407', 'Motobomba Stanley', 'Motobomba Stanley - categoría Maquinaria y Equipos', 17, 37, 884.44, 1193.99, 5, 'pza', TRUE),
(408, 'PROD-0408', '77800000408', 'Mezcladora de cemento #12', 'Mezcladora de cemento #12 - categoría Maquinaria y Equipos', 17, 37, 619.10, 835.79, 5, 'pza', TRUE),
(409, 'PROD-0409', '77800000409', 'Generador eléctrico DeWalt', 'Generador eléctrico DeWalt - categoría Maquinaria y Equipos', 17, 37, 460.53, 621.72, 15, 'pza', TRUE),
(410, 'PROD-0410', '77800000410', 'Compactadora manual #10', 'Compactadora manual #10 - categoría Maquinaria y Equipos', 17, 17, 158.47, 213.93, 10, 'pza', TRUE),
(411, 'PROD-0411', '77800000411', 'Mezcladora de cemento Grande', 'Mezcladora de cemento Grande - categoría Maquinaria y Equipos', 17, 37, 742.65, 1002.58, 15, 'pza', TRUE),
(412, 'PROD-0412', '77800000412', 'Motobomba 1/2"', 'Motobomba 1/2" - categoría Maquinaria y Equipos', 17, 37, 725.27, 979.11, 5, 'pza', TRUE),
(413, 'PROD-0413', '77800000413', 'Cortadora de concreto 10cm', 'Cortadora de concreto 10cm - categoría Maquinaria y Equipos', 17, 37, 447.04, 603.50, 25, 'pza', TRUE),
(414, 'PROD-0414', '77800000414', 'Vibrador de concreto 10cm', 'Vibrador de concreto 10cm - categoría Maquinaria y Equipos', 17, 17, 295.51, 398.94, 15, 'pza', TRUE),
(415, 'PROD-0415', '77800000415', 'Generador eléctrico Pretul', 'Generador eléctrico Pretul - categoría Maquinaria y Equipos', 17, 17, 634.24, 856.22, 20, 'pza', TRUE),
(416, 'PROD-0416', '77800000416', 'Vibrador de concreto 15cm', 'Vibrador de concreto 15cm - categoría Maquinaria y Equipos', 17, 37, 496.85, 670.75, 20, 'pza', TRUE),
(417, 'PROD-0417', '77800000417', 'Generador eléctrico Grande', 'Generador eléctrico Grande - categoría Maquinaria y Equipos', 17, 37, 573.71, 774.51, 15, 'pza', TRUE),
(418, 'PROD-0418', '77800000418', 'Mezcladora de cemento 18V', 'Mezcladora de cemento 18V - categoría Maquinaria y Equipos', 17, 17, 306.20, 413.37, 15, 'pza', TRUE),
(419, 'PROD-0419', '77800000419', 'Compactadora manual Mediano', 'Compactadora manual Mediano - categoría Maquinaria y Equipos', 17, 17, 360.35, 486.47, 5, 'pza', TRUE),
(420, 'PROD-0420', '77800000420', 'Cortadora de concreto Black+Decker', 'Cortadora de concreto Black+Decker - categoría Maquinaria y Equipos', 17, 17, 578.24, 780.62, 20, 'pza', TRUE),
(421, 'PROD-0421', '77800000421', 'Mezcladora de cemento Chico', 'Mezcladora de cemento Chico - categoría Maquinaria y Equipos', 17, 17, 798.45, 1077.91, 15, 'pza', TRUE),
(422, 'PROD-0422', '77800000422', 'Motobomba #8', 'Motobomba #8 - categoría Maquinaria y Equipos', 17, 37, 460.40, 621.54, 20, 'pza', TRUE),
(423, 'PROD-0423', '77800000423', 'Cortadora de concreto Truper', 'Cortadora de concreto Truper - categoría Maquinaria y Equipos', 17, 37, 772.09, 1042.32, 15, 'pza', TRUE),
(424, 'PROD-0424', '77800000424', 'Mezcladora de cemento 1/2"', 'Mezcladora de cemento 1/2" - categoría Maquinaria y Equipos', 17, 37, 281.26, 379.70, 15, 'pza', TRUE),
(425, 'PROD-0425', '77800000425', 'Vibrador de concreto 5kg', 'Vibrador de concreto 5kg - categoría Maquinaria y Equipos', 17, 17, 482.83, 651.82, 5, 'pza', TRUE),
(426, 'PROD-0426', '77800000426', 'Lavamanos de pedestal Bosch', 'Lavamanos de pedestal Bosch - categoría Grifería y Sanitarios', 18, 18, 25.39, 38.09, 10, 'pza', TRUE),
(427, 'PROD-0427', '77800000427', 'Ducha telefónica Chico', 'Ducha telefónica Chico - categoría Grifería y Sanitarios', 18, 18, 122.35, 183.52, 20, 'pza', TRUE),
(428, 'PROD-0428', '77800000428', 'Lavamanos de pedestal 110V', 'Lavamanos de pedestal 110V - categoría Grifería y Sanitarios', 18, 38, 114.65, 171.98, 20, 'pza', TRUE),
(429, 'PROD-0429', '77800000429', 'Lavamanos de pedestal #8', 'Lavamanos de pedestal #8 - categoría Grifería y Sanitarios', 18, 38, 60.96, 91.44, 15, 'pza', TRUE),
(430, 'PROD-0430', '77800000430', 'Fregadero de acero inoxidable 10cm', 'Fregadero de acero inoxidable 10cm - categoría Grifería y Sanitarios', 18, 18, 109.06, 163.59, 25, 'pza', TRUE),
(431, 'PROD-0431', '77800000431', 'Grifo monomando Truper', 'Grifo monomando Truper - categoría Grifería y Sanitarios', 18, 18, 154.93, 232.40, 5, 'pza', TRUE),
(432, 'PROD-0432', '77800000432', 'Llave de cocina 25cm', 'Llave de cocina 25cm - categoría Grifería y Sanitarios', 18, 18, 10.14, 15.21, 25, 'pza', TRUE),
(433, 'PROD-0433', '77800000433', 'Fregadero de acero inoxidable DeWalt', 'Fregadero de acero inoxidable DeWalt - categoría Grifería y Sanitarios', 18, 18, 156.41, 234.62, 5, 'pza', TRUE),
(434, 'PROD-0434', '77800000434', 'Ducha telefónica 110V', 'Ducha telefónica 110V - categoría Grifería y Sanitarios', 18, 38, 116.29, 174.44, 20, 'pza', TRUE),
(435, 'PROD-0435', '77800000435', 'Ducha telefónica Black+Decker', 'Ducha telefónica Black+Decker - categoría Grifería y Sanitarios', 18, 38, 102.67, 154.00, 10, 'pza', TRUE),
(436, 'PROD-0436', '77800000436', 'Fregadero de acero inoxidable 1"', 'Fregadero de acero inoxidable 1" - categoría Grifería y Sanitarios', 18, 18, 104.42, 156.63, 15, 'pza', TRUE),
(437, 'PROD-0437', '77800000437', 'Fregadero de acero inoxidable Ingco', 'Fregadero de acero inoxidable Ingco - categoría Grifería y Sanitarios', 18, 38, 179.57, 269.36, 10, 'pza', TRUE),
(438, 'PROD-0438', '77800000438', 'Llave de cocina 1/2"', 'Llave de cocina 1/2" - categoría Grifería y Sanitarios', 18, 18, 120.23, 180.34, 10, 'pza', TRUE),
(439, 'PROD-0439', '77800000439', 'Ducha telefónica 18V', 'Ducha telefónica 18V - categoría Grifería y Sanitarios', 18, 38, 175.75, 263.62, 10, 'pza', TRUE),
(440, 'PROD-0440', '77800000440', 'Fregadero de acero inoxidable 1 gal', 'Fregadero de acero inoxidable 1 gal - categoría Grifería y Sanitarios', 18, 38, 156.23, 234.34, 25, 'pza', TRUE),
(441, 'PROD-0441', '77800000441', 'Llave de cocina 10cm', 'Llave de cocina 10cm - categoría Grifería y Sanitarios', 18, 18, 87.25, 130.88, 20, 'pza', TRUE),
(442, 'PROD-0442', '77800000442', 'Lavamanos de pedestal Pretul', 'Lavamanos de pedestal Pretul - categoría Grifería y Sanitarios', 18, 38, 70.40, 105.60, 10, 'pza', TRUE),
(443, 'PROD-0443', '77800000443', 'Llave de cocina Total', 'Llave de cocina Total - categoría Grifería y Sanitarios', 18, 18, 108.25, 162.38, 10, 'pza', TRUE),
(444, 'PROD-0444', '77800000444', 'Ducha telefónica #10', 'Ducha telefónica #10 - categoría Grifería y Sanitarios', 18, 38, 79.84, 119.76, 25, 'pza', TRUE),
(445, 'PROD-0445', '77800000445', 'Grifo monomando Black+Decker', 'Grifo monomando Black+Decker - categoría Grifería y Sanitarios', 18, 18, 176.30, 264.45, 25, 'pza', TRUE),
(446, 'PROD-0446', '77800000446', 'Llave de cocina Stanley', 'Llave de cocina Stanley - categoría Grifería y Sanitarios', 18, 18, 108.65, 162.98, 5, 'pza', TRUE),
(447, 'PROD-0447', '77800000447', 'Grifo monomando Pretul', 'Grifo monomando Pretul - categoría Grifería y Sanitarios', 18, 38, 89.04, 133.56, 5, 'pza', TRUE),
(448, 'PROD-0448', '77800000448', 'Grifo monomando 25cm', 'Grifo monomando 25cm - categoría Grifería y Sanitarios', 18, 18, 51.69, 77.53, 5, 'pza', TRUE),
(449, 'PROD-0449', '77800000449', 'Lavamanos de pedestal Stanley', 'Lavamanos de pedestal Stanley - categoría Grifería y Sanitarios', 18, 38, 114.22, 171.33, 15, 'pza', TRUE),
(450, 'PROD-0450', '77800000450', 'Fregadero de acero inoxidable 18V', 'Fregadero de acero inoxidable 18V - categoría Grifería y Sanitarios', 18, 38, 87.19, 130.78, 20, 'pza', TRUE),
(451, 'PROD-0451', '77800000451', 'Cinta métrica de bolsillo 18V', 'Cinta métrica de bolsillo 18V - categoría Ferretería General', 19, 39, 7.76, 12.42, 15, 'pza', TRUE),
(452, 'PROD-0452', '77800000452', 'Cinta métrica de bolsillo Chico', 'Cinta métrica de bolsillo Chico - categoría Ferretería General', 19, 19, 21.58, 34.53, 20, 'pza', TRUE),
(453, 'PROD-0453', '77800000453', 'Cinta métrica de bolsillo 1kg', 'Cinta métrica de bolsillo 1kg - categoría Ferretería General', 19, 39, 34.07, 54.51, 5, 'pza', TRUE),
(454, 'PROD-0454', '77800000454', 'Candado combinación Pretul', 'Candado combinación Pretul - categoría Ferretería General', 19, 19, 16.61, 26.58, 20, 'pza', TRUE),
(455, 'PROD-0455', '77800000455', 'Linterna recargable #8', 'Linterna recargable #8 - categoría Ferretería General', 19, 39, 14.67, 23.47, 15, 'pza', TRUE),
(456, 'PROD-0456', '77800000456', 'Linterna recargable Grande', 'Linterna recargable Grande - categoría Ferretería General', 19, 19, 15.88, 25.41, 15, 'pza', TRUE),
(457, 'PROD-0457', '77800000457', 'Cinta métrica de bolsillo Makita', 'Cinta métrica de bolsillo Makita - categoría Ferretería General', 19, 39, 26.62, 42.59, 10, 'pza', TRUE),
(458, 'PROD-0458', '77800000458', 'Cuerda de nylon #10', 'Cuerda de nylon #10 - categoría Ferretería General', 19, 39, 27.11, 43.38, 15, 'pza', TRUE),
(459, 'PROD-0459', '77800000459', 'Caja de herramientas 10cm', 'Caja de herramientas 10cm - categoría Ferretería General', 19, 39, 20.49, 32.78, 20, 'pza', TRUE),
(460, 'PROD-0460', '77800000460', 'Cinta métrica de bolsillo Truper', 'Cinta métrica de bolsillo Truper - categoría Ferretería General', 19, 19, 3.36, 5.38, 5, 'pza', TRUE),
(461, 'PROD-0461', '77800000461', 'Cuerda de nylon 10cm', 'Cuerda de nylon 10cm - categoría Ferretería General', 19, 19, 20.59, 32.94, 5, 'pza', TRUE),
(462, 'PROD-0462', '77800000462', 'Caja de herramientas Chico', 'Caja de herramientas Chico - categoría Ferretería General', 19, 39, 20.99, 33.58, 20, 'pza', TRUE),
(463, 'PROD-0463', '77800000463', 'Lona plástica 1 gal', 'Lona plástica 1 gal - categoría Ferretería General', 19, 39, 4.13, 6.61, 5, 'pza', TRUE),
(464, 'PROD-0464', '77800000464', 'Candado combinación 12V', 'Candado combinación 12V - categoría Ferretería General', 19, 19, 32.67, 52.27, 25, 'pza', TRUE),
(465, 'PROD-0465', '77800000465', 'Linterna recargable 1kg', 'Linterna recargable 1kg - categoría Ferretería General', 19, 19, 30.16, 48.26, 10, 'pza', TRUE),
(466, 'PROD-0466', '77800000466', 'Cinta métrica de bolsillo Black+Decker', 'Cinta métrica de bolsillo Black+Decker - categoría Ferretería General', 19, 19, 20.58, 32.93, 5, 'pza', TRUE),
(467, 'PROD-0467', '77800000467', 'Lona plástica Truper', 'Lona plástica Truper - categoría Ferretería General', 19, 19, 11.22, 17.95, 10, 'pza', TRUE),
(468, 'PROD-0468', '77800000468', 'Caja de herramientas Total', 'Caja de herramientas Total - categoría Ferretería General', 19, 19, 36.38, 58.21, 5, 'pza', TRUE),
(469, 'PROD-0469', '77800000469', 'Guantes de trabajo 12V', 'Guantes de trabajo 12V - categoría Ferretería General', 19, 19, 33.74, 53.98, 5, 'pza', TRUE),
(470, 'PROD-0470', '77800000470', 'Cuerda de nylon 1/2"', 'Cuerda de nylon 1/2" - categoría Ferretería General', 19, 39, 24.61, 39.38, 25, 'pza', TRUE),
(471, 'PROD-0471', '77800000471', 'Cuerda de nylon Black+Decker', 'Cuerda de nylon Black+Decker - categoría Ferretería General', 19, 19, 18.20, 29.12, 20, 'pza', TRUE),
(472, 'PROD-0472', '77800000472', 'Candado combinación 10cm', 'Candado combinación 10cm - categoría Ferretería General', 19, 19, 14.22, 22.75, 10, 'pza', TRUE),
(473, 'PROD-0473', '77800000473', 'Candado combinación Chico', 'Candado combinación Chico - categoría Ferretería General', 19, 19, 14.75, 23.60, 5, 'pza', TRUE),
(474, 'PROD-0474', '77800000474', 'Candado combinación Makita', 'Candado combinación Makita - categoría Ferretería General', 19, 39, 8.39, 13.42, 15, 'pza', TRUE),
(475, 'PROD-0475', '77800000475', 'Guantes de trabajo 1 gal', 'Guantes de trabajo 1 gal - categoría Ferretería General', 19, 39, 11.26, 18.02, 25, 'pza', TRUE),
(476, 'PROD-0476', '77800000476', 'Casco de seguridad Bosch', 'Casco de seguridad Bosch - categoría Equipo de Protección Personal (EPP)', 20, 20, 22.87, 35.45, 15, 'pza', TRUE),
(477, 'PROD-0477', '77800000477', 'Botas punta de acero Pretul', 'Botas punta de acero Pretul - categoría Equipo de Protección Personal (EPP)', 20, 20, 55.78, 86.46, 5, 'pza', TRUE),
(478, 'PROD-0478', '77800000478', 'Orejeras antirruido 10cm', 'Orejeras antirruido 10cm - categoría Equipo de Protección Personal (EPP)', 20, 20, 37.49, 58.11, 15, 'pza', TRUE),
(479, 'PROD-0479', '77800000479', 'Mascarilla N95 25cm', 'Mascarilla N95 25cm - categoría Equipo de Protección Personal (EPP)', 20, 40, 27.49, 42.61, 25, 'pza', TRUE),
(480, 'PROD-0480', '77800000480', 'Casco de seguridad 5kg', 'Casco de seguridad 5kg - categoría Equipo de Protección Personal (EPP)', 20, 40, 14.74, 22.85, 25, 'pza', TRUE),
(481, 'PROD-0481', '77800000481', 'Casco de seguridad Ingco', 'Casco de seguridad Ingco - categoría Equipo de Protección Personal (EPP)', 20, 20, 12.27, 19.02, 15, 'pza', TRUE),
(482, 'PROD-0482', '77800000482', 'Casco de seguridad Surtek', 'Casco de seguridad Surtek - categoría Equipo de Protección Personal (EPP)', 20, 20, 46.49, 72.06, 25, 'pza', TRUE),
(483, 'PROD-0483', '77800000483', 'Orejeras antirruido Makita', 'Orejeras antirruido Makita - categoría Equipo de Protección Personal (EPP)', 20, 20, 24.47, 37.93, 15, 'pza', TRUE),
(484, 'PROD-0484', '77800000484', 'Chaleco reflectivo Bosch', 'Chaleco reflectivo Bosch - categoría Equipo de Protección Personal (EPP)', 20, 40, 6.65, 10.31, 15, 'pza', TRUE),
(485, 'PROD-0485', '77800000485', 'Orejeras antirruido 5 gal', 'Orejeras antirruido 5 gal - categoría Equipo de Protección Personal (EPP)', 20, 40, 36.60, 56.73, 5, 'pza', TRUE),
(486, 'PROD-0486', '77800000486', 'Mascarilla N95 Genérico', 'Mascarilla N95 Genérico - categoría Equipo de Protección Personal (EPP)', 20, 40, 33.28, 51.58, 15, 'pza', TRUE),
(487, 'PROD-0487', '77800000487', 'Mascarilla N95 15cm', 'Mascarilla N95 15cm - categoría Equipo de Protección Personal (EPP)', 20, 40, 8.85, 13.72, 20, 'pza', TRUE),
(488, 'PROD-0488', '77800000488', 'Botas punta de acero 5 gal', 'Botas punta de acero 5 gal - categoría Equipo de Protección Personal (EPP)', 20, 20, 10.33, 16.01, 15, 'pza', TRUE),
(489, 'PROD-0489', '77800000489', 'Chaleco reflectivo Mediano', 'Chaleco reflectivo Mediano - categoría Equipo de Protección Personal (EPP)', 20, 40, 13.08, 20.27, 10, 'pza', TRUE),
(490, 'PROD-0490', '77800000490', 'Chaleco reflectivo Pretul', 'Chaleco reflectivo Pretul - categoría Equipo de Protección Personal (EPP)', 20, 40, 30.91, 47.91, 25, 'pza', TRUE),
(491, 'PROD-0491', '77800000491', 'Mascarilla N95 18V', 'Mascarilla N95 18V - categoría Equipo de Protección Personal (EPP)', 20, 40, 26.23, 40.66, 20, 'pza', TRUE),
(492, 'PROD-0492', '77800000492', 'Lentes de seguridad 5kg', 'Lentes de seguridad 5kg - categoría Equipo de Protección Personal (EPP)', 20, 40, 15.67, 24.29, 5, 'pza', TRUE),
(493, 'PROD-0493', '77800000493', 'Chaleco reflectivo Grande', 'Chaleco reflectivo Grande - categoría Equipo de Protección Personal (EPP)', 20, 40, 30.58, 47.40, 15, 'pza', TRUE),
(494, 'PROD-0494', '77800000494', 'Mascarilla N95 5 gal', 'Mascarilla N95 5 gal - categoría Equipo de Protección Personal (EPP)', 20, 40, 33.69, 52.22, 25, 'pza', TRUE),
(495, 'PROD-0495', '77800000495', 'Lentes de seguridad Pretul', 'Lentes de seguridad Pretul - categoría Equipo de Protección Personal (EPP)', 20, 20, 55.88, 86.61, 10, 'pza', TRUE),
(496, 'PROD-0496', '77800000496', 'Chaleco reflectivo 18V', 'Chaleco reflectivo 18V - categoría Equipo de Protección Personal (EPP)', 20, 40, 54.57, 84.58, 5, 'pza', TRUE),
(497, 'PROD-0497', '77800000497', 'Botas punta de acero Mediano', 'Botas punta de acero Mediano - categoría Equipo de Protección Personal (EPP)', 20, 40, 33.55, 52.00, 10, 'pza', TRUE),
(498, 'PROD-0498', '77800000498', 'Lentes de seguridad Chico', 'Lentes de seguridad Chico - categoría Equipo de Protección Personal (EPP)', 20, 40, 15.21, 23.58, 10, 'pza', TRUE),
(499, 'PROD-0499', '77800000499', 'Chaleco reflectivo 1 gal', 'Chaleco reflectivo 1 gal - categoría Equipo de Protección Personal (EPP)', 20, 20, 18.94, 29.36, 5, 'pza', TRUE),
(500, 'PROD-0500', '77800000500', 'Botas punta de acero Black+Decker', 'Botas punta de acero Black+Decker - categoría Equipo de Protección Personal (EPP)', 20, 20, 32.54, 50.44, 15, 'pza', TRUE);

-- -------------------- INVENTARIO --------------------
-- El trigger trg_productos_after_insert ya creó la fila de inventario
-- (stock 0) por cada producto. Aquí se actualiza con stock realista.
UPDATE inventario SET stock_actual = 26, stock_reservado = 0 WHERE producto_id = 1;
UPDATE inventario SET stock_actual = 143, stock_reservado = 2 WHERE producto_id = 2;
UPDATE inventario SET stock_actual = 33, stock_reservado = 1 WHERE producto_id = 3;
UPDATE inventario SET stock_actual = 55, stock_reservado = 0 WHERE producto_id = 4;
UPDATE inventario SET stock_actual = 64, stock_reservado = 1 WHERE producto_id = 5;
UPDATE inventario SET stock_actual = 85, stock_reservado = 0 WHERE producto_id = 6;
UPDATE inventario SET stock_actual = 106, stock_reservado = 0 WHERE producto_id = 7;
UPDATE inventario SET stock_actual = 90, stock_reservado = 0 WHERE producto_id = 8;
UPDATE inventario SET stock_actual = 120, stock_reservado = 2 WHERE producto_id = 9;
UPDATE inventario SET stock_actual = 76, stock_reservado = 1 WHERE producto_id = 10;
UPDATE inventario SET stock_actual = 114, stock_reservado = 0 WHERE producto_id = 11;
UPDATE inventario SET stock_actual = 109, stock_reservado = 2 WHERE producto_id = 12;
UPDATE inventario SET stock_actual = 53, stock_reservado = 0 WHERE producto_id = 13;
UPDATE inventario SET stock_actual = 11, stock_reservado = 0 WHERE producto_id = 14;
UPDATE inventario SET stock_actual = 133, stock_reservado = 0 WHERE producto_id = 15;
UPDATE inventario SET stock_actual = 49, stock_reservado = 1 WHERE producto_id = 16;
UPDATE inventario SET stock_actual = 51, stock_reservado = 0 WHERE producto_id = 17;
UPDATE inventario SET stock_actual = 21, stock_reservado = 2 WHERE producto_id = 18;
UPDATE inventario SET stock_actual = 17, stock_reservado = 1 WHERE producto_id = 19;
UPDATE inventario SET stock_actual = 96, stock_reservado = 2 WHERE producto_id = 20;
UPDATE inventario SET stock_actual = 98, stock_reservado = 2 WHERE producto_id = 21;
UPDATE inventario SET stock_actual = 82, stock_reservado = 0 WHERE producto_id = 22;
UPDATE inventario SET stock_actual = 53, stock_reservado = 0 WHERE producto_id = 23;
UPDATE inventario SET stock_actual = 87, stock_reservado = 0 WHERE producto_id = 24;
UPDATE inventario SET stock_actual = 45, stock_reservado = 0 WHERE producto_id = 25;
UPDATE inventario SET stock_actual = 20, stock_reservado = 0 WHERE producto_id = 26;
UPDATE inventario SET stock_actual = 10, stock_reservado = 0 WHERE producto_id = 27;
UPDATE inventario SET stock_actual = 66, stock_reservado = 2 WHERE producto_id = 28;
UPDATE inventario SET stock_actual = 47, stock_reservado = 0 WHERE producto_id = 29;
UPDATE inventario SET stock_actual = 85, stock_reservado = 0 WHERE producto_id = 30;
UPDATE inventario SET stock_actual = 72, stock_reservado = 0 WHERE producto_id = 31;
UPDATE inventario SET stock_actual = 121, stock_reservado = 1 WHERE producto_id = 32;
UPDATE inventario SET stock_actual = 11, stock_reservado = 0 WHERE producto_id = 33;
UPDATE inventario SET stock_actual = 71, stock_reservado = 1 WHERE producto_id = 34;
UPDATE inventario SET stock_actual = 127, stock_reservado = 0 WHERE producto_id = 35;
UPDATE inventario SET stock_actual = 26, stock_reservado = 2 WHERE producto_id = 36;
UPDATE inventario SET stock_actual = 19, stock_reservado = 0 WHERE producto_id = 37;
UPDATE inventario SET stock_actual = 111, stock_reservado = 0 WHERE producto_id = 38;
UPDATE inventario SET stock_actual = 81, stock_reservado = 1 WHERE producto_id = 39;
UPDATE inventario SET stock_actual = 34, stock_reservado = 0 WHERE producto_id = 40;
UPDATE inventario SET stock_actual = 14, stock_reservado = 0 WHERE producto_id = 41;
UPDATE inventario SET stock_actual = 59, stock_reservado = 0 WHERE producto_id = 42;
UPDATE inventario SET stock_actual = 99, stock_reservado = 1 WHERE producto_id = 43;
UPDATE inventario SET stock_actual = 64, stock_reservado = 0 WHERE producto_id = 44;
UPDATE inventario SET stock_actual = 78, stock_reservado = 0 WHERE producto_id = 45;
UPDATE inventario SET stock_actual = 46, stock_reservado = 1 WHERE producto_id = 46;
UPDATE inventario SET stock_actual = 25, stock_reservado = 0 WHERE producto_id = 47;
UPDATE inventario SET stock_actual = 69, stock_reservado = 0 WHERE producto_id = 48;
UPDATE inventario SET stock_actual = 42, stock_reservado = 2 WHERE producto_id = 49;
UPDATE inventario SET stock_actual = 32, stock_reservado = 2 WHERE producto_id = 50;
UPDATE inventario SET stock_actual = 15, stock_reservado = 1 WHERE producto_id = 51;
UPDATE inventario SET stock_actual = 34, stock_reservado = 1 WHERE producto_id = 52;
UPDATE inventario SET stock_actual = 7, stock_reservado = 1 WHERE producto_id = 53;
UPDATE inventario SET stock_actual = 31, stock_reservado = 2 WHERE producto_id = 54;
UPDATE inventario SET stock_actual = 96, stock_reservado = 0 WHERE producto_id = 55;
UPDATE inventario SET stock_actual = 69, stock_reservado = 0 WHERE producto_id = 56;
UPDATE inventario SET stock_actual = 28, stock_reservado = 2 WHERE producto_id = 57;
UPDATE inventario SET stock_actual = 48, stock_reservado = 0 WHERE producto_id = 58;
UPDATE inventario SET stock_actual = 29, stock_reservado = 0 WHERE producto_id = 59;
UPDATE inventario SET stock_actual = 91, stock_reservado = 2 WHERE producto_id = 60;
UPDATE inventario SET stock_actual = 28, stock_reservado = 2 WHERE producto_id = 61;
UPDATE inventario SET stock_actual = 18, stock_reservado = 0 WHERE producto_id = 62;
UPDATE inventario SET stock_actual = 58, stock_reservado = 0 WHERE producto_id = 63;
UPDATE inventario SET stock_actual = 46, stock_reservado = 1 WHERE producto_id = 64;
UPDATE inventario SET stock_actual = 7, stock_reservado = 2 WHERE producto_id = 65;
UPDATE inventario SET stock_actual = 63, stock_reservado = 0 WHERE producto_id = 66;
UPDATE inventario SET stock_actual = 87, stock_reservado = 2 WHERE producto_id = 67;
UPDATE inventario SET stock_actual = 20, stock_reservado = 2 WHERE producto_id = 68;
UPDATE inventario SET stock_actual = 71, stock_reservado = 1 WHERE producto_id = 69;
UPDATE inventario SET stock_actual = 54, stock_reservado = 0 WHERE producto_id = 70;
UPDATE inventario SET stock_actual = 34, stock_reservado = 0 WHERE producto_id = 71;
UPDATE inventario SET stock_actual = 87, stock_reservado = 0 WHERE producto_id = 72;
UPDATE inventario SET stock_actual = 32, stock_reservado = 0 WHERE producto_id = 73;
UPDATE inventario SET stock_actual = 59, stock_reservado = 0 WHERE producto_id = 74;
UPDATE inventario SET stock_actual = 5, stock_reservado = 1 WHERE producto_id = 75;
UPDATE inventario SET stock_actual = 83, stock_reservado = 0 WHERE producto_id = 76;
UPDATE inventario SET stock_actual = 96, stock_reservado = 2 WHERE producto_id = 77;
UPDATE inventario SET stock_actual = 55, stock_reservado = 0 WHERE producto_id = 78;
UPDATE inventario SET stock_actual = 70, stock_reservado = 0 WHERE producto_id = 79;
UPDATE inventario SET stock_actual = 21, stock_reservado = 0 WHERE producto_id = 80;
UPDATE inventario SET stock_actual = 89, stock_reservado = 2 WHERE producto_id = 81;
UPDATE inventario SET stock_actual = 25, stock_reservado = 1 WHERE producto_id = 82;
UPDATE inventario SET stock_actual = 16, stock_reservado = 0 WHERE producto_id = 83;
UPDATE inventario SET stock_actual = 29, stock_reservado = 2 WHERE producto_id = 84;
UPDATE inventario SET stock_actual = 12, stock_reservado = 0 WHERE producto_id = 85;
UPDATE inventario SET stock_actual = 59, stock_reservado = 1 WHERE producto_id = 86;
UPDATE inventario SET stock_actual = 18, stock_reservado = 0 WHERE producto_id = 87;
UPDATE inventario SET stock_actual = 24, stock_reservado = 0 WHERE producto_id = 88;
UPDATE inventario SET stock_actual = 12, stock_reservado = 0 WHERE producto_id = 89;
UPDATE inventario SET stock_actual = 85, stock_reservado = 0 WHERE producto_id = 90;
UPDATE inventario SET stock_actual = 28, stock_reservado = 0 WHERE producto_id = 91;
UPDATE inventario SET stock_actual = 49, stock_reservado = 0 WHERE producto_id = 92;
UPDATE inventario SET stock_actual = 115, stock_reservado = 1 WHERE producto_id = 93;
UPDATE inventario SET stock_actual = 41, stock_reservado = 1 WHERE producto_id = 94;
UPDATE inventario SET stock_actual = 59, stock_reservado = 0 WHERE producto_id = 95;
UPDATE inventario SET stock_actual = 97, stock_reservado = 0 WHERE producto_id = 96;
UPDATE inventario SET stock_actual = 7, stock_reservado = 0 WHERE producto_id = 97;
UPDATE inventario SET stock_actual = 97, stock_reservado = 1 WHERE producto_id = 98;
UPDATE inventario SET stock_actual = 72, stock_reservado = 0 WHERE producto_id = 99;
UPDATE inventario SET stock_actual = 70, stock_reservado = 0 WHERE producto_id = 100;
UPDATE inventario SET stock_actual = 41, stock_reservado = 2 WHERE producto_id = 101;
UPDATE inventario SET stock_actual = 38, stock_reservado = 0 WHERE producto_id = 102;
UPDATE inventario SET stock_actual = 11, stock_reservado = 0 WHERE producto_id = 103;
UPDATE inventario SET stock_actual = 36, stock_reservado = 0 WHERE producto_id = 104;
UPDATE inventario SET stock_actual = 142, stock_reservado = 1 WHERE producto_id = 105;
UPDATE inventario SET stock_actual = 43, stock_reservado = 0 WHERE producto_id = 106;
UPDATE inventario SET stock_actual = 87, stock_reservado = 1 WHERE producto_id = 107;
UPDATE inventario SET stock_actual = 88, stock_reservado = 0 WHERE producto_id = 108;
UPDATE inventario SET stock_actual = 76, stock_reservado = 0 WHERE producto_id = 109;
UPDATE inventario SET stock_actual = 43, stock_reservado = 0 WHERE producto_id = 110;
UPDATE inventario SET stock_actual = 60, stock_reservado = 1 WHERE producto_id = 111;
UPDATE inventario SET stock_actual = 51, stock_reservado = 0 WHERE producto_id = 112;
UPDATE inventario SET stock_actual = 70, stock_reservado = 0 WHERE producto_id = 113;
UPDATE inventario SET stock_actual = 25, stock_reservado = 0 WHERE producto_id = 114;
UPDATE inventario SET stock_actual = 47, stock_reservado = 0 WHERE producto_id = 115;
UPDATE inventario SET stock_actual = 114, stock_reservado = 0 WHERE producto_id = 116;
UPDATE inventario SET stock_actual = 55, stock_reservado = 0 WHERE producto_id = 117;
UPDATE inventario SET stock_actual = 53, stock_reservado = 0 WHERE producto_id = 118;
UPDATE inventario SET stock_actual = 75, stock_reservado = 0 WHERE producto_id = 119;
UPDATE inventario SET stock_actual = 22, stock_reservado = 0 WHERE producto_id = 120;
UPDATE inventario SET stock_actual = 81, stock_reservado = 0 WHERE producto_id = 121;
UPDATE inventario SET stock_actual = 29, stock_reservado = 0 WHERE producto_id = 122;
UPDATE inventario SET stock_actual = 119, stock_reservado = 0 WHERE producto_id = 123;
UPDATE inventario SET stock_actual = 12, stock_reservado = 1 WHERE producto_id = 124;
UPDATE inventario SET stock_actual = 19, stock_reservado = 0 WHERE producto_id = 125;
UPDATE inventario SET stock_actual = 55, stock_reservado = 1 WHERE producto_id = 126;
UPDATE inventario SET stock_actual = 57, stock_reservado = 0 WHERE producto_id = 127;
UPDATE inventario SET stock_actual = 99, stock_reservado = 1 WHERE producto_id = 128;
UPDATE inventario SET stock_actual = 44, stock_reservado = 1 WHERE producto_id = 129;
UPDATE inventario SET stock_actual = 90, stock_reservado = 0 WHERE producto_id = 130;
UPDATE inventario SET stock_actual = 112, stock_reservado = 1 WHERE producto_id = 131;
UPDATE inventario SET stock_actual = 36, stock_reservado = 0 WHERE producto_id = 132;
UPDATE inventario SET stock_actual = 44, stock_reservado = 0 WHERE producto_id = 133;
UPDATE inventario SET stock_actual = 26, stock_reservado = 0 WHERE producto_id = 134;
UPDATE inventario SET stock_actual = 14, stock_reservado = 0 WHERE producto_id = 135;
UPDATE inventario SET stock_actual = 67, stock_reservado = 0 WHERE producto_id = 136;
UPDATE inventario SET stock_actual = 141, stock_reservado = 1 WHERE producto_id = 137;
UPDATE inventario SET stock_actual = 22, stock_reservado = 2 WHERE producto_id = 138;
UPDATE inventario SET stock_actual = 117, stock_reservado = 0 WHERE producto_id = 139;
UPDATE inventario SET stock_actual = 21, stock_reservado = 1 WHERE producto_id = 140;
UPDATE inventario SET stock_actual = 21, stock_reservado = 0 WHERE producto_id = 141;
UPDATE inventario SET stock_actual = 57, stock_reservado = 0 WHERE producto_id = 142;
UPDATE inventario SET stock_actual = 16, stock_reservado = 1 WHERE producto_id = 143;
UPDATE inventario SET stock_actual = 29, stock_reservado = 0 WHERE producto_id = 144;
UPDATE inventario SET stock_actual = 7, stock_reservado = 1 WHERE producto_id = 145;
UPDATE inventario SET stock_actual = 27, stock_reservado = 0 WHERE producto_id = 146;
UPDATE inventario SET stock_actual = 75, stock_reservado = 0 WHERE producto_id = 147;
UPDATE inventario SET stock_actual = 21, stock_reservado = 0 WHERE producto_id = 148;
UPDATE inventario SET stock_actual = 85, stock_reservado = 0 WHERE producto_id = 149;
UPDATE inventario SET stock_actual = 84, stock_reservado = 2 WHERE producto_id = 150;
UPDATE inventario SET stock_actual = 32, stock_reservado = 0 WHERE producto_id = 151;
UPDATE inventario SET stock_actual = 43, stock_reservado = 1 WHERE producto_id = 152;
UPDATE inventario SET stock_actual = 112, stock_reservado = 1 WHERE producto_id = 153;
UPDATE inventario SET stock_actual = 30, stock_reservado = 0 WHERE producto_id = 154;
UPDATE inventario SET stock_actual = 26, stock_reservado = 0 WHERE producto_id = 155;
UPDATE inventario SET stock_actual = 45, stock_reservado = 0 WHERE producto_id = 156;
UPDATE inventario SET stock_actual = 49, stock_reservado = 1 WHERE producto_id = 157;
UPDATE inventario SET stock_actual = 70, stock_reservado = 0 WHERE producto_id = 158;
UPDATE inventario SET stock_actual = 38, stock_reservado = 2 WHERE producto_id = 159;
UPDATE inventario SET stock_actual = 23, stock_reservado = 0 WHERE producto_id = 160;
UPDATE inventario SET stock_actual = 23, stock_reservado = 0 WHERE producto_id = 161;
UPDATE inventario SET stock_actual = 40, stock_reservado = 1 WHERE producto_id = 162;
UPDATE inventario SET stock_actual = 87, stock_reservado = 0 WHERE producto_id = 163;
UPDATE inventario SET stock_actual = 95, stock_reservado = 0 WHERE producto_id = 164;
UPDATE inventario SET stock_actual = 99, stock_reservado = 0 WHERE producto_id = 165;
UPDATE inventario SET stock_actual = 90, stock_reservado = 0 WHERE producto_id = 166;
UPDATE inventario SET stock_actual = 12, stock_reservado = 0 WHERE producto_id = 167;
UPDATE inventario SET stock_actual = 42, stock_reservado = 1 WHERE producto_id = 168;
UPDATE inventario SET stock_actual = 74, stock_reservado = 1 WHERE producto_id = 169;
UPDATE inventario SET stock_actual = 113, stock_reservado = 0 WHERE producto_id = 170;
UPDATE inventario SET stock_actual = 32, stock_reservado = 0 WHERE producto_id = 171;
UPDATE inventario SET stock_actual = 31, stock_reservado = 0 WHERE producto_id = 172;
UPDATE inventario SET stock_actual = 34, stock_reservado = 2 WHERE producto_id = 173;
UPDATE inventario SET stock_actual = 107, stock_reservado = 1 WHERE producto_id = 174;
UPDATE inventario SET stock_actual = 82, stock_reservado = 0 WHERE producto_id = 175;
UPDATE inventario SET stock_actual = 27, stock_reservado = 0 WHERE producto_id = 176;
UPDATE inventario SET stock_actual = 117, stock_reservado = 2 WHERE producto_id = 177;
UPDATE inventario SET stock_actual = 26, stock_reservado = 0 WHERE producto_id = 178;
UPDATE inventario SET stock_actual = 49, stock_reservado = 1 WHERE producto_id = 179;
UPDATE inventario SET stock_actual = 41, stock_reservado = 1 WHERE producto_id = 180;
UPDATE inventario SET stock_actual = 34, stock_reservado = 2 WHERE producto_id = 181;
UPDATE inventario SET stock_actual = 31, stock_reservado = 1 WHERE producto_id = 182;
UPDATE inventario SET stock_actual = 112, stock_reservado = 0 WHERE producto_id = 183;
UPDATE inventario SET stock_actual = 5, stock_reservado = 2 WHERE producto_id = 184;
UPDATE inventario SET stock_actual = 12, stock_reservado = 0 WHERE producto_id = 185;
UPDATE inventario SET stock_actual = 123, stock_reservado = 1 WHERE producto_id = 186;
UPDATE inventario SET stock_actual = 25, stock_reservado = 2 WHERE producto_id = 187;
UPDATE inventario SET stock_actual = 25, stock_reservado = 0 WHERE producto_id = 188;
UPDATE inventario SET stock_actual = 19, stock_reservado = 2 WHERE producto_id = 189;
UPDATE inventario SET stock_actual = 14, stock_reservado = 1 WHERE producto_id = 190;
UPDATE inventario SET stock_actual = 13, stock_reservado = 0 WHERE producto_id = 191;
UPDATE inventario SET stock_actual = 19, stock_reservado = 2 WHERE producto_id = 192;
UPDATE inventario SET stock_actual = 17, stock_reservado = 0 WHERE producto_id = 193;
UPDATE inventario SET stock_actual = 10, stock_reservado = 1 WHERE producto_id = 194;
UPDATE inventario SET stock_actual = 36, stock_reservado = 0 WHERE producto_id = 195;
UPDATE inventario SET stock_actual = 30, stock_reservado = 0 WHERE producto_id = 196;
UPDATE inventario SET stock_actual = 82, stock_reservado = 0 WHERE producto_id = 197;
UPDATE inventario SET stock_actual = 105, stock_reservado = 0 WHERE producto_id = 198;
UPDATE inventario SET stock_actual = 22, stock_reservado = 1 WHERE producto_id = 199;
UPDATE inventario SET stock_actual = 26, stock_reservado = 0 WHERE producto_id = 200;
UPDATE inventario SET stock_actual = 11, stock_reservado = 0 WHERE producto_id = 201;
UPDATE inventario SET stock_actual = 87, stock_reservado = 0 WHERE producto_id = 202;
UPDATE inventario SET stock_actual = 16, stock_reservado = 0 WHERE producto_id = 203;
UPDATE inventario SET stock_actual = 129, stock_reservado = 0 WHERE producto_id = 204;
UPDATE inventario SET stock_actual = 65, stock_reservado = 0 WHERE producto_id = 205;
UPDATE inventario SET stock_actual = 60, stock_reservado = 2 WHERE producto_id = 206;
UPDATE inventario SET stock_actual = 87, stock_reservado = 2 WHERE producto_id = 207;
UPDATE inventario SET stock_actual = 41, stock_reservado = 2 WHERE producto_id = 208;
UPDATE inventario SET stock_actual = 7, stock_reservado = 0 WHERE producto_id = 209;
UPDATE inventario SET stock_actual = 21, stock_reservado = 1 WHERE producto_id = 210;
UPDATE inventario SET stock_actual = 50, stock_reservado = 0 WHERE producto_id = 211;
UPDATE inventario SET stock_actual = 22, stock_reservado = 0 WHERE producto_id = 212;
UPDATE inventario SET stock_actual = 59, stock_reservado = 0 WHERE producto_id = 213;
UPDATE inventario SET stock_actual = 16, stock_reservado = 1 WHERE producto_id = 214;
UPDATE inventario SET stock_actual = 56, stock_reservado = 0 WHERE producto_id = 215;
UPDATE inventario SET stock_actual = 101, stock_reservado = 1 WHERE producto_id = 216;
UPDATE inventario SET stock_actual = 113, stock_reservado = 2 WHERE producto_id = 217;
UPDATE inventario SET stock_actual = 129, stock_reservado = 0 WHERE producto_id = 218;
UPDATE inventario SET stock_actual = 31, stock_reservado = 0 WHERE producto_id = 219;
UPDATE inventario SET stock_actual = 75, stock_reservado = 2 WHERE producto_id = 220;
UPDATE inventario SET stock_actual = 43, stock_reservado = 2 WHERE producto_id = 221;
UPDATE inventario SET stock_actual = 31, stock_reservado = 0 WHERE producto_id = 222;
UPDATE inventario SET stock_actual = 36, stock_reservado = 0 WHERE producto_id = 223;
UPDATE inventario SET stock_actual = 72, stock_reservado = 0 WHERE producto_id = 224;
UPDATE inventario SET stock_actual = 16, stock_reservado = 0 WHERE producto_id = 225;
UPDATE inventario SET stock_actual = 76, stock_reservado = 0 WHERE producto_id = 226;
UPDATE inventario SET stock_actual = 10, stock_reservado = 0 WHERE producto_id = 227;
UPDATE inventario SET stock_actual = 12, stock_reservado = 1 WHERE producto_id = 228;
UPDATE inventario SET stock_actual = 27, stock_reservado = 0 WHERE producto_id = 229;
UPDATE inventario SET stock_actual = 26, stock_reservado = 0 WHERE producto_id = 230;
UPDATE inventario SET stock_actual = 82, stock_reservado = 0 WHERE producto_id = 231;
UPDATE inventario SET stock_actual = 59, stock_reservado = 0 WHERE producto_id = 232;
UPDATE inventario SET stock_actual = 14, stock_reservado = 0 WHERE producto_id = 233;
UPDATE inventario SET stock_actual = 124, stock_reservado = 0 WHERE producto_id = 234;
UPDATE inventario SET stock_actual = 27, stock_reservado = 1 WHERE producto_id = 235;
UPDATE inventario SET stock_actual = 20, stock_reservado = 0 WHERE producto_id = 236;
UPDATE inventario SET stock_actual = 12, stock_reservado = 0 WHERE producto_id = 237;
UPDATE inventario SET stock_actual = 50, stock_reservado = 1 WHERE producto_id = 238;
UPDATE inventario SET stock_actual = 52, stock_reservado = 0 WHERE producto_id = 239;
UPDATE inventario SET stock_actual = 130, stock_reservado = 2 WHERE producto_id = 240;
UPDATE inventario SET stock_actual = 21, stock_reservado = 0 WHERE producto_id = 241;
UPDATE inventario SET stock_actual = 61, stock_reservado = 0 WHERE producto_id = 242;
UPDATE inventario SET stock_actual = 15, stock_reservado = 0 WHERE producto_id = 243;
UPDATE inventario SET stock_actual = 48, stock_reservado = 0 WHERE producto_id = 244;
UPDATE inventario SET stock_actual = 13, stock_reservado = 2 WHERE producto_id = 245;
UPDATE inventario SET stock_actual = 56, stock_reservado = 0 WHERE producto_id = 246;
UPDATE inventario SET stock_actual = 48, stock_reservado = 1 WHERE producto_id = 247;
UPDATE inventario SET stock_actual = 23, stock_reservado = 2 WHERE producto_id = 248;
UPDATE inventario SET stock_actual = 53, stock_reservado = 2 WHERE producto_id = 249;
UPDATE inventario SET stock_actual = 79, stock_reservado = 0 WHERE producto_id = 250;
UPDATE inventario SET stock_actual = 69, stock_reservado = 0 WHERE producto_id = 251;
UPDATE inventario SET stock_actual = 5, stock_reservado = 0 WHERE producto_id = 252;
UPDATE inventario SET stock_actual = 99, stock_reservado = 0 WHERE producto_id = 253;
UPDATE inventario SET stock_actual = 38, stock_reservado = 0 WHERE producto_id = 254;
UPDATE inventario SET stock_actual = 54, stock_reservado = 1 WHERE producto_id = 255;
UPDATE inventario SET stock_actual = 17, stock_reservado = 0 WHERE producto_id = 256;
UPDATE inventario SET stock_actual = 16, stock_reservado = 1 WHERE producto_id = 257;
UPDATE inventario SET stock_actual = 43, stock_reservado = 2 WHERE producto_id = 258;
UPDATE inventario SET stock_actual = 28, stock_reservado = 0 WHERE producto_id = 259;
UPDATE inventario SET stock_actual = 18, stock_reservado = 1 WHERE producto_id = 260;
UPDATE inventario SET stock_actual = 16, stock_reservado = 2 WHERE producto_id = 261;
UPDATE inventario SET stock_actual = 81, stock_reservado = 2 WHERE producto_id = 262;
UPDATE inventario SET stock_actual = 40, stock_reservado = 0 WHERE producto_id = 263;
UPDATE inventario SET stock_actual = 139, stock_reservado = 2 WHERE producto_id = 264;
UPDATE inventario SET stock_actual = 42, stock_reservado = 1 WHERE producto_id = 265;
UPDATE inventario SET stock_actual = 29, stock_reservado = 1 WHERE producto_id = 266;
UPDATE inventario SET stock_actual = 21, stock_reservado = 2 WHERE producto_id = 267;
UPDATE inventario SET stock_actual = 71, stock_reservado = 0 WHERE producto_id = 268;
UPDATE inventario SET stock_actual = 82, stock_reservado = 0 WHERE producto_id = 269;
UPDATE inventario SET stock_actual = 20, stock_reservado = 0 WHERE producto_id = 270;
UPDATE inventario SET stock_actual = 22, stock_reservado = 2 WHERE producto_id = 271;
UPDATE inventario SET stock_actual = 148, stock_reservado = 2 WHERE producto_id = 272;
UPDATE inventario SET stock_actual = 51, stock_reservado = 1 WHERE producto_id = 273;
UPDATE inventario SET stock_actual = 12, stock_reservado = 1 WHERE producto_id = 274;
UPDATE inventario SET stock_actual = 101, stock_reservado = 1 WHERE producto_id = 275;
UPDATE inventario SET stock_actual = 61, stock_reservado = 0 WHERE producto_id = 276;
UPDATE inventario SET stock_actual = 92, stock_reservado = 1 WHERE producto_id = 277;
UPDATE inventario SET stock_actual = 72, stock_reservado = 0 WHERE producto_id = 278;
UPDATE inventario SET stock_actual = 63, stock_reservado = 0 WHERE producto_id = 279;
UPDATE inventario SET stock_actual = 18, stock_reservado = 2 WHERE producto_id = 280;
UPDATE inventario SET stock_actual = 82, stock_reservado = 1 WHERE producto_id = 281;
UPDATE inventario SET stock_actual = 30, stock_reservado = 2 WHERE producto_id = 282;
UPDATE inventario SET stock_actual = 59, stock_reservado = 2 WHERE producto_id = 283;
UPDATE inventario SET stock_actual = 52, stock_reservado = 0 WHERE producto_id = 284;
UPDATE inventario SET stock_actual = 44, stock_reservado = 1 WHERE producto_id = 285;
UPDATE inventario SET stock_actual = 11, stock_reservado = 1 WHERE producto_id = 286;
UPDATE inventario SET stock_actual = 6, stock_reservado = 0 WHERE producto_id = 287;
UPDATE inventario SET stock_actual = 130, stock_reservado = 0 WHERE producto_id = 288;
UPDATE inventario SET stock_actual = 100, stock_reservado = 1 WHERE producto_id = 289;
UPDATE inventario SET stock_actual = 106, stock_reservado = 0 WHERE producto_id = 290;
UPDATE inventario SET stock_actual = 33, stock_reservado = 2 WHERE producto_id = 291;
UPDATE inventario SET stock_actual = 16, stock_reservado = 2 WHERE producto_id = 292;
UPDATE inventario SET stock_actual = 26, stock_reservado = 0 WHERE producto_id = 293;
UPDATE inventario SET stock_actual = 20, stock_reservado = 0 WHERE producto_id = 294;
UPDATE inventario SET stock_actual = 89, stock_reservado = 0 WHERE producto_id = 295;
UPDATE inventario SET stock_actual = 16, stock_reservado = 2 WHERE producto_id = 296;
UPDATE inventario SET stock_actual = 23, stock_reservado = 2 WHERE producto_id = 297;
UPDATE inventario SET stock_actual = 14, stock_reservado = 0 WHERE producto_id = 298;
UPDATE inventario SET stock_actual = 43, stock_reservado = 0 WHERE producto_id = 299;
UPDATE inventario SET stock_actual = 39, stock_reservado = 0 WHERE producto_id = 300;
UPDATE inventario SET stock_actual = 51, stock_reservado = 1 WHERE producto_id = 301;
UPDATE inventario SET stock_actual = 46, stock_reservado = 2 WHERE producto_id = 302;
UPDATE inventario SET stock_actual = 30, stock_reservado = 2 WHERE producto_id = 303;
UPDATE inventario SET stock_actual = 38, stock_reservado = 2 WHERE producto_id = 304;
UPDATE inventario SET stock_actual = 106, stock_reservado = 0 WHERE producto_id = 305;
UPDATE inventario SET stock_actual = 38, stock_reservado = 2 WHERE producto_id = 306;
UPDATE inventario SET stock_actual = 24, stock_reservado = 1 WHERE producto_id = 307;
UPDATE inventario SET stock_actual = 49, stock_reservado = 2 WHERE producto_id = 308;
UPDATE inventario SET stock_actual = 73, stock_reservado = 2 WHERE producto_id = 309;
UPDATE inventario SET stock_actual = 36, stock_reservado = 0 WHERE producto_id = 310;
UPDATE inventario SET stock_actual = 112, stock_reservado = 1 WHERE producto_id = 311;
UPDATE inventario SET stock_actual = 40, stock_reservado = 2 WHERE producto_id = 312;
UPDATE inventario SET stock_actual = 89, stock_reservado = 0 WHERE producto_id = 313;
UPDATE inventario SET stock_actual = 48, stock_reservado = 2 WHERE producto_id = 314;
UPDATE inventario SET stock_actual = 86, stock_reservado = 1 WHERE producto_id = 315;
UPDATE inventario SET stock_actual = 79, stock_reservado = 0 WHERE producto_id = 316;
UPDATE inventario SET stock_actual = 64, stock_reservado = 2 WHERE producto_id = 317;
UPDATE inventario SET stock_actual = 76, stock_reservado = 0 WHERE producto_id = 318;
UPDATE inventario SET stock_actual = 77, stock_reservado = 0 WHERE producto_id = 319;
UPDATE inventario SET stock_actual = 23, stock_reservado = 2 WHERE producto_id = 320;
UPDATE inventario SET stock_actual = 103, stock_reservado = 0 WHERE producto_id = 321;
UPDATE inventario SET stock_actual = 9, stock_reservado = 1 WHERE producto_id = 322;
UPDATE inventario SET stock_actual = 79, stock_reservado = 2 WHERE producto_id = 323;
UPDATE inventario SET stock_actual = 116, stock_reservado = 0 WHERE producto_id = 324;
UPDATE inventario SET stock_actual = 86, stock_reservado = 0 WHERE producto_id = 325;
UPDATE inventario SET stock_actual = 61, stock_reservado = 2 WHERE producto_id = 326;
UPDATE inventario SET stock_actual = 45, stock_reservado = 0 WHERE producto_id = 327;
UPDATE inventario SET stock_actual = 61, stock_reservado = 0 WHERE producto_id = 328;
UPDATE inventario SET stock_actual = 109, stock_reservado = 0 WHERE producto_id = 329;
UPDATE inventario SET stock_actual = 72, stock_reservado = 0 WHERE producto_id = 330;
UPDATE inventario SET stock_actual = 16, stock_reservado = 0 WHERE producto_id = 331;
UPDATE inventario SET stock_actual = 45, stock_reservado = 0 WHERE producto_id = 332;
UPDATE inventario SET stock_actual = 10, stock_reservado = 1 WHERE producto_id = 333;
UPDATE inventario SET stock_actual = 93, stock_reservado = 0 WHERE producto_id = 334;
UPDATE inventario SET stock_actual = 134, stock_reservado = 2 WHERE producto_id = 335;
UPDATE inventario SET stock_actual = 13, stock_reservado = 0 WHERE producto_id = 336;
UPDATE inventario SET stock_actual = 19, stock_reservado = 0 WHERE producto_id = 337;
UPDATE inventario SET stock_actual = 96, stock_reservado = 0 WHERE producto_id = 338;
UPDATE inventario SET stock_actual = 16, stock_reservado = 2 WHERE producto_id = 339;
UPDATE inventario SET stock_actual = 47, stock_reservado = 0 WHERE producto_id = 340;
UPDATE inventario SET stock_actual = 87, stock_reservado = 1 WHERE producto_id = 341;
UPDATE inventario SET stock_actual = 74, stock_reservado = 0 WHERE producto_id = 342;
UPDATE inventario SET stock_actual = 68, stock_reservado = 2 WHERE producto_id = 343;
UPDATE inventario SET stock_actual = 7, stock_reservado = 0 WHERE producto_id = 344;
UPDATE inventario SET stock_actual = 52, stock_reservado = 0 WHERE producto_id = 345;
UPDATE inventario SET stock_actual = 119, stock_reservado = 0 WHERE producto_id = 346;
UPDATE inventario SET stock_actual = 11, stock_reservado = 0 WHERE producto_id = 347;
UPDATE inventario SET stock_actual = 11, stock_reservado = 0 WHERE producto_id = 348;
UPDATE inventario SET stock_actual = 20, stock_reservado = 2 WHERE producto_id = 349;
UPDATE inventario SET stock_actual = 87, stock_reservado = 0 WHERE producto_id = 350;
UPDATE inventario SET stock_actual = 125, stock_reservado = 0 WHERE producto_id = 351;
UPDATE inventario SET stock_actual = 27, stock_reservado = 1 WHERE producto_id = 352;
UPDATE inventario SET stock_actual = 20, stock_reservado = 1 WHERE producto_id = 353;
UPDATE inventario SET stock_actual = 114, stock_reservado = 1 WHERE producto_id = 354;
UPDATE inventario SET stock_actual = 69, stock_reservado = 0 WHERE producto_id = 355;
UPDATE inventario SET stock_actual = 142, stock_reservado = 2 WHERE producto_id = 356;
UPDATE inventario SET stock_actual = 38, stock_reservado = 0 WHERE producto_id = 357;
UPDATE inventario SET stock_actual = 20, stock_reservado = 2 WHERE producto_id = 358;
UPDATE inventario SET stock_actual = 24, stock_reservado = 0 WHERE producto_id = 359;
UPDATE inventario SET stock_actual = 26, stock_reservado = 2 WHERE producto_id = 360;
UPDATE inventario SET stock_actual = 118, stock_reservado = 2 WHERE producto_id = 361;
UPDATE inventario SET stock_actual = 67, stock_reservado = 0 WHERE producto_id = 362;
UPDATE inventario SET stock_actual = 6, stock_reservado = 0 WHERE producto_id = 363;
UPDATE inventario SET stock_actual = 16, stock_reservado = 1 WHERE producto_id = 364;
UPDATE inventario SET stock_actual = 107, stock_reservado = 1 WHERE producto_id = 365;
UPDATE inventario SET stock_actual = 57, stock_reservado = 0 WHERE producto_id = 366;
UPDATE inventario SET stock_actual = 5, stock_reservado = 0 WHERE producto_id = 367;
UPDATE inventario SET stock_actual = 19, stock_reservado = 0 WHERE producto_id = 368;
UPDATE inventario SET stock_actual = 67, stock_reservado = 0 WHERE producto_id = 369;
UPDATE inventario SET stock_actual = 14, stock_reservado = 0 WHERE producto_id = 370;
UPDATE inventario SET stock_actual = 8, stock_reservado = 0 WHERE producto_id = 371;
UPDATE inventario SET stock_actual = 109, stock_reservado = 0 WHERE producto_id = 372;
UPDATE inventario SET stock_actual = 78, stock_reservado = 0 WHERE producto_id = 373;
UPDATE inventario SET stock_actual = 33, stock_reservado = 0 WHERE producto_id = 374;
UPDATE inventario SET stock_actual = 68, stock_reservado = 0 WHERE producto_id = 375;
UPDATE inventario SET stock_actual = 14, stock_reservado = 1 WHERE producto_id = 376;
UPDATE inventario SET stock_actual = 150, stock_reservado = 0 WHERE producto_id = 377;
UPDATE inventario SET stock_actual = 93, stock_reservado = 0 WHERE producto_id = 378;
UPDATE inventario SET stock_actual = 91, stock_reservado = 0 WHERE producto_id = 379;
UPDATE inventario SET stock_actual = 64, stock_reservado = 0 WHERE producto_id = 380;
UPDATE inventario SET stock_actual = 11, stock_reservado = 0 WHERE producto_id = 381;
UPDATE inventario SET stock_actual = 45, stock_reservado = 2 WHERE producto_id = 382;
UPDATE inventario SET stock_actual = 64, stock_reservado = 0 WHERE producto_id = 383;
UPDATE inventario SET stock_actual = 65, stock_reservado = 0 WHERE producto_id = 384;
UPDATE inventario SET stock_actual = 147, stock_reservado = 2 WHERE producto_id = 385;
UPDATE inventario SET stock_actual = 44, stock_reservado = 1 WHERE producto_id = 386;
UPDATE inventario SET stock_actual = 72, stock_reservado = 2 WHERE producto_id = 387;
UPDATE inventario SET stock_actual = 47, stock_reservado = 2 WHERE producto_id = 388;
UPDATE inventario SET stock_actual = 82, stock_reservado = 0 WHERE producto_id = 389;
UPDATE inventario SET stock_actual = 51, stock_reservado = 2 WHERE producto_id = 390;
UPDATE inventario SET stock_actual = 93, stock_reservado = 0 WHERE producto_id = 391;
UPDATE inventario SET stock_actual = 56, stock_reservado = 0 WHERE producto_id = 392;
UPDATE inventario SET stock_actual = 72, stock_reservado = 0 WHERE producto_id = 393;
UPDATE inventario SET stock_actual = 34, stock_reservado = 0 WHERE producto_id = 394;
UPDATE inventario SET stock_actual = 29, stock_reservado = 2 WHERE producto_id = 395;
UPDATE inventario SET stock_actual = 54, stock_reservado = 2 WHERE producto_id = 396;
UPDATE inventario SET stock_actual = 16, stock_reservado = 0 WHERE producto_id = 397;
UPDATE inventario SET stock_actual = 85, stock_reservado = 0 WHERE producto_id = 398;
UPDATE inventario SET stock_actual = 80, stock_reservado = 0 WHERE producto_id = 399;
UPDATE inventario SET stock_actual = 18, stock_reservado = 0 WHERE producto_id = 400;
UPDATE inventario SET stock_actual = 27, stock_reservado = 0 WHERE producto_id = 401;
UPDATE inventario SET stock_actual = 51, stock_reservado = 0 WHERE producto_id = 402;
UPDATE inventario SET stock_actual = 36, stock_reservado = 1 WHERE producto_id = 403;
UPDATE inventario SET stock_actual = 48, stock_reservado = 2 WHERE producto_id = 404;
UPDATE inventario SET stock_actual = 97, stock_reservado = 1 WHERE producto_id = 405;
UPDATE inventario SET stock_actual = 49, stock_reservado = 0 WHERE producto_id = 406;
UPDATE inventario SET stock_actual = 8, stock_reservado = 0 WHERE producto_id = 407;
UPDATE inventario SET stock_actual = 28, stock_reservado = 0 WHERE producto_id = 408;
UPDATE inventario SET stock_actual = 59, stock_reservado = 2 WHERE producto_id = 409;
UPDATE inventario SET stock_actual = 54, stock_reservado = 0 WHERE producto_id = 410;
UPDATE inventario SET stock_actual = 53, stock_reservado = 2 WHERE producto_id = 411;
UPDATE inventario SET stock_actual = 26, stock_reservado = 0 WHERE producto_id = 412;
UPDATE inventario SET stock_actual = 103, stock_reservado = 0 WHERE producto_id = 413;
UPDATE inventario SET stock_actual = 76, stock_reservado = 1 WHERE producto_id = 414;
UPDATE inventario SET stock_actual = 39, stock_reservado = 1 WHERE producto_id = 415;
UPDATE inventario SET stock_actual = 115, stock_reservado = 0 WHERE producto_id = 416;
UPDATE inventario SET stock_actual = 86, stock_reservado = 2 WHERE producto_id = 417;
UPDATE inventario SET stock_actual = 60, stock_reservado = 0 WHERE producto_id = 418;
UPDATE inventario SET stock_actual = 15, stock_reservado = 1 WHERE producto_id = 419;
UPDATE inventario SET stock_actual = 43, stock_reservado = 2 WHERE producto_id = 420;
UPDATE inventario SET stock_actual = 55, stock_reservado = 0 WHERE producto_id = 421;
UPDATE inventario SET stock_actual = 50, stock_reservado = 0 WHERE producto_id = 422;
UPDATE inventario SET stock_actual = 42, stock_reservado = 2 WHERE producto_id = 423;
UPDATE inventario SET stock_actual = 19, stock_reservado = 0 WHERE producto_id = 424;
UPDATE inventario SET stock_actual = 30, stock_reservado = 1 WHERE producto_id = 425;
UPDATE inventario SET stock_actual = 47, stock_reservado = 0 WHERE producto_id = 426;
UPDATE inventario SET stock_actual = 88, stock_reservado = 0 WHERE producto_id = 427;
UPDATE inventario SET stock_actual = 45, stock_reservado = 0 WHERE producto_id = 428;
UPDATE inventario SET stock_actual = 70, stock_reservado = 0 WHERE producto_id = 429;
UPDATE inventario SET stock_actual = 90, stock_reservado = 1 WHERE producto_id = 430;
UPDATE inventario SET stock_actual = 16, stock_reservado = 2 WHERE producto_id = 431;
UPDATE inventario SET stock_actual = 130, stock_reservado = 0 WHERE producto_id = 432;
UPDATE inventario SET stock_actual = 12, stock_reservado = 1 WHERE producto_id = 433;
UPDATE inventario SET stock_actual = 71, stock_reservado = 1 WHERE producto_id = 434;
UPDATE inventario SET stock_actual = 40, stock_reservado = 0 WHERE producto_id = 435;
UPDATE inventario SET stock_actual = 68, stock_reservado = 0 WHERE producto_id = 436;
UPDATE inventario SET stock_actual = 18, stock_reservado = 0 WHERE producto_id = 437;
UPDATE inventario SET stock_actual = 56, stock_reservado = 1 WHERE producto_id = 438;
UPDATE inventario SET stock_actual = 15, stock_reservado = 0 WHERE producto_id = 439;
UPDATE inventario SET stock_actual = 144, stock_reservado = 1 WHERE producto_id = 440;
UPDATE inventario SET stock_actual = 90, stock_reservado = 1 WHERE producto_id = 441;
UPDATE inventario SET stock_actual = 37, stock_reservado = 2 WHERE producto_id = 442;
UPDATE inventario SET stock_actual = 55, stock_reservado = 2 WHERE producto_id = 443;
UPDATE inventario SET stock_actual = 98, stock_reservado = 0 WHERE producto_id = 444;
UPDATE inventario SET stock_actual = 86, stock_reservado = 0 WHERE producto_id = 445;
UPDATE inventario SET stock_actual = 9, stock_reservado = 2 WHERE producto_id = 446;
UPDATE inventario SET stock_actual = 21, stock_reservado = 2 WHERE producto_id = 447;
UPDATE inventario SET stock_actual = 26, stock_reservado = 2 WHERE producto_id = 448;
UPDATE inventario SET stock_actual = 83, stock_reservado = 0 WHERE producto_id = 449;
UPDATE inventario SET stock_actual = 93, stock_reservado = 0 WHERE producto_id = 450;
UPDATE inventario SET stock_actual = 65, stock_reservado = 2 WHERE producto_id = 451;
UPDATE inventario SET stock_actual = 81, stock_reservado = 1 WHERE producto_id = 452;
UPDATE inventario SET stock_actual = 22, stock_reservado = 0 WHERE producto_id = 453;
UPDATE inventario SET stock_actual = 35, stock_reservado = 2 WHERE producto_id = 454;
UPDATE inventario SET stock_actual = 66, stock_reservado = 0 WHERE producto_id = 455;
UPDATE inventario SET stock_actual = 25, stock_reservado = 1 WHERE producto_id = 456;
UPDATE inventario SET stock_actual = 45, stock_reservado = 0 WHERE producto_id = 457;
UPDATE inventario SET stock_actual = 64, stock_reservado = 0 WHERE producto_id = 458;
UPDATE inventario SET stock_actual = 114, stock_reservado = 0 WHERE producto_id = 459;
UPDATE inventario SET stock_actual = 28, stock_reservado = 2 WHERE producto_id = 460;
UPDATE inventario SET stock_actual = 30, stock_reservado = 2 WHERE producto_id = 461;
UPDATE inventario SET stock_actual = 22, stock_reservado = 0 WHERE producto_id = 462;
UPDATE inventario SET stock_actual = 17, stock_reservado = 0 WHERE producto_id = 463;
UPDATE inventario SET stock_actual = 83, stock_reservado = 2 WHERE producto_id = 464;
UPDATE inventario SET stock_actual = 14, stock_reservado = 0 WHERE producto_id = 465;
UPDATE inventario SET stock_actual = 20, stock_reservado = 2 WHERE producto_id = 466;
UPDATE inventario SET stock_actual = 10, stock_reservado = 0 WHERE producto_id = 467;
UPDATE inventario SET stock_actual = 19, stock_reservado = 2 WHERE producto_id = 468;
UPDATE inventario SET stock_actual = 8, stock_reservado = 0 WHERE producto_id = 469;
UPDATE inventario SET stock_actual = 53, stock_reservado = 0 WHERE producto_id = 470;
UPDATE inventario SET stock_actual = 61, stock_reservado = 0 WHERE producto_id = 471;
UPDATE inventario SET stock_actual = 58, stock_reservado = 0 WHERE producto_id = 472;
UPDATE inventario SET stock_actual = 13, stock_reservado = 0 WHERE producto_id = 473;
UPDATE inventario SET stock_actual = 85, stock_reservado = 0 WHERE producto_id = 474;
UPDATE inventario SET stock_actual = 30, stock_reservado = 0 WHERE producto_id = 475;
UPDATE inventario SET stock_actual = 84, stock_reservado = 2 WHERE producto_id = 476;
UPDATE inventario SET stock_actual = 24, stock_reservado = 0 WHERE producto_id = 477;
UPDATE inventario SET stock_actual = 21, stock_reservado = 0 WHERE producto_id = 478;
UPDATE inventario SET stock_actual = 30, stock_reservado = 0 WHERE producto_id = 479;
UPDATE inventario SET stock_actual = 115, stock_reservado = 0 WHERE producto_id = 480;
UPDATE inventario SET stock_actual = 45, stock_reservado = 2 WHERE producto_id = 481;
UPDATE inventario SET stock_actual = 136, stock_reservado = 0 WHERE producto_id = 482;
UPDATE inventario SET stock_actual = 79, stock_reservado = 1 WHERE producto_id = 483;
UPDATE inventario SET stock_actual = 59, stock_reservado = 1 WHERE producto_id = 484;
UPDATE inventario SET stock_actual = 20, stock_reservado = 2 WHERE producto_id = 485;
UPDATE inventario SET stock_actual = 23, stock_reservado = 0 WHERE producto_id = 486;
UPDATE inventario SET stock_actual = 42, stock_reservado = 0 WHERE producto_id = 487;
UPDATE inventario SET stock_actual = 29, stock_reservado = 0 WHERE producto_id = 488;
UPDATE inventario SET stock_actual = 43, stock_reservado = 0 WHERE producto_id = 489;
UPDATE inventario SET stock_actual = 144, stock_reservado = 0 WHERE producto_id = 490;
UPDATE inventario SET stock_actual = 114, stock_reservado = 0 WHERE producto_id = 491;
UPDATE inventario SET stock_actual = 30, stock_reservado = 0 WHERE producto_id = 492;
UPDATE inventario SET stock_actual = 56, stock_reservado = 1 WHERE producto_id = 493;
UPDATE inventario SET stock_actual = 41, stock_reservado = 0 WHERE producto_id = 494;
UPDATE inventario SET stock_actual = 40, stock_reservado = 0 WHERE producto_id = 495;
UPDATE inventario SET stock_actual = 9, stock_reservado = 0 WHERE producto_id = 496;
UPDATE inventario SET stock_actual = 49, stock_reservado = 0 WHERE producto_id = 497;
UPDATE inventario SET stock_actual = 55, stock_reservado = 0 WHERE producto_id = 498;
UPDATE inventario SET stock_actual = 15, stock_reservado = 2 WHERE producto_id = 499;
UPDATE inventario SET stock_actual = 38, stock_reservado = 0 WHERE producto_id = 500;

-- Resumen: 4 roles, 5 usuarios, 20 categorías, 50 proveedores, 20 clientes, 500 productos.