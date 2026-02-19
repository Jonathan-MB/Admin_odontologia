const botonGuardar = document.getElementById('boton-guardar-historias');

botonGuardar.addEventListener('click', () => {
    // Datos compartidos
    const fecha = document.querySelector('input[name="fecha"]').value;
    const especialistaId = document.querySelector('select[name="especialistaId"]').value;


    // Recoger todas las tarjetas
    const tarjetas = document.querySelectorAll('.agregar-historia-tarjeta');

    const historias = [];

    tarjetas.forEach(tarjeta => {
        const dienteId = tarjeta.querySelector('select[name="dienteId"]').value;
        const observacion = tarjeta.querySelector('textarea[name="observacion"]').value;

        // Ignorar tarjetas vacías
        if (!dienteId || !observacion.trim()) return;

        historias.push({
            clienteId,
            especialistaId,
            dienteId,
            observacion,
            fecha
        });
    });

    if (historias.length === 0) {
        alert('No hay historias válidas para guardar');
        return;
    }

    fetch('/historias/bulk', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ historias })
    })
        .then(res => res.json())
        .then(data => {
            if (data.message) {
                window.location.reload(); // ← solo recarga si fue exitoso
            }
        })
        .catch(err => {
            alert('Error al guardar las historias'); // ← avisa si falló
            console.error(err);
        });
});