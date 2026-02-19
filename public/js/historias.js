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

        dienteActual = dientes.find(d => d.nombre == dienteSeleccionado);

        document.getElementById('diente-Actual-nombre').textContent = dienteActual.nombre;

        popup.classList.remove('hidden');

        historiasFiltradas()
    });
});

document.querySelectorAll('.boton-cerrar-pop-up').forEach(boton => {
    boton.addEventListener('click', () => {
        document.querySelectorAll('.contenedor-pop-up').forEach(p => p.classList.add('hidden'));
    });
});

function historiasFiltradas() {
    cuerpo.innerHTML = '';

    const filtradas = historias.filter(h =>
        h.diente_id == dienteActual.id ||
        (h.diente_id == 1 && dienteActual.id != 1 && dienteActual.id != 2)) .sort((a, b) => new Date(b.fecha) - new Date(a.fecha));;

    filtradas.forEach(h => {
        const clone = template.content.cloneNode(true);

        const fecha = new Date(h.fecha);
        const fechaFormateada = fecha.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });

        clone.querySelector('.fecha-tarjeta').textContent = fechaFormateada;
        clone.querySelector('.especialista-tarjeta').textContent = h.especialista.nombre;
        clone.querySelector('.obserbacion-tarjeta').textContent = h.observacion;
        cuerpo.appendChild(clone);
    });
}




botonNuevaHistoria.addEventListener('click', () => {
    popupNuevas.classList.remove('hidden');

});