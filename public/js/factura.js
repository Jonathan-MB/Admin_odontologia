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