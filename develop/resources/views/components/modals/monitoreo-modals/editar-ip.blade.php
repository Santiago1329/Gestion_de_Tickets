<div class="modal fade" id="modalEditarIp" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title fw-bold">Editar IP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form wire:submit.prevent="actualizarIp">
                <div class="modal-body">
                    <label class="form-label fw-semibold small">Nueva IP *</label>
                    <input type="text" wire:model="ipEditar"
                        placeholder="Ej: 192.168.1.10"
                        class="form-control form-control-sm @error('ipEditar') is-invalid @enderror">
                    @error('ipEditar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold"
                        wire:loading.attr="disabled" wire:target="actualizarIp">
                        <span wire:loading.remove wire:target="actualizarIp">Guardar cambios</span>
                        <span wire:loading wire:target="actualizarIp">
                            <span class="spinner-border spinner-border-sm me-1"></span> Guardando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
