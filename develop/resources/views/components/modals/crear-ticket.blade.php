<div class="modal fade" id="modalCrearTicket" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-plus me-2 text-danger"></i> Nuevo Ticket
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form wire:submit.prevent="guardarTicket">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">¿Qué está fallando? *</label>
                        <input type="text" wire:model="titulo" wire:key="admin-titulo-{{ $tituloKey ?? 0 }}"
                            placeholder="Ej: No conecta la impresora"
                            class="form-control @error('titulo') is-invalid @enderror">
                        @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Categoría *</label>
                        <select wire:model="categoria_id" wire:key="admin-categoria-{{ $tituloKey ?? 0 }}"
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
                        <textarea wire:model="descripcion" rows="3" wire:key="admin-descripcion-{{ $tituloKey ?? 0 }}"
                            placeholder="Escribe los detalles aquí..."
                            class="form-control @error('descripcion') is-invalid @enderror"></textarea>
                        @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Prioridad *</label>
                        <select wire:model="prioridad" wire:key="admin-prioridad-{{ $tituloKey ?? 0 }}"
                            class="form-select @error('prioridad') is-invalid @enderror">
                            <option value="">-- Selecciona una opción --</option>
                            <option value="baja">Baja</option>
                            <option value="media">Media</option>
                            <option value="alta">Alta</option>
                        </select>
                        @error('prioridad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            <i class="fa-solid fa-paperclip me-1 text-muted"></i> Adjuntar Evidencia (Opcional)
                        </label>
                        <input type="file" wire:model="archivo_adjunto" wire:key="admin-archivo-{{ $tituloKey ?? 0 }}"
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
                    <button type="submit" class="btn btn-danger fw-bold" wire:loading.attr="disabled" wire:target="guardarTicket">
                        <span wire:loading.remove wire:target="guardarTicket">
                            <i class="fa-solid fa-paper-plane me-1"></i> Crear Ticket
                        </span>
                        <span wire:loading wire:target="guardarTicket">
                            <span class="spinner-border spinner-border-sm me-2"></span> Creando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>