const botonGuardar = document.getElementById('boton-guardar-historias');
const fechaInput = document.querySelector('input[name="fecha"]');
const especialistaSelect = document.querySelector('select[name="especialistaId"]');

// Validación visual al cambiar
fechaInput.addEventListener('change', () => {
    fechaInput.style.backgroundColor = fechaInput.value ? '' : '#ffcccc';
});

especialistaSelect.addEventListener('change', () => {
    especialistaSelect.style.backgroundColor = especialistaSelect.value ? '' : '#ffcccc';
});

// Validación visual en dientes de cada tarjeta
document.addEventListener('change', (e) => {
    if (e.target.matches('input[name="dienteId"]')) {
        const encontrado = dientes.find(d => d.nombre === e.target.value);
        e.target.style.backgroundColor = encontrado ? '' : '#ffcccc';
    }
});

botonGuardar.addEventListener('click', () => {
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
    const historias = [];

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
        historias.push({
            clienteId,
            especialistaId,
            dienteId: encontrado.id, // ← id correcto
            observacion,
            fecha
        });
    });

    if (!valido) return;

    if (historias.length === 0) {
        alert('No hay historias válidas para guardar');
        return;
    }

    fetch('/historias/bulk', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ historias })
    })
        .then(res => res.json())
        .then(data => {
            if (data.message) {
                window.location.reload();
            }
        })
        .catch(err => {
            alert('Error al guardar las historias');
            console.error(err);
        });
});