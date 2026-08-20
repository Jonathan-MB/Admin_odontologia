const botonGuardarFactura = document.getElementById('boton-guardar-factura');

botonGuardarFactura.addEventListener('click', async () => {

    botonGuardarFactura.disabled = true;
    botonGuardarFactura.textContent = 'Guardando...';

    const nombreInput = document.getElementById('nombre-input').value.trim();
    const especialistaId = document.getElementById('especialista-input').value;
    const metodoPagoId = document.getElementById('metodo-pago-input').value;
    const abonoInput = parseFloat(document.getElementById('abono-input').value) || 0;
    const saldoInput = parseFloat(document.getElementById('saldo-input').value) || 0;

    if (!nombreInput) {
        alert('El nombre es obligatorio');
        botonGuardarFactura.disabled = false;
        botonGuardarFactura.textContent = 'Guardar / Imprimir';
        return;
    }

    if (!especialistaId) {
        alert('Debes seleccionar un especialista');
        botonGuardarFactura.disabled = false;
        botonGuardarFactura.textContent = 'Guardar / Imprimir';
        return;
    }

    if (!metodoPagoId) {
        alert('Debes seleccionar un metodo de pago');
        botonGuardarFactura.disabled = false;
        botonGuardarFactura.textContent = 'Guardar / Imprimir';
        return;
    }

    const agendarCita = document.getElementById('mostrar-fecha-cita-id').value;
    const fechaCita = agendarCita == 2
        ? document.querySelector('input[type="datetime-local"]').value
        : null;

    if (agendarCita == 2 && !fechaCita) {
        alert('Debes ingresar la fecha de la próxima cita');
        botonGuardarFactura.disabled = false;
        botonGuardarFactura.textContent = 'Guardar / Imprimir';
        return;
    }

    const saldoCliente = parseFloat(clienteSaldo);
    const saldoFinal = saldoCliente + saldoInput - abonoInput;

    if (saldoFinal < 0) {
        alert('El saldo no puede ser negativo');
        botonGuardarFactura.disabled = false;
        botonGuardarFactura.textContent = 'Guardar / Imprimir';
        return;
    }

    try {
        const response = await fetch('/facturas/guardar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                clienteId,
                especialistaId,
                metodoPagoId,
                sedeId,
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
            botonGuardarFactura.disabled = false;
            botonGuardarFactura.textContent = 'Guardar / Imprimir';
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

            document.getElementById('factura-numero').textContent       = String(data.noFactura).padStart(4, '0');
            document.getElementById('factura-fecha').textContent        = ahora.toLocaleDateString('es-ES');
            document.getElementById('factura-hora').textContent         = ahora.toLocaleTimeString('es-ES');
            document.getElementById('factura-nombre').textContent       = nombreInput;
            document.getElementById('factura-especialista').textContent = especialistaNombre;
            document.getElementById('factura-abono').textContent        = '$ ' + abonoInput.toLocaleString('es-CO');
            document.getElementById('factura-saldo').textContent        = '$ ' + saldoFinal.toLocaleString('es-CO');
            document.getElementById('factura-proxima-cita').textContent = fechaCitaFormateada;

            const facturaClone = document.getElementById('factura-imprimible').cloneNode(true);
            facturaClone.classList.remove('hidden');

            const estilos = Array.from(document.querySelectorAll('link[rel="stylesheet"]'))
                .map(link => link.outerHTML)
                .join('');

            const blob = new Blob([`
                <html>
                <head>
                    <meta charset="UTF-8">
                    <base href="${window.location.origin}/">
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
                    ${facturaClone.outerHTML}
                </body>
                </html>
            `], { type: 'text/html; charset=utf-8' });

            const url = URL.createObjectURL(blob);
            const ventana = window.open(url, '_blank');

            ventana.addEventListener('load', () => {
                ventana.document.fonts.ready.then(() => {
                    ventana.focus();
                    ventana.print();
                });
            });

            // 👇 afterprint fuera del fonts.ready para que se registre a tiempo
            ventana.addEventListener('afterprint', () => {
                ventana.close();
                URL.revokeObjectURL(url);
                window.location.reload();
            });
        }

    } catch (error) {
        console.error(error);
        alert('Error inesperado');
        botonGuardarFactura.disabled = false;
        botonGuardarFactura.textContent = 'Guardar / Imprimir';
    }
});