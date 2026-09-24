<?php

class CiudadController extends Controller {
  private Ciudad $model;

  public function __construct() {
    $this->model = new Ciudad();
  }

  public function listar(): never {
    $departamento_id = isset($_GET['departamento_id']) && $_GET['departamento_id'] !== ''
      ? (int) $_GET['departamento_id']
      : null;

    $this->responder(['ok' => true, 'datos' => $this->model->listar($departamento_id)]);
  }

  public function obtener(): never {
    $ciudad_id = (int) ($_GET['ciudad_id'] ?? 0);
    $ciudad    = $this->model->obtener($ciudad_id);

    if (!$ciudad) {
      $this->responder(['ok' => false, 'mensaje' => 'Ciudad no encontrada.'], 404);
    }

    $this->responder(['ok' => true, 'datos' => $ciudad]);
  }

  public function guardar(): never {
    $datos           = $this->obtenerJson();
    $departamento_id = (int) ($datos['departamento_id'] ?? 0);
    $nombre          = trim((string) ($datos['nombre'] ?? ''));

    if ($departamento_id <= 0 || $nombre === '') {
      $this->responder(['ok' => false, 'mensaje' => 'Departamento y nombre de la ciudad son obligatorios.'], 422);
    }

    try {
      $ciudad_id = $this->model->guardar($departamento_id, $nombre);
      $this->responder(['ok' => true, 'mensaje' => 'Ciudad guardada correctamente.', 'ciudad_id' => $ciudad_id], 201);
    } catch (PDOException $e) {
      $this->responder(['ok' => false, 'mensaje' => $this->mensajeError($e)], 409);
    }
  }

  public function actualizar(): never {
    $datos           = $this->obtenerJson();
    $ciudad_id       = (int) ($datos['ciudad_id'] ?? 0);
    $departamento_id = (int) ($datos['departamento_id'] ?? 0);
    $nombre          = trim((string) ($datos['nombre'] ?? ''));

    if ($ciudad_id <= 0 || $departamento_id <= 0 || $nombre === '') {
      $this->responder(['ok' => false, 'mensaje' => 'Datos de la ciudad incompletos.'], 422);
    }

    try {
      $this->model->actualizar($ciudad_id, $departamento_id, $nombre);
      $this->responder(['ok' => true, 'mensaje' => 'Ciudad actualizada correctamente.']);
    } catch (PDOException $e) {
      $this->responder(['ok' => false, 'mensaje' => $this->mensajeError($e)], 409);
    }
  }

  public function eliminar(): never {
    $datos     = $this->obtenerJson();
    $ciudad_id = (int) ($datos['ciudad_id'] ?? 0);

    if ($ciudad_id <= 0) {
      $this->responder(['ok' => false, 'mensaje' => 'Ciudad inválida.'], 422);
    }

    try {
      $this->model->eliminar($ciudad_id);
      $this->responder(['ok' => true, 'mensaje' => 'Ciudad eliminada correctamente.']);
    } catch (PDOException $e) {
      $this->responder(['ok' => false, 'mensaje' => $this->mensajeError($e)], 409);
    }
  }

  private function mensajeError(PDOException $e): string {
    $codigo_mysql = (int) ($e->errorInfo[1] ?? 0);

    if ($codigo_mysql === 1062) {
      return 'Ya existe esa ciudad para el departamento seleccionado.';
    }

    if ($codigo_mysql === 1452) {
      return 'El departamento seleccionado no existe.';
    }

    return 'No fue posible completar la operación.';
  }
}
