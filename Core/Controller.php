<?php

abstract class Controller {
  protected function responder(array $datos, int $codigo = 200): never {
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
  }

  protected function obtenerJson(): array {
    $contenido = file_get_contents('php://input');
    $datos     = json_decode($contenido ?: '{}', true);

    return is_array($datos) ? $datos : [];
  }
}
