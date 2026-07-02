<div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">

    <div id="toastExito" class="toast align-items-center border-0 text-white" role="alert" aria-live="assertive" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-check"></i>
                <span id="toastExitoMensaje"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>

    <div id="toastError" class="toast align-items-center border-0 text-white bg-danger" role="alert" aria-live="assertive" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-xmark"></i>
                <span id="toastErrorMensaje"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>

</div>

<script>
    window.addEventListener('mostrarToast', (e) => {
        const tipo = e.detail.tipo ?? 'exito';
        const mensaje = e.detail.mensaje ?? '';

        if (tipo === 'exito') {
            document.getElementById('toastExitoMensaje').textContent = mensaje;
            const toast = new bootstrap.Toast(document.getElementById('toastExito'));
            toast.show();
        } else {
            document.getElementById('toastErrorMensaje').textContent = mensaje;
            const toast = new bootstrap.Toast(document.getElementById('toastError'));
            toast.show();
        }
    });
</script>