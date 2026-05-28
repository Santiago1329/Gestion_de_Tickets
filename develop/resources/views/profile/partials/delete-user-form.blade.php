<h5 class="card-title fw-bold text-danger mb-1">Eliminar cuenta</h5>
<p class="text-muted small mb-4">Una vez eliminada,todos tus datos seran borrados permanentemente.</p>

<!-- Boton modal -->
<button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#modalEliminarCuenta">
    <i class="fa-solid fa-trash me-2"></i> Eliminar mi cuenta
</button>

<!-- Modal de confirmacion -->
<div class="modal fade" id="modalEliminarCuenta" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger">¿Estás seguro?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <p class="text-muted">
                    Esta acción no se puede deshacer. Ingresa tu contraseña para confirmar
                    que deseas eliminar tu cuenta permanentemente.
                </p>

                <form method="post" action="{{ route('profile.destroy') }}" id="formEliminarCuenta">
                    @csrf
                    @method('delete')

                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Contraseña</label>
                        <input 
                            type="password"
                            id="delete_password"
                            name="password"
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                            placeholder="Ingresa tu contraseña"
                            required
                        >
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0">
                <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formEliminarCuenta" class="btn btn-danger">
                    <i class="fa-solid fa-trash me-2"></i> Si, eliminar mi cuenta
                </button>
            </div>
        </div>
    </div>
</div>
