<div class="modal fade" id="modalCrearTicket" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title fw-bold">Nuevo Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form wire:submit.prevent="guardarTicket">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">¿Qué está fallando? *</label>
                        <input type="text" wire:model="titulo" 
                            wire:key="admin-titulo-{{ $tituloKey ?? 0 }}"
                            placeholder="Ej: No conecta la impresora"
                            class="form-control form-control-sm @error('titulo') is-invalid @enderror">
                        @error('titulo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Categoría *</label>
                            <select wire:model="categoria_id" 
                                wire:key="admin-categoria-{{ $tituloKey ?? 0 }}"
                                class="form-select form-select-sm @error('categoria_id') is-invalid @enderror">
                                <option value="">-- Selecciona --</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                @endforeach
                            </select>
                            @error('categoria_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Prioridad *</label>
                            <select wire:model="prioridad" 
                                wire:key="admin-prioridad-{{ $tituloKey ?? 0 }}"
                                class="form-select form-select-sm @error('prioridad') is-invalid @enderror">
                                <option value="">-- Selecciona --</option>
                                <option value="baja">Baja</option>
                                <option value="media">Media</option>
                                <option value="alta">Alta</option>
                            </select>
                            @error('prioridad') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Descripción *</label>
                        <textarea wire:model="descripcion" rows="3" 
                            wire:key="admin-descripcion-{{ $tituloKey ?? 0 }}"
                            placeholder="Describe el problema..."
                            class="form-control form-control-sm @error('descripcion') is-invalid @enderror"></textarea>
                        @error('descripcion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Teléfono (Opcional)</label>
                        <input type="text" wire:model="telefono"
                            wire:key="admin-telefono-{{ $tituloKey ?? 0 }}"
                            placeholder="Ej: 3001234567"
                            class="form-control form-control-sm @error('telefono') is-invalid @enderror">
                        @error('telefono') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold small">Adjuntar Evidencia (Opcional)</label>
                        <input type="file" wire:model="archivo_adjunto" 
                            wire:key="admin-archivo-{{ $tituloKey ?? 0 }}"
                            accept=".jpg,.jpeg,.png,.gif,.pdf"
                            class="form-control form-control-sm @error('archivo_adjunto') is-invalid @enderror">
                        <div wire:loading wire:target="archivo_adjunto" class="small text-success mt-2">
                            <span class="spinner-border spinner-border-sm me-1"></span> Subiendo...
                        </div>
                        @error('archivo_adjunto') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold" 
                        wire:loading.attr="disabled" wire:target="guardarTicket">
                        <span wire:loading.remove wire:target="guardarTicket">Crear Ticket</span>
                        <span wire:loading wire:target="guardarTicket">
                            <span class="spinner-border spinner-border-sm me-1"></span> Creando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>