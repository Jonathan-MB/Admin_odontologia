const botones = document.querySelectorAll('.diente');
const popup = document.getElementById('pop-up-historias-diente');
const botonNuevaHistoria = document.getElementById('boton-pop-historia');
const popupNuevas = document.getElementById('pop-up-historias-nuevas');
const cuerpo = document.getElementById('cuerpo-historias');
const template = document.getElementById('template-historia');

let dienteActual = null; // variable accesible en toda la vista

botones.forEach(boton => {
    boton.addEventListener('click', () => {
        const dienteSeleccionado = boton.dataset.diente;

        if (dienteSeleccionado === 'Todas') {
            dienteActual = { nombre: 'Todas', id: null }; // ← objeto especial
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



botonNuevaHistoria.addEventListener('click', () => {
    popupNuevas.classList.remove('hidden');

});