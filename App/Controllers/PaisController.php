<?php

class PaisController extends Controller {
  private Pais $model;

  public function __construct() {
    $this->model = new Pais();
  }

  public function listar(): never {
    $this->responder(['ok' => true, 'datos' => $this->model->listar()]);
  }

  public function obtener(): never {
    $pais_id = (int) ($_GET['pais_id'] ?? 0);
    $pais    = $this->model->obtener($pais_id);

    if (!$pais) {
      $this->responder(['ok' => false, 'mensaje' => 'País no encontrado.'], 404);
    }

    $this->responder(['ok' => true, 'datos' => $pais]);
  }

  public function guardar(): never {
    $datos  = $this->obtenerJson();
    $nombre = trim((string) ($datos['nombre'] ?? ''));

    if ($nombre === '') {
      $this->responder(['ok' => false, 'mensaje' => 'El nombre del país es obligatorio.'], 422);
    }

    try {
      $pais_id = $this->model->guardar($nombre);
      $this->responder(['ok' => true, 'mensaje' => 'País guardado correctamente.', 'pais_id' => $pais_id], 201);
    } catch (PDOException $e) {
      $this->responder(['ok' => false, 'mensaje' => $this->mensajeError($e)], 409);
    }
  }

  public function actualizar(): never {
    $datos   = $this->obtenerJson();
    $pais_id = (int) ($datos['pais_id'] ?? 0);
    $nombre  = trim((string) ($datos['nombre'] ?? ''));

    if ($pais_id <= 0 || $nombre === '') {
      $this->responder(['ok' => false, 'mensaje' => 'Datos del país incompletos.'], 422);
    }

    try {
      $this->model->actualizar($pais_id, $nombre);
      $this->responder(['ok' => true, 'mensaje' => 'País actualizado correctamente.']);
    } catch (PDOException $e) {
      $this->responder(['ok' => false, 'mensaje' => $this->mensajeError($e)], 409);
    }
  }

  public function eliminar(): never {
    $datos   = $this->obtenerJson();
    $pais_id = (int) ($datos['pais_id'] ?? 0);

    if ($pais_id <= 0) {
      $this->responder(['ok' => false, 'mensaje' => 'País inválido.'], 422);
    }

    try {
      $this->model->eliminar($pais_id);
      $this->responder(['ok' => true, 'mensaje' => 'País eliminado correctamente.']);
    } catch (PDOException $e) {
      $this->responder(['ok' => false, 'mensaje' => $this->mensajeError($e)], 409);
    }
  }

  private function mensajeError(PDOException $e): string {
    $codigo_mysql = (int) ($e->errorInfo[1] ?? 0);

    if ($codigo_mysql === 1062) {
      return 'Ya existe un país con ese nombre.';
    }

    if ($codigo_mysql === 1451) {
      return 'No se puede eliminar el país porque tiene departamentos relacionados.';
    }

    return 'No fue posible completar la operación.';
  }
}
