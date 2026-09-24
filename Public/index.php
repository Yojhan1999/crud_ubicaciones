<?php

require_once __DIR__ . '/../Core/Autoloader.php';

$modulo = strtolower((string) ($_GET['modulo'] ?? ''));
$accion = strtolower((string) ($_GET['accion'] ?? ''));

if ($modulo === '' && $accion === '') {
  require_once __DIR__ . '/../App/Views/index.php';
  exit;
}

$controladores = [
  'pais'         => PaisController::class,
  'departamento' => DepartamentoController::class,
  'ciudad'       => CiudadController::class
];

$accionesPermitidas = ['listar', 'obtener', 'guardar', 'actualizar', 'eliminar'];

if (!isset($controladores[$modulo]) || !in_array($accion, $accionesPermitidas, true)) {
  http_response_code(404);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['ok' => false, 'mensaje' => 'Ruta no encontrada.'], JSON_UNESCAPED_UNICODE);
  exit;
}

$controller = new $controladores[$modulo]();
$controller->{$accion}();
