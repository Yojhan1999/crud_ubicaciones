<?php

spl_autoload_register(function (string $class): void {
  $rutas = [
    __DIR__ . '/../Core/' . $class . '.php',
    __DIR__ . '/../App/Controllers/' . $class . '.php',
    __DIR__ . '/../App/Models/' . $class . '.php'
  ];

  foreach ($rutas as $ruta) {
    if (file_exists($ruta)) {
      require_once $ruta;
      return;
    }
  }
});
