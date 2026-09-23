<div class="modal fade" id="modalAgregarDispositivo" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title fw-bold">Agregar dispositivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form wire:submit.prevent="agregarDispositivo">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nombre *</label>
                        <input type="text" wire:model="nombre"
                            placeholder="Ej: Servidor principal"
                            class="form-control form-control-sm @error('nombre') is-invalid @enderror">
                        @error('nombre')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">IP *</label>
                        <input type="text" wire:model="ip"
                            placeholder="Ej: 192.168.1.10"
                            class="form-control form-control-sm @error('ip') is-invalid @enderror">
                        @error('ip')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold small">Estado inicial</label>
                        <select wire:model="estado" class="form-select form-select-sm">
                            <option value="desconocido">Desconocido</option>
                            <option value="online">En línea</option>
                            <option value="offline">Caído</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold"
                        wire:loading.attr="disabled" wire:target="agregarDispositivo">
                        <span wire:loading.remove wire:target="agregarDispositivo">Agregar dispositivo</span>
                        <span wire:loading wire:target="agregarDispositivo">
                            <span class="spinner-border spinner-border-sm me-1"></span> Guardando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
