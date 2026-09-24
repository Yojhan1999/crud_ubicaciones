const modalPais = new bootstrap.Modal(document.getElementById('modalPais'));
const modalDepartamento = new bootstrap.Modal(document.getElementById('modalDepartamento'));
const modalCiudad = new bootstrap.Modal(document.getElementById('modalCiudad'));

$(async function () {
  await cargarTodo();

  $('#form_pais').on('submit', guardarPais);
  $('#form_departamento').on('submit', guardarDepartamento);
  $('#form_ciudad').on('submit', guardarCiudad);
  $('#ciudad_pais_id').on('change', async function () {
    await cargarDepartamentosCombo(Number($(this).val()), null);
  });
});

async function cargarTodo() {
  await Promise.all([
    listarPaises(),
    listarDepartamentos(),
    listarCiudades()
  ]);
}

function peticionAjax(url, metodo = 'GET', datos = null) {
  return $.ajax({
    url,
    method: metodo,
    contentType: 'application/json; charset=utf-8',
    dataType: 'json',
    data: datos !== null ? JSON.stringify(datos) : undefined
  });
}

function mostrarAlerta(mensaje, tipo = 'success') {
  $('#alerta').html(`
    <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
      ${escaparHtml(mensaje)}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  `);
}

function mensajeError(xhr) {
  return xhr.responseJSON?.mensaje || 'Ocurrió un error al procesar la solicitud.';
}

function escaparHtml(valor) {
  return $('<div>').text(valor ?? '').html();
}

async function listarPaises() {
  const respuesta = await peticionAjax('index.php?modulo=pais&accion=listar');
  const filas = respuesta.datos.map((pais) => `
    <tr>
      <td>${pais.pais_id}</td>
      <td>${escaparHtml(pais.nombre)}</td>
      <td class="text-end">
        <button class="btn btn-sm btn-outline-primary" onclick="editarPais(${pais.pais_id})">Editar</button>
        <button class="btn btn-sm btn-outline-danger" onclick="eliminarPais(${pais.pais_id})">Eliminar</button>
      </td>
    </tr>
  `).join('');

  $('#tabla_paises').html(filas || '<tr><td colspan="3" class="text-center text-secondary py-4">Sin registros.</td></tr>');
}

async function cargarPaisesCombo(selector, seleccionado = null) {
  const respuesta = await peticionAjax('index.php?modulo=pais&accion=listar');
  let opciones = '<option value="">Seleccione...</option>';

  respuesta.datos.forEach((pais) => {
    const selected = Number(seleccionado) === Number(pais.pais_id) ? 'selected' : '';
    opciones += `<option value="${pais.pais_id}" ${selected}>${escaparHtml(pais.nombre)}</option>`;
  });

  $(selector).html(opciones);
}

function abrirModalPais() {
  $('#pais_id').val('');
  $('#pais_nombre').val('');
  $('#titulo_modal_pais').text('Nuevo país');
  modalPais.show();
}

async function editarPais(pais_id) {
  const respuesta = await peticionAjax(`index.php?modulo=pais&accion=obtener&pais_id=${pais_id}`);
  $('#pais_id').val(respuesta.datos.pais_id);
  $('#pais_nombre').val(respuesta.datos.nombre);
  $('#titulo_modal_pais').text('Editar país');
  modalPais.show();
}

async function guardarPais(evento) {
  evento.preventDefault();
  const pais_id = Number($('#pais_id').val());
  const datos = {
    nombre: $('#pais_nombre').val().trim()
  };
  let accion = 'guardar';

  if (pais_id > 0) {
    datos.pais_id = pais_id;
    accion = 'actualizar';
  }

  try {
    const respuesta = await peticionAjax(`index.php?modulo=pais&accion=${accion}`, 'POST', datos);
    modalPais.hide();
    mostrarAlerta(respuesta.mensaje);
    await cargarTodo();
  } catch (xhr) {
    mostrarAlerta(mensajeError(xhr), 'danger');
  }
}

async function eliminarPais(pais_id) {
  if (!confirm('¿Desea eliminar este país?')) {
    return;
  }

  try {
    const respuesta = await peticionAjax('index.php?modulo=pais&accion=eliminar', 'POST', {pais_id});
    mostrarAlerta(respuesta.mensaje);
    await cargarTodo();
  } catch (xhr) {
    mostrarAlerta(mensajeError(xhr), 'danger');
  }
}

async function listarDepartamentos() {
  const respuesta = await peticionAjax('index.php?modulo=departamento&accion=listar');
  const filas = respuesta.datos.map((departamento) => `
    <tr>
      <td>${departamento.departamento_id}</td>
      <td>${escaparHtml(departamento.pais)}</td>
      <td>${escaparHtml(departamento.nombre)}</td>
      <td class="text-end">
        <button class="btn btn-sm btn-outline-primary" onclick="editarDepartamento(${departamento.departamento_id})">Editar</button>
        <button class="btn btn-sm btn-outline-danger" onclick="eliminarDepartamento(${departamento.departamento_id})">Eliminar</button>
      </td>
    </tr>
  `).join('');

  $('#tabla_departamentos').html(filas || '<tr><td colspan="4" class="text-center text-secondary py-4">Sin registros.</td></tr>');
}

async function cargarDepartamentosCombo(pais_id, seleccionado = null) {
  const selector = '#ciudad_departamento_id';

  if (!pais_id) {
    $(selector).html('<option value="">Seleccione primero un país...</option>');
    return;
  }

  const respuesta = await peticionAjax(`index.php?modulo=departamento&accion=listar&pais_id=${pais_id}`);
  let opciones = '<option value="">Seleccione...</option>';

  respuesta.datos.forEach((departamento) => {
    const selected = Number(seleccionado) === Number(departamento.departamento_id) ? 'selected' : '';
    opciones += `<option value="${departamento.departamento_id}" ${selected}>${escaparHtml(departamento.nombre)}</option>`;
  });

  $(selector).html(opciones);
}

async function abrirModalDepartamento() {
  $('#departamento_id').val('');
  $('#departamento_nombre').val('');
  $('#titulo_modal_departamento').text('Nuevo departamento');
  await cargarPaisesCombo('#departamento_pais_id');
  modalDepartamento.show();
}

async function editarDepartamento(departamento_id) {
  const respuesta = await peticionAjax(`index.php?modulo=departamento&accion=obtener&departamento_id=${departamento_id}`);
  $('#departamento_id').val(respuesta.datos.departamento_id);
  $('#departamento_nombre').val(respuesta.datos.nombre);
  $('#titulo_modal_departamento').text('Editar departamento');
  await cargarPaisesCombo('#departamento_pais_id', respuesta.datos.pais_id);
  modalDepartamento.show();
}

async function guardarDepartamento(evento) {
  evento.preventDefault();
  const departamento_id = Number($('#departamento_id').val());
  const datos = {
    pais_id: Number($('#departamento_pais_id').val()),
    nombre: $('#departamento_nombre').val().trim()
  };
  let accion = 'guardar';

  if (departamento_id > 0) {
    datos.departamento_id = departamento_id;
    accion = 'actualizar';
  }

  try {
    const respuesta = await peticionAjax(`index.php?modulo=departamento&accion=${accion}`, 'POST', datos);
    modalDepartamento.hide();
    mostrarAlerta(respuesta.mensaje);
    await cargarTodo();
  } catch (xhr) {
    mostrarAlerta(mensajeError(xhr), 'danger');
  }
}

async function eliminarDepartamento(departamento_id) {
  if (!confirm('¿Desea eliminar este departamento?')) {
    return;
  }

  try {
    const respuesta = await peticionAjax('index.php?modulo=departamento&accion=eliminar', 'POST', {departamento_id});
    mostrarAlerta(respuesta.mensaje);
    await cargarTodo();
  } catch (xhr) {
    mostrarAlerta(mensajeError(xhr), 'danger');
  }
}

async function listarCiudades() {
  const respuesta = await peticionAjax('index.php?modulo=ciudad&accion=listar');
  const filas = respuesta.datos.map((ciudad) => `
    <tr>
      <td>${ciudad.ciudad_id}</td>
      <td>${escaparHtml(ciudad.pais)}</td>
      <td>${escaparHtml(ciudad.departamento)}</td>
      <td>${escaparHtml(ciudad.nombre)}</td>
      <td class="text-end">
        <button class="btn btn-sm btn-outline-primary" onclick="editarCiudad(${ciudad.ciudad_id})">Editar</button>
        <button class="btn btn-sm btn-outline-danger" onclick="eliminarCiudad(${ciudad.ciudad_id})">Eliminar</button>
      </td>
    </tr>
  `).join('');

  $('#tabla_ciudades').html(filas || '<tr><td colspan="5" class="text-center text-secondary py-4">Sin registros.</td></tr>');
}

async function abrirModalCiudad() {
  $('#ciudad_id').val('');
  $('#ciudad_nombre').val('');
  $('#titulo_modal_ciudad').text('Nueva ciudad');
  await cargarPaisesCombo('#ciudad_pais_id');
  $('#ciudad_departamento_id').html('<option value="">Seleccione primero un país...</option>');
  modalCiudad.show();
}

async function editarCiudad(ciudad_id) {
  const respuesta = await peticionAjax(`index.php?modulo=ciudad&accion=obtener&ciudad_id=${ciudad_id}`);
  $('#ciudad_id').val(respuesta.datos.ciudad_id);
  $('#ciudad_nombre').val(respuesta.datos.nombre);
  $('#titulo_modal_ciudad').text('Editar ciudad');
  await cargarPaisesCombo('#ciudad_pais_id', respuesta.datos.pais_id);
  await cargarDepartamentosCombo(respuesta.datos.pais_id, respuesta.datos.departamento_id);
  modalCiudad.show();
}

async function guardarCiudad(evento) {
  evento.preventDefault();
  const ciudad_id = Number($('#ciudad_id').val());
  const datos = {
    departamento_id: Number($('#ciudad_departamento_id').val()),
    nombre: $('#ciudad_nombre').val().trim()
  };
  let accion = 'guardar';

  if (ciudad_id > 0) {
    datos.ciudad_id = ciudad_id;
    accion = 'actualizar';
  }

  try {
    const respuesta = await peticionAjax(`index.php?modulo=ciudad&accion=${accion}`, 'POST', datos);
    modalCiudad.hide();
    mostrarAlerta(respuesta.mensaje);
    await cargarTodo();
  } catch (xhr) {
    mostrarAlerta(mensajeError(xhr), 'danger');
  }
}

async function eliminarCiudad(ciudad_id) {
  if (!confirm('¿Desea eliminar esta ciudad?')) {
    return;
  }

  try {
    const respuesta = await peticionAjax('index.php?modulo=ciudad&accion=eliminar', 'POST', {ciudad_id});
    mostrarAlerta(respuesta.mensaje);
    await cargarTodo();
  } catch (xhr) {
    mostrarAlerta(mensajeError(xhr), 'danger');
  }
}
