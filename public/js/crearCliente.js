const tipoDocHidden = document.getElementById('tipo-doc-hidden');
const epsIdHidden = document.getElementById('eps-id-hidden');
const tipoDocInput = document.getElementById('tipo-doc-input');
const epsInput = document.getElementById('eps-input');
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

document.querySelector('form').addEventListener('submit', (e) => {
    e.preventDefault();
    let valido = true;

    if (!tipoDocHidden.value) {
        tipoDocInput.style.backgroundColor = '#ffcccc'; 
        valido = false;
    }

    if (!epsIdHidden.value) {
        epsInput.style.backgroundColor = '#ffcccc'; 
        valido = false;
    }

    if (!valido) return; 

    e.target.submit();
});