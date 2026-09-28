<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CRUD de ubicaciones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .modulo-seccion {
      display: none;
    }

    .modulo-seccion.activa {
      display: block;
    }

    .titulo-formulario {
      font-size: 2.3rem;
      font-weight: 700;
      text-align: center;
      margin-bottom: 1.75rem;
    }

    .barra-opciones .btn {
      min-width: 150px;
    }

    .acciones-formulario {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
      margin-top: 1rem;
    }

    @media (max-width: 768px) {
      .acciones-formulario {
        flex-direction: column-reverse;
        align-items: stretch;
      }

      .acciones-formulario .btn {
        width: 100%;
      }
    }
  </style>
</head>
<body class="bg-light">
  <main class="container py-4 py-md-5">
    <div class="text-center mb-4">
      <h1 class="display-6 fw-bold mb-2">CRUD de ubicaciones</h1>
      <p class="text-secondary mb-0">Selecciona el módulo que deseas administrar.</p>
    </div>

    <div id="alerta"></div>

    <div class="d-flex flex-wrap justify-content-center gap-3 barra-opciones mb-5">
      <button class="btn btn-outline-primary" type="button" onclick="abrirModalPais()">País</button>
      <button class="btn btn-outline-primary" type="button" onclick="abrirModalDepartamento()">Departamento</button>
      <button class="btn btn-outline-primary" type="button" onclick="abrirModalCiudad()">Ciudad</button>
    </div>

    <section class="modulo-seccion activa" id="seccion_pais">
      <h2 class="titulo-formulario" id="titulo_pais">Crear País</h2>
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
          <form id="form_pais">
            <input type="hidden" id="pais_id">
            <div class="mb-3">
              <label class="form-label" for="pais_nombre">País:</label>
              <input class="form-control" id="pais_nombre" maxlength="100" placeholder="Ingresa el nombre del país" required>
            </div>
            <div class="acciones-formulario">
              <button type="button" class="btn btn-secondary" onclick="cancelarFormularioPais()">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
      </div>

      <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
          <h3 class="h5 mb-0">Listado de países</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody id="tabla_paises"></tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <section class="modulo-seccion" id="seccion_departamento">
      <h2 class="titulo-formulario" id="titulo_departamento">Crear Departamento</h2>
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
          <form id="form_departamento">
            <input type="hidden" id="departamento_id">
            <div class="mb-3">
              <label class="form-label" for="departamento_pais_id">País:</label>
              <select class="form-select" id="departamento_pais_id" required></select>
            </div>
            <div class="mb-3">
              <label class="form-label" for="departamento_nombre">Departamento:</label>
              <input class="form-control" id="departamento_nombre" maxlength="100" placeholder="Ingresa el nombre del departamento" required>
            </div>
            <div class="acciones-formulario">
              <button type="button" class="btn btn-secondary" onclick="cancelarFormularioDepartamento()">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
      </div>

      <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
          <h3 class="h5 mb-0">Listado de departamentos</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>País</th>
                  <th>Departamento</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody id="tabla_departamentos"></tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <section class="modulo-seccion" id="seccion_ciudad">
      <h2 class="titulo-formulario" id="titulo_ciudad">Crear Ciudad</h2>
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
          <form id="form_ciudad">
            <input type="hidden" id="ciudad_id">
            <div class="mb-3">
              <label class="form-label" for="ciudad_pais_id">País:</label>
              <select class="form-select" id="ciudad_pais_id" required></select>
            </div>
            <div class="mb-3">
              <label class="form-label" for="ciudad_departamento_id">Departamento:</label>
              <select class="form-select" id="ciudad_departamento_id" required></select>
            </div>
            <div class="mb-3">
              <label class="form-label" for="ciudad_nombre">Ciudad:</label>
              <input class="form-control" id="ciudad_nombre" maxlength="100" placeholder="Ingresa el nombre de la ciudad" required>
            </div>
            <div class="acciones-formulario">
              <button type="button" class="btn btn-secondary" onclick="cancelarFormularioCiudad()">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
      </div>

      <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
          <h3 class="h5 mb-0">Listado de ciudades</h3>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>País</th>
                  <th>Departamento</th>
                  <th>Ciudad</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody id="tabla_ciudades"></tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <?php
    $ruta_js = str_contains($_SERVER['REQUEST_URI'], '/Public')
      ? 'assets/js/ubicaciones.js'
      : 'Public/assets/js/ubicaciones.js';
  ?>
  <script src="<?php echo $ruta_js; ?>"></script>
</body>
</html>
