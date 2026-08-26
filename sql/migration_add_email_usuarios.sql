-- =====================================================================
-- schecontroll — Migración: agregar columna email a usuarios
-- Ejecuta esto si ya tienes la base de datos creada sin el campo email
-- =====================================================================

ALTER TABLE usuarios
  ADD COLUMN email VARCHAR(150) UNIQUE NULL
  AFTER username;

-- Verificar resultado:
-- SHOW COLUMNS FROM usuarios;
