const hoy = new Date().toISOString().split('T')[0];
document.getElementById('fecha').value = hoy;
document.getElementById('fecha').max = hoy;


document.querySelectorAll('.nombre-especialista').forEach(boton => {
    boton.addEventListener('click', () => {
        const nombre = boton.dataset.nombre;
        const facturas = JSON.parse(boton.dataset.facturas);

        document.getElementById('popup-nombre-especialista').textContent = nombre;

        const contenido = document.getElementById('popup-contenido');
        contenido.innerHTML = '';

        if (facturas.length === 0) {
            contenido.innerHTML = '<p>Sin facturas para esta fecha</p>';
        } else {
            facturas.forEach(factura => {
                const fechaObj = new Date(factura.created_at);
                const fecha = fechaObj.toLocaleDateString('es-ES') + ' ' + fechaObj.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
                contenido.innerHTML += `
                    <div class="factura-tarjeta">
                        <div class="columna-factura">
                            <p class="tarjeta-encabezado">Fecha - Hora</p>
                            <p class="tarjeta-obsevacion">${fecha}</p>
                        </div>
                        <div class="columna-factura">
                            <p class="tarjeta-encabezado">Nombre</p>
                            <p class="tarjeta-obsevacion">${factura.nombre}</p>
                        </div>
                        <div class="columna-factura">
                            <p class="tarjeta-encabezado">Abono</p>
                            <p class="tarjeta-obsevacion">$ ${Number(factura.abono).toLocaleString('es-CO')}</p>
                        </div>
                        <div class="columna-factura">
                            <p class="tarjeta-encabezado">No. Factura</p>
                            <p class="tarjeta-obsevacion">${String(factura.no_factura).padStart(4, '0')}</p>
                        </div>
                    </div>
                    <p class="tarjeta-separador">-------------------------------------------</p>
                `;
            });
        }

        document.getElementById('popup-facturas').classList.remove('hidden');
    });
});

document.querySelector('.boton-cerrar-pop-up').addEventListener('click', () => {
    document.getElementById('popup-facturas').classList.add('hidden');
});