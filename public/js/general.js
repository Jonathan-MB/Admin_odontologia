
    const contenedorUsuario = document.getElementById('contenedor-usuario');
    const menuUsuario = document.getElementById('menu-usuario');
    const imagenUsuario = document.querySelector('.usuario-imagen');

    imagenUsuario.addEventListener('click', function(e) {
        e.stopPropagation();
        menuUsuario.classList.toggle('hidden');
    });

    document.addEventListener('click', function(e) {
        if (!contenedorUsuario.contains(e.target)) {
            menuUsuario.classList.add('hidden');
        }
    });

