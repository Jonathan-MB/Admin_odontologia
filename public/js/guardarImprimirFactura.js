const botonGuardarFactura = document.getElementById('boton-guardar-factura');

botonGuardarFactura.addEventListener('click', async () => {

    const nombreInput = document.getElementById('nombre-input').value.trim();
    const especialistaId = document.getElementById('especialista-input').value;
    const abonoInput = parseFloat(document.getElementById('abono-input').value) || 0; // si está vacío es 0
    const saldoInput = parseFloat(document.getElementById('saldo-input').value) || 0; // si está vacío es 0

    // Validaciones
    if (!nombreInput) {
        alert('El nombre es obligatorio');
        return;
    }

    if (!especialistaId) {
        alert('Debes seleccionar un especialista');
        return;
    }

    const agendarCita = document.getElementById('mostrar-fecha-cita-id').value;
    const fechaCita = agendarCita == 2
        ? document.querySelector('input[type="datetime-local"]').value
        : null;

    if (agendarCita == 2 && !fechaCita) {
        alert('Debes ingresar la fecha de la próxima cita');
        return;
    }

    // Calcular saldo final
    const saldoCliente = parseFloat(clienteSaldo);
    const saldoFinal = saldoCliente + saldoInput - abonoInput;

    if (saldoFinal < 0) {
        alert('El saldo no puede ser negativo');
        return;
    }

    const response = await fetch('/facturas/guardar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            clienteId,
            especialistaId,
            nombre: nombreInput,
            abono: abonoInput,
            saldoInput,
            saldoFinal,
            fechaCita,
        })
    });

    if (!response.ok) {
        const text = await response.text();
        console.error(text);
        alert('Error al guardar');
        return;
    }

    const data = await response.json();
    if (data.success) {
        const ahora = new Date();
        const especialistaNombre = document.getElementById('especialista-input').options[document.getElementById('especialista-input').selectedIndex].text;

        const fechaCitaFormateada = fechaCita
            ? (() => {
                const [fecha, hora] = fechaCita.split('T');
                const [anio, mes, dia] = fecha.split('-');
                return `${dia}/${mes}/${anio} ${hora}`;
            })()
            : 'Sin cita';

        document.getElementById('factura-numero').textContent = String(data.noFactura).padStart(4, '0');
        document.getElementById('factura-fecha').textContent = ahora.toLocaleDateString('es-ES');
        document.getElementById('factura-hora').textContent = ahora.toLocaleTimeString('es-ES');
        document.getElementById('factura-nombre').textContent = nombreInput;
        document.getElementById('factura-especialista').textContent = especialistaNombre;
        document.getElementById('factura-abono').textContent = '$ ' + abonoInput.toLocaleString('es-CO');
        document.getElementById('factura-saldo').textContent = '$ ' + saldoFinal.toLocaleString('es-CO');
        document.getElementById('factura-proxima-cita').textContent = fechaCitaFormateada;

        const estilos = Array.from(document.querySelectorAll('link[rel="stylesheet"]'))
            .map(link => link.outerHTML)
            .join('');

        const ventana = window.open('', '_blank');
        ventana.document.write(`
        <html>
        <head>
            ${estilos}
            <style>
                @font-face {
                    font-family: "Breathing";
                    src: url("${fontBreathingUrl}") format("truetype");
                    font-weight: normal;
                    font-style: normal;
                }
                @page { margin: 5mm; size: auto; }
                body { margin: 0; }
            </style>
        </head>
        <body>
            ${document.getElementById('factura-imprimible').outerHTML}
        </body>
    </html>
    `);
        ventana.document.close();

        setTimeout(() => {
            ventana.focus();
            ventana.print();
            ventana.close();
            window.location.reload();
        }, 800);
    }
});