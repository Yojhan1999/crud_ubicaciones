<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CRUD de ubicaciones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <main class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
      <div>
        <h1 class="h3 mb-1">Administración de ubicaciones</h1>
        <p class="text-secondary mb-0">CRUD de países, departamentos y ciudades.</p>
      </div>
    </div>

    <div id="alerta"></div>

    <ul class="nav nav-tabs mb-3" id="ubicacionesTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-paises" type="button">Países</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-departamentos" type="button">Departamentos</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-ciudades" type="button">Ciudades</button>
      </li>
    </ul>

    <div class="tab-content">
      <section class="tab-pane fade show active" id="tab-paises">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h2 class="h5 mb-0">Países</h2>
            <button class="btn btn-primary" type="button" onclick="abrirModalPais()">Nuevo país</button>
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

      <section class="tab-pane fade" id="tab-departamentos">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h2 class="h5 mb-0">Departamentos</h2>
            <button class="btn btn-primary" type="button" onclick="abrirModalDepartamento()">Nuevo departamento</button>
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

      <section class="tab-pane fade" id="tab-ciudades">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h2 class="h5 mb-0">Ciudades</h2>
            <button class="btn btn-primary" type="button" onclick="abrirModalCiudad()">Nueva ciudad</button>
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
    </div>
  </main>

  <div class="modal fade" id="modalPais" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form class="modal-content" id="form_pais">
        <div class="modal-header">
          <h2 class="modal-title fs-5" id="titulo_modal_pais">Nuevo país</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="pais_id">
          <label class="form-label" for="pais_nombre">Nombre</label>
          <input class="form-control" id="pais_nombre" maxlength="100" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="modalDepartamento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form class="modal-content" id="form_departamento">
        <div class="modal-header">
          <h2 class="modal-title fs-5" id="titulo_modal_departamento">Nuevo departamento</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="departamento_id">
          <div class="mb-3">
            <label class="form-label" for="departamento_pais_id">País</label>
            <select class="form-select" id="departamento_pais_id" required></select>
          </div>
          <div>
            <label class="form-label" for="departamento_nombre">Nombre</label>
            <input class="form-control" id="departamento_nombre" maxlength="100" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="modalCiudad" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form class="modal-content" id="form_ciudad">
        <div class="modal-header">
          <h2 class="modal-title fs-5" id="titulo_modal_ciudad">Nueva ciudad</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="ciudad_id">
          <div class="mb-3">
            <label class="form-label" for="ciudad_pais_id">País</label>
            <select class="form-select" id="ciudad_pais_id" required></select>
          </div>
          <div class="mb-3">
            <label class="form-label" for="ciudad_departamento_id">Departamento</label>
            <select class="form-select" id="ciudad_departamento_id" required></select>
          </div>
          <div>
            <label class="form-label" for="ciudad_nombre">Nombre</label>
            <input class="form-control" id="ciudad_nombre" maxlength="100" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="Public/assets/js/ubicaciones.js"></script>
</body>
</html>
