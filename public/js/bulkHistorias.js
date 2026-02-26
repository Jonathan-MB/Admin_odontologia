const popupNuevasHistorias = document.getElementById('pop-up-historias-nuevas');
const botonGuardar = document.getElementById('boton-guardar-historias');
const fechaInput = popupNuevasHistorias.querySelector('input[name="fecha"]');
const especialistaSelect = popupNuevasHistorias.querySelector('select[name="especialistaId"]');

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
            dienteId: encontrado.id,
            observacion,
            fecha
        });
    });
console.log('valido:', valido, 'historias:', historias);
    if (!valido) return;

    if (historias.length === 0) {
        alert('No hay historias válidas para guardar');
        return;
    }

    console.log('enviando fetch...');

    fetch('/historias/bulk', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ historias })
    })
        .then(res => {
            console.log('Status:', res.status);
            return res.json();
        })
        .then(data => {
            console.log('Data:', data);
            if (data.message) {
                alert('El odontograma ha sido actualizado correctamente');
                window.location.reload();
            }
        })
        .catch(err => {
            alert('Error al guardar las historias');
            console.error(err);
        });
});