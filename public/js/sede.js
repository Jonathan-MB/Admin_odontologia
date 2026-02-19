document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.seleccion-sede').forEach(boton => {
        boton.addEventListener('click', function () {

            fetch('/guardar-sede', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                },
                body: JSON.stringify({
                    id: this.dataset.id,
                    nombre: this.dataset.nombre
                })
            })
                .then(res => res.json())
                .then(data => {
                    window.location.href = "/";
                });

        });
    });

});
