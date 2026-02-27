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