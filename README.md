# CRUD de ubicaciones

CRUD desarrollado con PHP puro, PDO, MySQL/MariaDB, Bootstrap 5, jQuery y AJAX.

## Requisitos

- XAMPP con Apache y MySQL activos.
- PHP 8.1 o superior.
- Extensión `pdo_mysql` habilitada.
- `mod_rewrite` habilitado en Apache.

## Instalación

1. Copiar la carpeta `crud_ubicaciones` dentro de `C:\\xampp\\htdocs\\`.
2. Ingresar a phpMyAdmin.
3. Ejecutar `sql/crear_tablas.sql`.
4. Verificar la conexión en `Core/Database.php`.
5. Abrir `http://localhost/crud_ubicaciones/index`.

## Conexión predeterminada

- Host: `localhost`
- Puerto: `3306`
- Base de datos: `crud_ubicaciones`
- Usuario: `root`
- Contraseña: vacía

## Relaciones

- Un país puede tener muchos departamentos.
- Un departamento pertenece a un país.
- Un departamento puede tener muchas ciudades.
- Una ciudad pertenece a un departamento.
- No se puede eliminar un país con departamentos relacionados.
- No se puede eliminar un departamento con ciudades relacionadas.
