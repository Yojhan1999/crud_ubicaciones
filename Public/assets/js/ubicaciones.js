document.addEventListener('DOMContentLoaded', async () => {
  document.getElementById('form_pais').addEventListener('submit', guardarPais);
  document.getElementById('form_departamento').addEventListener('submit', guardarDepartamento);
  document.getElementById('form_ciudad').addEventListener('submit', guardarCiudad);

  document.getElementById('ciudad_pais_id').addEventListener('change', async function () {
    await cargarDepartamentosCombo(Number(this.value), null);
  });

  await cargarTodo();
  mostrarSeccion('pais');
});

async function cargarTodo() {
  await Promise.all([
    listarPaises(),
    listarDepartamentos(),
    listarCiudades()
  ]);
}

async function peticionAjax(url, metodo = 'GET', datos = null) {
  const opciones = {
    method: metodo,
    headers: {
      'Accept': 'application/json'
    }
  };

  if (datos !== null) {
    opciones.headers['Content-Type'] = 'application/json; charset=utf-8';
    opciones.body = JSON.stringify(datos);
  }

  const peticion = await fetch(url, opciones);
  const respuesta = await peticion.json();

  if (!peticion.ok) {
    throw new Error(respuesta.mensaje || 'Ocurrió un error al procesar la solicitud.');
  }

  return respuesta;
}

function mostrarAlerta(mensaje, tipo = 'success') {
  document.getElementById('alerta').innerHTML = `
    <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
      ${escaparHtml(mensaje)}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  `;
}

function mensajeError(error) {
  return error.message || 'Ocurrió un error al procesar la solicitud.';
}

function escaparHtml(valor) {
  const elemento = document.createElement('div');
  elemento.textContent = valor ?? '';
  return elemento.innerHTML;
}

function mostrarSeccion(modulo) {
  document.querySelectorAll('.modulo-seccion').forEach((seccion) => {
    seccion.classList.remove('activa');
  });

  document.getElementById(`seccion_${modulo}`).classList.add('activa');
}

async function listarPaises() {
  try {
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

    document.getElementById('tabla_paises').innerHTML = filas
      || '<tr><td colspan="3" class="text-center text-secondary py-4">Sin registros.</td></tr>';
  } catch (error) {
    mostrarAlerta(mensajeError(error), 'danger');
  }
}

async function cargarPaisesCombo(selector, seleccionado = null) {
  const respuesta = await peticionAjax('index.php?modulo=pais&accion=listar');
  let opciones = '<option value="">-- Selecciona un país --</option>';

  respuesta.datos.forEach((pais) => {
    const selected = Number(seleccionado) === Number(pais.pais_id) ? 'selected' : '';
    opciones += `<option value="${pais.pais_id}" ${selected}>${escaparHtml(pais.nombre)}</option>`;
  });

  document.querySelector(selector).innerHTML = opciones;
}

function abrirModalPais() {
  mostrarSeccion('pais');
  document.getElementById('titulo_pais').textContent = 'Crear País';
  document.getElementById('pais_id').value = '';
  document.getElementById('pais_nombre').value = '';
  document.getElementById('pais_nombre').focus();
}

function cancelarFormularioPais() {
  document.getElementById('form_pais').reset();
  document.getElementById('pais_id').value = '';
  document.getElementById('titulo_pais').textContent = 'Crear País';
  mostrarSeccion('pais');
}

async function editarPais(pais_id) {
  try {
    const respuesta = await peticionAjax(`index.php?modulo=pais&accion=obtener&pais_id=${pais_id}`);

    mostrarSeccion('pais');
    document.getElementById('titulo_pais').textContent = 'Editar País';
    document.getElementById('pais_id').value = respuesta.datos.pais_id;
    document.getElementById('pais_nombre').value = respuesta.datos.nombre;
    document.getElementById('pais_nombre').focus();
  } catch (error) {
    mostrarAlerta(mensajeError(error), 'danger');
  }
}

async function guardarPais(evento) {
  evento.preventDefault();

  const pais_id = Number(document.getElementById('pais_id').value);
  const datos = {
    nombre: document.getElementById('pais_nombre').value.trim()
  };
  let accion = 'guardar';

  if (pais_id > 0) {
    datos.pais_id = pais_id;
    accion = 'actualizar';
  }

  try {
    const respuesta = await peticionAjax(
      `index.php?modulo=pais&accion=${accion}`,
      'POST',
      datos
    );

    cancelarFormularioPais();
    mostrarAlerta(respuesta.mensaje);
    await cargarTodo();
  } catch (error) {
    mostrarAlerta(mensajeError(error), 'danger');
  }
}

async function eliminarPais(pais_id) {
  if (!confirm('¿Desea eliminar este país?')) {
    return;
  }

  try {
    const respuesta = await peticionAjax(
      'index.php?modulo=pais&accion=eliminar',
      'POST',
      {pais_id}
    );

    mostrarAlerta(respuesta.mensaje);
    await cargarTodo();
  } catch (error) {
    mostrarAlerta(mensajeError(error), 'danger');
  }
}

async function listarDepartamentos() {
  try {
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

    document.getElementById('tabla_departamentos').innerHTML = filas
      || '<tr><td colspan="4" class="text-center text-secondary py-4">Sin registros.</td></tr>';
  } catch (error) {
    mostrarAlerta(mensajeError(error), 'danger');
  }
}

async function cargarDepartamentosCombo(pais_id, seleccionado = null) {
  const selector = document.getElementById('ciudad_departamento_id');

  if (!pais_id) {
    selector.innerHTML = '<option value="">-- Selecciona primero un país --</option>';
    return;
  }

  const respuesta = await peticionAjax(
    `index.php?modulo=departamento&accion=listar&pais_id=${pais_id}`
  );
  let opciones = '<option value="">-- Selecciona un departamento --</option>';

  respuesta.datos.forEach((departamento) => {
    const selected = Number(seleccionado) === Number(departamento.departamento_id)
      ? 'selected'
      : '';

    opciones += `<option value="${departamento.departamento_id}" ${selected}>${escaparHtml(departamento.nombre)}</option>`;
  });

  selector.innerHTML = opciones;
}

async function abrirModalDepartamento() {
  mostrarSeccion('departamento');
  document.getElementById('titulo_departamento').textContent = 'Crear Departamento';
  document.getElementById('departamento_id').value = '';
  document.getElementById('departamento_nombre').value = '';

  try {
    await cargarPaisesCombo('#departamento_pais_id');
    document.getElementById('departamento_pais_id').focus();
  } catch (error) {
    mostrarAlerta(mensajeError(error), 'danger');
  }
}

function cancelarFormularioDepartamento() {
  document.getElementById('form_departamento').reset();
  document.getElementById('departamento_id').value = '';
  document.getElementById('departamento_pais_id').innerHTML = '';
  document.getElementById('titulo_departamento').textContent = 'Crear Departamento';
  mostrarSeccion('pais');
}

async function editarDepartamento(departamento_id) {
  try {
    const respuesta = await peticionAjax(
      `index.php?modulo=departamento&accion=obtener&departamento_id=${departamento_id}`
    );

    mostrarSeccion('departamento');
    document.getElementById('titulo_departamento').textContent = 'Editar Departamento';
    document.getElementById('departamento_id').value = respuesta.datos.departamento_id;
    document.getElementById('departamento_nombre').value = respuesta.datos.nombre;

    await cargarPaisesCombo(
      '#departamento_pais_id',
      respuesta.datos.pais_id
    );

    document.getElementById('departamento_pais_id').focus();
  } catch (error) {
    mostrarAlerta(mensajeError(error), 'danger');
  }
}

async function guardarDepartamento(evento) {
  evento.preventDefault();

  const departamento_id = Number(document.getElementById('departamento_id').value);
  const datos = {
    pais_id: Number(document.getElementById('departamento_pais_id').value),
    nombre: document.getElementById('departamento_nombre').value.trim()
  };
  let accion = 'guardar';

  if (departamento_id > 0) {
    datos.departamento_id = departamento_id;
    accion = 'actualizar';
  }

  try {
    const respuesta = await peticionAjax(
      `index.php?modulo=departamento&accion=${accion}`,
      'POST',
      datos
    );

    cancelarFormularioDepartamento();
    mostrarAlerta(respuesta.mensaje);
    await cargarTodo();
  } catch (error) {
    mostrarAlerta(mensajeError(error), 'danger');
  }
}

async function eliminarDepartamento(departamento_id) {
  if (!confirm('¿Desea eliminar este departamento?')) {
    return;
  }

  try {
    const respuesta = await peticionAjax(
      'index.php?modulo=departamento&accion=eliminar',
      'POST',
      {departamento_id}
    );

    mostrarAlerta(respuesta.mensaje);
    await cargarTodo();
  } catch (error) {
    mostrarAlerta(mensajeError(error), 'danger');
  }
}

async function listarCiudades() {
  try {
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

    document.getElementById('tabla_ciudades').innerHTML = filas
      || '<tr><td colspan="5" class="text-center text-secondary py-4">Sin registros.</td></tr>';
  } catch (error) {
    mostrarAlerta(mensajeError(error), 'danger');
  }
}

async function abrirModalCiudad() {
  mostrarSeccion('ciudad');
  document.getElementById('titulo_ciudad').textContent = 'Crear Ciudad';
  document.getElementById('ciudad_id').value = '';
  document.getElementById('ciudad_nombre').value = '';

  try {
    await cargarPaisesCombo('#ciudad_pais_id');
    document.getElementById('ciudad_departamento_id').innerHTML = '<option value="">-- Selecciona primero un país --</option>';
    document.getElementById('ciudad_pais_id').focus();
  } catch (error) {
    mostrarAlerta(mensajeError(error), 'danger');
  }
}

function cancelarFormularioCiudad() {
  document.getElementById('form_ciudad').reset();
  document.getElementById('ciudad_id').value = '';
  document.getElementById('ciudad_pais_id').innerHTML = '';
  document.getElementById('ciudad_departamento_id').innerHTML = '';
  document.getElementById('titulo_ciudad').textContent = 'Crear Ciudad';
  mostrarSeccion('pais');
}

async function editarCiudad(ciudad_id) {
  try {
    const respuesta = await peticionAjax(
      `index.php?modulo=ciudad&accion=obtener&ciudad_id=${ciudad_id}`
    );

    mostrarSeccion('ciudad');
    document.getElementById('titulo_ciudad').textContent = 'Editar Ciudad';
    document.getElementById('ciudad_id').value = respuesta.datos.ciudad_id;
    document.getElementById('ciudad_nombre').value = respuesta.datos.nombre;

    await cargarPaisesCombo(
      '#ciudad_pais_id',
      respuesta.datos.pais_id
    );

    await cargarDepartamentosCombo(
      respuesta.datos.pais_id,
      respuesta.datos.departamento_id
    );

    document.getElementById('ciudad_pais_id').focus();
  } catch (error) {
    mostrarAlerta(mensajeError(error), 'danger');
  }
}

async function guardarCiudad(evento) {
  evento.preventDefault();

  const ciudad_id = Number(document.getElementById('ciudad_id').value);
  const datos = {
    departamento_id: Number(document.getElementById('ciudad_departamento_id').value),
    nombre: document.getElementById('ciudad_nombre').value.trim()
  };
  let accion = 'guardar';

  if (ciudad_id > 0) {
    datos.ciudad_id = ciudad_id;
    accion = 'actualizar';
  }

  try {
    const respuesta = await peticionAjax(
      `index.php?modulo=ciudad&accion=${accion}`,
      'POST',
      datos
    );

    cancelarFormularioCiudad();
    mostrarAlerta(respuesta.mensaje);
    await cargarTodo();
  } catch (error) {
    mostrarAlerta(mensajeError(error), 'danger');
  }
}

async function eliminarCiudad(ciudad_id) {
  if (!confirm('¿Desea eliminar esta ciudad?')) {
    return;
  }

  try {
    const respuesta = await peticionAjax(
      'index.php?modulo=ciudad&accion=eliminar',
      'POST',
      {ciudad_id}
    );

    mostrarAlerta(respuesta.mensaje);
    await cargarTodo();
  } catch (error) {
    mostrarAlerta(mensajeError(error), 'danger');
  }
}
