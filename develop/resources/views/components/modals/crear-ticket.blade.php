<div class="modal fade" id="modalCrearTicket" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-plus me-2 text-danger"></i> Nuevo Ticket
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold small">¿Qué está fallando? *</label>
                    <input type="text" wire:model="titulo"
                        placeholder="Ej: No conecta la impresora"
                        class="form-control @error('titulo') is-invalid @enderror">
                    @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Categoría *</label>
                    <select wire:model="categoria_id"
                        class="form-select @error('categoria_id') is-invalid @enderror">
                        <option value="">-- Selecciona una opción --</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                    @error('categoria_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Descripción *</label>
                    <textarea wire:model="descripcion" rows="3"
                        placeholder="Escribe los detalles aquí..."
                        class="form-control @error('descripcion') is-invalid @enderror"></textarea>
                    @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold small">
                        <i class="fa-solid fa-paperclip me-1 text-muted"></i> Adjuntar Evidencia (Opcional)
                    </label>
                    <input type="file" wire:model="archivo_adjunto"
                        accept=".jpg,.jpeg,.png,.gif,.pdf"
                        class="form-control @error('archivo_adjunto') is-invalid @enderror">
                    <div wire:loading wire:target="archivo_adjunto" class="small text-primary mt-2">
                        <i class="fa-solid fa-spinner fa-spin me-1"></i> Subiendo archivo...
                    </div>
                    @error('archivo_adjunto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button wire:click="guardarTicket" class="btn btn-danger fw-bold">
                    <i class="fa-solid fa-paper-plane me-1"></i> Crear Ticket
                </button>
            </div>
        </div>
    </div>
</div>