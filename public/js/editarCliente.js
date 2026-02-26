document.addEventListener('DOMContentLoaded', () => {
    const tipoDocHidden = document.getElementById('tipo-doc-hidden');
    const epsIdHidden = document.getElementById('eps-id-hidden');
    const tipoDocInput = document.getElementById('tipo-doc-input');
    const epsInput = document.getElementById('eps-input');
    const numDoc = document.getElementById('num-doc');

    tipoDocInput.addEventListener('change', () => {
        const encontrado = tipoDocumentos.find(t => t.nombre === tipoDocInput.value);
        tipoDocHidden.value = encontrado ? encontrado.id : '';
        tipoDocInput.style.backgroundColor = encontrado ? '' : '#ef5252';
    });

    epsInput.addEventListener('change', () => {
        const encontrado = epsData.find(e => e.nombre === epsInput.value);
        epsIdHidden.value = encontrado ? encontrado.id : '';
        epsInput.style.backgroundColor = encontrado ? '' : '#ef5252';
    });

    document.getElementById('form-editar-cliente').addEventListener('submit', async (e) => {
        e.preventDefault();
        let valido = true;

        if (!tipoDocHidden.value) {
            tipoDocInput.style.backgroundColor = '#ef5252';
            valido = false;
        }

        if (!epsIdHidden.value) {
            epsInput.style.backgroundColor = '#ef5252';
            valido = false;
        }

        if (!valido) return;

        // Verificar si el documento ya existe (excluyendo el cliente actual)
        const res = await fetch(`/clientes/verificar-documento/${numDoc.value}?clienteId=${clienteId}`);
        const data = await res.json();

        if (data.existe) {
            alert('El número de documento ya existe');
            numDoc.style.backgroundColor = '#ef5252';
            numDoc.focus();
            return;
        }

        HTMLFormElement.prototype.submit.call(e.target);
    });
});