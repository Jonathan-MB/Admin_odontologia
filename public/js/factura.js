const selectCita = document.getElementById('mostrar-fecha-cita-id');
const divCita = document.getElementById('proxima-cita-factura');
const botonNuevaHistoria = document.getElementById('boton-pop-historia');
const botonNuevaFactura = document.getElementById('boton-nueva-factura');
const botonNuevaFacturaCerrar = document.getElementById('cerrar-pop-up');
const popupNuevaFactura = document.getElementById('pop-up-nueva-factura');

botonNuevaFactura.addEventListener('click', () => {
    popupNuevaFactura.classList.remove('hidden');
});

selectCita.addEventListener('change', () => {
    if (selectCita.value == 2) {
        divCita.classList.remove('hidden');
    } else {
        divCita.classList.add('hidden');
    }
});

botonNuevaFacturaCerrar.addEventListener('click', () => {
    popupNuevaFactura.classList.add('hidden');
});


document.querySelectorAll('.Factura-antigua-imprimir').forEach(boton => {
    boton.addEventListener('click', () => {


        const numero = boton.dataset.numero;
        const nombre = boton.dataset.nombre;
        const abono = parseFloat(boton.dataset.abono);
        const saldo = parseFloat(boton.dataset.saldo);
        const fechaRaw = new Date(boton.dataset.fecha);
        const especialista = boton.dataset.especialista;
        const cita = boton.dataset.cita;

        // 👇 Buscar la sede de esta factura
        const facturaSede = sedes.find(s => s.id == boton.dataset.sedeId);


        document.getElementById('factura-numero').textContent = numero;
        document.getElementById('factura-fecha').textContent = fechaRaw.toLocaleDateString('es-ES');
        document.getElementById('factura-hora').textContent = fechaRaw.toLocaleTimeString('es-ES');
        document.getElementById('factura-nombre').textContent = nombre;
        document.getElementById('factura-especialista').textContent = especialista;
        document.getElementById('factura-abono').textContent = '$ ' + abono.toLocaleString('es-CO');
        document.getElementById('factura-saldo').textContent = '$ ' + saldo.toLocaleString('es-CO');
        document.getElementById('factura-proxima-cita').textContent = cita;

        const facturaClone = document.getElementById('factura-imprimible').cloneNode(true);
        facturaClone.classList.remove('hidden');

        // 👇 Reemplazar datos de sede en el clone si se encontró la sede
        if (facturaSede) {
            facturaClone.querySelector('.sede-datos-encabezado-factura-imprimir').textContent = facturaSede.nombre;
            facturaClone.querySelector('.nit-datos-encabezado-factura-imprimir').textContent = 'Nit - ' + facturaSede.nit;
            facturaClone.querySelectorAll('.direccion-items')[0].querySelector('p').textContent = facturaSede.direccion;
            facturaClone.querySelectorAll('.direccion-items')[1].querySelector('p').textContent = facturaSede.telefono;
            facturaClone.querySelectorAll('.direccion-items')[2].querySelector('p').textContent = facturaSede.celular;
        }

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

        // 👇 afterprint va directo en la ventana, no anidado dentro del fonts.ready
        ventana.addEventListener('afterprint', () => {
            ventana.close();
            URL.revokeObjectURL(url);
        });
    });
});

