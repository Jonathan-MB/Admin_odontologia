const botonMasHistorias = document.querySelector('.mas-historias');
const contenedorHistorias = document.querySelector('.cuerpo-historias-nuevas');

botonMasHistorias.addEventListener('click', () => {
    const nuevaTarjeta = document.createElement('div');
    nuevaTarjeta.classList.add('agregar-historia-tarjeta');

    // Construir opciones de dientes desde el array JS
    const opcionesDientes = dientes.map(d => 
        `<option value="${d.id}">${d.nombre}</option>`
    ).join('');

    nuevaTarjeta.innerHTML = `
        <div class="datos-historia">
            <label>Diente</label>
            <label>Observacion</label>
        </div>
        <div class="datos-historia">
            <input type="text" class="diente-nueva-historia" list="diente-list" name="dienteId" autocomplete="off">
            <textarea class="observaciones-nueva-historia" name="observacion"></textarea>
        </div>
    `;

    // Insertar antes del botón
    contenedorHistorias.insertBefore(nuevaTarjeta, botonMasHistorias);
});



