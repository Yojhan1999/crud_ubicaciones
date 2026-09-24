<?php

class DepartamentoController extends Controller {
  private Departamento $model;

  public function __construct() {
    $this->model = new Departamento();
  }

  public function listar(): never {
    $pais_id = isset($_GET['pais_id']) && $_GET['pais_id'] !== '' ? (int) $_GET['pais_id'] : null;
    $this->responder(['ok' => true, 'datos' => $this->model->listar($pais_id)]);
  }

  public function obtener(): never {
    $departamento_id = (int) ($_GET['departamento_id'] ?? 0);
    $departamento    = $this->model->obtener($departamento_id);

    if (!$departamento) {
      $this->responder(['ok' => false, 'mensaje' => 'Departamento no encontrado.'], 404);
    }

    $this->responder(['ok' => true, 'datos' => $departamento]);
  }

  public function guardar(): never {
    $datos   = $this->obtenerJson();
    $pais_id = (int) ($datos['pais_id'] ?? 0);
    $nombre  = trim((string) ($datos['nombre'] ?? ''));

    if ($pais_id <= 0 || $nombre === '') {
      $this->responder(['ok' => false, 'mensaje' => 'País y nombre del departamento son obligatorios.'], 422);
    }

    try {
      $departamento_id = $this->model->guardar($pais_id, $nombre);
      $this->responder([
        'ok'              => true,
        'mensaje'         => 'Departamento guardado correctamente.',
        'departamento_id' => $departamento_id
      ], 201);
    } catch (PDOException $e) {
      $this->responder(['ok' => false, 'mensaje' => $this->mensajeError($e)], 409);
    }
  }

  public function actualizar(): never {
    $datos           = $this->obtenerJson();
    $departamento_id = (int) ($datos['departamento_id'] ?? 0);
    $pais_id         = (int) ($datos['pais_id'] ?? 0);
    $nombre          = trim((string) ($datos['nombre'] ?? ''));

    if ($departamento_id <= 0 || $pais_id <= 0 || $nombre === '') {
      $this->responder(['ok' => false, 'mensaje' => 'Datos del departamento incompletos.'], 422);
    }

    try {
      $this->model->actualizar($departamento_id, $pais_id, $nombre);
      $this->responder(['ok' => true, 'mensaje' => 'Departamento actualizado correctamente.']);
    } catch (PDOException $e) {
      $this->responder(['ok' => false, 'mensaje' => $this->mensajeError($e)], 409);
    }
  }

  public function eliminar(): never {
    $datos           = $this->obtenerJson();
    $departamento_id = (int) ($datos['departamento_id'] ?? 0);

    if ($departamento_id <= 0) {
      $this->responder(['ok' => false, 'mensaje' => 'Departamento inválido.'], 422);
    }

    try {
      $this->model->eliminar($departamento_id);
      $this->responder(['ok' => true, 'mensaje' => 'Departamento eliminado correctamente.']);
    } catch (PDOException $e) {
      $this->responder(['ok' => false, 'mensaje' => $this->mensajeError($e)], 409);
    }
  }

  private function mensajeError(PDOException $e): string {
    $codigo_mysql = (int) ($e->errorInfo[1] ?? 0);

    if ($codigo_mysql === 1062) {
      return 'Ya existe ese departamento para el país seleccionado.';
    }

    if ($codigo_mysql === 1451) {
      return 'No se puede eliminar el departamento porque tiene ciudades relacionadas.';
    }

    if ($codigo_mysql === 1452) {
      return 'El país seleccionado no existe.';
    }

    return 'No fue posible completar la operación.';
  }
}
