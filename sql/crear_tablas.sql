CREATE DATABASE IF NOT EXISTS crud_ubicaciones
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE crud_ubicaciones;

CREATE TABLE pais (
  pais_id INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  PRIMARY KEY (pais_id),
  UNIQUE KEY uq_pais_nombre (nombre)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE departamento (
  departamento_id INT NOT NULL AUTO_INCREMENT,
  pais_id INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  PRIMARY KEY (departamento_id),
  UNIQUE KEY uq_departamento_pais_nombre (pais_id, nombre),
  KEY idx_departamento_pais_id (pais_id),
  CONSTRAINT fk_departamento_pais
    FOREIGN KEY (pais_id)
    REFERENCES pais (pais_id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE ciudad (
  ciudad_id INT NOT NULL AUTO_INCREMENT,
  departamento_id INT NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  PRIMARY KEY (ciudad_id),
  UNIQUE KEY uq_ciudad_departamento_nombre (departamento_id, nombre),
  KEY idx_ciudad_departamento_id (departamento_id),
  CONSTRAINT fk_ciudad_departamento
    FOREIGN KEY (departamento_id)
    REFERENCES departamento (departamento_id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
