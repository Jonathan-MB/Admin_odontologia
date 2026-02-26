
const contenedorUsuario = document.getElementById('contenedor-usuario');
const menuUsuario = document.getElementById('menu-usuario');
const imagenUsuario = document.querySelector('.usuario-imagen');
const mensaje = document.getElementById('mensaje');
imagenUsuario.addEventListener('click', function (e) {
    e.stopPropagation();
    menuUsuario.classList.toggle('hidden');
});

document.addEventListener('click', function (e) {
    if (!contenedorUsuario.contains(e.target)) {
        menuUsuario.classList.add('hidden');
    }
});

function fetchPost(url, data) {
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    });
}


if (mensaje) {
    mensaje.style.transition = 'opacity 0.5s ease';
    setTimeout(function () {
        mensaje.style.opacity = '0';
        setTimeout(function () {
            mensaje.style.display = 'none';
        }, 500);
    }, 5000);
}