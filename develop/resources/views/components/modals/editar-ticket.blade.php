<div class="modal fade" id="modalEditar" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header border-bottom border-2" style="border-color: var(--color-primary) !important;">
                <h5 class="modal-title fw-bold">Editar Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Estado</label>
                    <select wire:model="editarEstado"
                        class="form-select form-select-sm @error('editarEstado') is-invalid @enderror">
                        @foreach($estadosDisponibles as $estado)
                            <option value="{{ $estado }}" @selected($editarEstado == $estado)>
                                {{ ucfirst(str_replace('_', ' ', $estado)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('editarEstado') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-0">
                    <label class="form-label fw-semibold small">Prioridad</label>
                    <select wire:model="editarPrioridad"
                        class="form-select form-select-sm @error('editarPrioridad') is-invalid @enderror">
                        <option value="baja" @selected($editarPrioridad == 'baja')>Baja</option>
                        <option value="media" @selected($editarPrioridad == 'media')>Media</option>
                        <option value="alta" @selected($editarPrioridad == 'alta')>Alta</option>
                    </select>
                    @error('editarPrioridad') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button wire:click="guardarEdicion" class="btn btn-primary fw-bold"
                    wire:loading.attr="disabled" wire:target="guardarEdicion">
                    <span wire:loading.remove wire:target="guardarEdicion">Guardar</span>
                    <span wire:loading wire:target="guardarEdicion">
                        <span class="spinner-border spinner-border-sm me-1"></span> Guardando...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>