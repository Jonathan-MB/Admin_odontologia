document.querySelectorAll('.btn-cliente').forEach(btn => {
    btn.addEventListener('click', () => {

        document.getElementById('form-agendar').action  = btn.dataset.url;
        document.getElementById('form-eliminar').action = btn.dataset.url;

        const nacimiento = new Date(btn.dataset.edad);
        const hoy = new Date();
        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const mes = hoy.getMonth() - nacimiento.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
            edad--;
        }

        document.getElementById('popup-nombre').textContent    = btn.dataset.nombre;
        document.getElementById('popup-telefono').textContent  = btn.dataset.telefono;
        document.getElementById('popup-edad').textContent      = edad;
        document.getElementById('popup-correo').textContent    = btn.dataset.correo;
        document.getElementById('popup-documento').textContent = btn.dataset.documento;

        //  Precargar la cita actual para no tener que reescribirla entera
        const inputDia  = document.getElementById('popup-fecha-dia');
        const inputHora = document.getElementById('popup-fecha-hora');
        const citaActual = btn.dataset.fecha;

        if (citaActual) {
            const [dia, hora] = citaActual.replace('T', ' ').split(' ');
            inputDia.value  = dia;
            inputHora.value = hora ? hora.slice(0, 5) : '';
        } else {
            inputDia.value  = '';
            inputHora.value = '';
        }

        // Enlaces a la ficha del paciente, para consultarlo o contactarlo
        document.getElementById('popup-historia').href    = btn.dataset.urlHistoria;
        document.getElementById('popup-ir-facturas').href = btn.dataset.urlFacturas;

        document.getElementById('popup-facturas').classList.remove('hidden');
    });
});

// ✅ id corregido
document.getElementById('cerrar-pop-up').addEventListener('click', () => {
    document.getElementById('popup-facturas').classList.add('hidden');
});

document.getElementById('form-agendar').addEventListener('submit', (e) => {
    e.preventDefault(); // detén primero

    const dia  = document.getElementById('popup-fecha-dia').value;
    const hora = document.getElementById('popup-fecha-hora').value;

    if (dia && hora) {
        document.getElementById('popup-fecha-hidden').value = `${dia}T${hora}`;
        e.target.submit(); // envía después de asignar
    } else {
        alert('Selecciona fecha y hora');
    }
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.getElementById('popup-facturas').classList.add('hidden');
    }
});

// Confirmar antes de eliminar: no había forma de deshacer
document.getElementById('form-eliminar').addEventListener('submit', (e) => {
    const nombre = document.getElementById('popup-nombre').textContent;

    if (!confirm('Eliminar la cita de ' + nombre + '?')) {
        e.preventDefault();
    }
});

// Panel de citas vencidas sin reagendar
const botonPendientes = document.getElementById('boton-pendientes');
const panelPendientes = document.getElementById('panel-pendientes');

if (botonPendientes && panelPendientes) {
    botonPendientes.addEventListener('click', () => {
        panelPendientes.classList.toggle('hidden');

        if (!panelPendientes.classList.contains('hidden')) {
            panelPendientes.scrollIntoView({ behavior: 'smooth' });
        }
    });
}

// ============ AGENDAR DESDE EL CALENDARIO ============

const popupNuevaCita = document.getElementById('popup-nueva-cita');

if (popupNuevaCita) {

    const botonNuevaCita   = document.getElementById('boton-nueva-cita');
    const buscarPaciente   = document.getElementById('buscar-paciente');
    const resultados       = document.getElementById('resultados-paciente');
    const pacienteElegido  = document.getElementById('paciente-elegido');
    const clienteId        = document.getElementById('nueva-cita-cliente');
    const formNuevaCita    = document.getElementById('form-nueva-cita');

    const abrir = () => popupNuevaCita.classList.remove('hidden');
    const cerrar = () => popupNuevaCita.classList.add('hidden');

    botonNuevaCita.addEventListener('click', abrir);
    document.getElementById('cerrar-nueva-cita').addEventListener('click', cerrar);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') cerrar();
    });

    //  Buscar paciente mientras se escribe, esperando a que deje de teclear
    let temporizador = null;

    buscarPaciente.addEventListener('input', () => {
        clearTimeout(temporizador);

        const texto = buscarPaciente.value.trim();

        if (texto.length < 3) {
            resultados.innerHTML = '';
            return;
        }

        temporizador = setTimeout(async () => {
            const res = await fetch('/agenda/clientes?q=' + encodeURIComponent(texto), {
                headers: { 'Accept': 'application/json' }
            });

            const clientes = await res.json();

            resultados.innerHTML = '';

            if (clientes.length === 0) {
                resultados.innerHTML = '<p class="sin-resultados">Sin coincidencias</p>';
                return;
            }

            clientes.forEach(cliente => {
                const fila = document.createElement('button');
                fila.type = 'button';
                fila.className = 'resultado-paciente';
                fila.textContent = cliente.nombre + '  ·  ' + cliente.documento;

                fila.addEventListener('click', () => {
                    clienteId.value = cliente.id;
                    pacienteElegido.textContent = 'Paciente: ' + cliente.nombre;
                    pacienteElegido.classList.remove('hidden');
                    resultados.innerHTML = '';
                    buscarPaciente.value = '';
                });

                resultados.appendChild(fila);
            });
        }, 300);
    });

    formNuevaCita.addEventListener('submit', (e) => {
        e.preventDefault();

        const dia  = document.getElementById('nueva-cita-dia').value;
        const hora = document.getElementById('nueva-cita-hora').value;

        if (!clienteId.value) {
            alert('Busca y elige un paciente');
            return;
        }

        if (!dia || !hora) {
            alert('Selecciona fecha y hora');
            return;
        }

        document.getElementById('nueva-cita-fecha-hora').value = dia + 'T' + hora;
        e.target.submit();
    });
}
