// ============ VARIABLES GLOBALES ============
let dienteActual = null;
let haycambios = false;

// ============ ELEMENTOS ============
const botones = document.querySelectorAll('.diente');
const popup = document.getElementById('pop-up-historias-diente');
const popupNuevasHistorias = document.getElementById('pop-up-historias-nuevas');
const cuerpo = document.getElementById('cuerpo-historias');
const template = document.getElementById('template-historia');
const botonGuardar = document.getElementById('boton-guardar-historias');
const fechaInput = popupNuevasHistorias.querySelector('input[name="fecha"]');
const especialistaSelect = popupNuevasHistorias.querySelector('select[name="especialistaId"]');


// ============ POPUP HISTORIAS DIENTE ============
botones.forEach(boton => {
    boton.addEventListener('click', () => {
        const dienteSeleccionado = boton.dataset.diente;

        if (dienteSeleccionado === 'Todas') {
            dienteActual = { nombre: 'Todas', id: null };
        } else {
            dienteActual = dientes.find(d => d.nombre == dienteSeleccionado);
        }

        document.getElementById('diente-Actual-nombre').textContent = dienteActual.nombre;
        popup.classList.remove('hidden');
        historiasFiltradas();
    });
});

document.querySelectorAll('.boton-cerrar-pop-up').forEach(boton => {
    boton.addEventListener('click', () => {
        document.querySelectorAll('.contenedor-pop-up').forEach(p => p.classList.add('hidden'));
    });
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.contenedor-pop-up').forEach(p => p.classList.add('hidden'));
    }
});

function historiasFiltradas() {
    cuerpo.innerHTML = '';

    const filtradas = dienteActual.nombre === 'Todas' ? [...historias]
        : historias.filter(h =>
            h.diente_id == dienteActual.id ||
            (h.diente_id == 1 && dienteActual.id != 1 && dienteActual.id != 2)
        );

    if (filtradas.length === 0) {
        cuerpo.innerHTML = '<p class="sin-historias">Sin Historias</p>';
        return;
    }

    filtradas
        .sort((a, b) => new Date(b.fecha) - new Date(a.fecha))
        .forEach(h => {
            const clone = template.content.cloneNode(true);
            const [anio, mes, dia] = h.fecha.split('-');
            const fechaFormateada = `${dia}/${mes}/${anio}`;
            const codDiente = h.diente ? `# ${h.diente.nombre}` : 'No Aplica';

            clone.querySelector('.fecha-tarjeta').textContent = fechaFormateada;
            clone.querySelector('.especialista-tarjeta').textContent = h.especialista.nombre;
            clone.querySelector('.diente-tarjeta').textContent = codDiente;
            clone.querySelector('.obserbacion-tarjeta').textContent = h.observacion;
            cuerpo.appendChild(clone);
        });
}

// ============ POPUP NUEVAS HISTORIAS ============
fechaInput.addEventListener('change', () => {
    fechaInput.style.backgroundColor = fechaInput.value ? '' : '#ffcccc';
});

especialistaSelect.addEventListener('change', () => {
    especialistaSelect.style.backgroundColor = especialistaSelect.value ? '' : '#ffcccc';
});

document.addEventListener('change', (e) => {
    if (e.target.matches('input[name="dienteId"]')) {
        const encontrado = dientes.find(d => d.nombre === e.target.value);
        e.target.style.backgroundColor = encontrado ? '' : '#ffcccc';
    }
});

botonGuardar.addEventListener('click', async () => {
    const fecha = fechaInput.value;
    const especialistaId = especialistaSelect.value;

    let valido = true;

    if (!fecha) {
        fechaInput.style.backgroundColor = '#ffcccc';
        valido = false;
    }

    if (!especialistaId) {
        especialistaSelect.style.backgroundColor = '#ffcccc';
        valido = false;
    }

    const tarjetas = document.querySelectorAll('.agregar-historia-tarjeta');
    const historiasAGuardar = [];

    tarjetas.forEach(tarjeta => {
        const dienteInput = tarjeta.querySelector('input[name="dienteId"]');
        const observacion = tarjeta.querySelector('textarea[name="observacion"]').value;
        const encontrado = dientes.find(d => d.nombre === dienteInput.value);

        if (!encontrado) {
            dienteInput.style.backgroundColor = '#ffcccc';
            valido = false;
            return;
        }

        if (!observacion.trim()) return;

        dienteInput.style.backgroundColor = '';
        historiasAGuardar.push({
            clienteId,
            especialistaId,
            dienteId: encontrado.id,
            observacion,
            fecha
        });
    });

    if (!valido) return;

    if (historiasAGuardar.length === 0) {
        alert('No hay historias válidas para guardar');
        return;
    }

    const res = await fetch('/historias/bulk', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ historias: historiasAGuardar })
    });

    const data = await res.json();
    if (data.message) {
        alert('Historias guardadas correctamente');
        window.location.reload();
    }
});

// ============ ODONTOGRAMA ============
document.querySelectorAll('.contenedor-info-diente textarea').forEach(textarea => {
    textarea.addEventListener('input', () => {
        haycambios = true;
    });
});

window.addEventListener('beforeunload', (e) => {
    if (haycambios) {
        e.preventDefault();
        e.returnValue = '';
    }
});

document.getElementById('boton-guardar-odontograma').addEventListener('click', async () => {
    const especialistaSelectOdonto = document.getElementById('especialistaId');
    const especialistaIdOdonto = especialistaSelectOdonto.value;
    const fechaInputOdonto = document.getElementById('fechaOdontograma');
    const fechaOdonto = fechaInputOdonto.value;

    if (!especialistaIdOdonto) {
        especialistaSelectOdonto.style.backgroundColor = '#ef5252';
        alert('Selecciona un especialista');
        return;
    }
    especialistaSelectOdonto.style.backgroundColor = '';

    if (!fechaOdonto) {
        fechaInputOdonto.style.backgroundColor = '#ef5252';
        alert('Selecciona una fecha');
        return;
    }
    fechaInputOdonto.style.backgroundColor = '';

    const historiasOdonto = [];

    document.querySelectorAll('.contenedor-info-diente').forEach(contenedor => {
        const boton = contenedor.querySelector('button.diente');
        const textarea = contenedor.querySelector('textarea');
        const observacion = textarea.value.trim();
        const dienteNombre = boton.dataset.diente;
        const dienteObj = dientes.find(d => d.nombre == dienteNombre);

        if (!observacion || !dienteObj) return;

        const ultimaHistoria = ultimasHistorias[dienteObj.id];
        if (ultimaHistoria && ultimaHistoria.observacion === observacion) return;

        historiasOdonto.push({
            clienteId,
            especialistaId: especialistaIdOdonto,
            dienteId: dienteObj.id,
            observacion,
            fecha: fechaOdonto
        });
    });

    if (historiasOdonto.length === 0) {
        alert('No hay cambios para guardar');
        return;
    }

    const res = await fetch('/historias/bulk', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ historias: historiasOdonto })
    });

    const data = await res.json();
    if (data.message) {
        haycambios = false;
        alert('Odontograma guardado correctamente');
        window.location.reload();
    }
});