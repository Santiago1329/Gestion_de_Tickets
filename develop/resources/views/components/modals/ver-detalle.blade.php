<div class="modal fade" id="modalDetalle" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <!-- Header destacado -->
            <div class="modal-header border-bottom border-2" style="border-color: var(--color-primary) !important;">
                <div class="w-100">
                    <h5 class="modal-title fw-bold mb-1">{{ $ticketDetalle->titulo ?? 'Detalle del Ticket' }}</h5>
                    <small class="text-muted">TIC-{{ str_pad($ticketDetalle->id ?? 0, 4, '0', STR_PAD_LEFT) }}</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @if($ticketDetalle)
                    <!-- Usuario y Email -->
                    <div class="mb-3 pb-3" style="border-bottom: 1px solid var(--color-border);">
                        <small class="text-muted text-uppercase fw-semibold d-block mb-2">Usuario</small>
                        <div class="fw-bold mb-1" style="color: var(--color-text);">{{ $ticketDetalle->user->name }}</div>
                        <small class="text-muted">{{ $ticketDetalle->user->email }}</small>
                    </div>

                    <!-- Fecha y Categoría -->
                    <div class="row g-3 mb-3 pb-3" style="border-bottom: 1px solid var(--color-border);">
                        <div class="col-6">
                            <small class="text-muted text-uppercase fw-semibold d-block mb-1">Fecha</small>
                            <div class="small fw-semibold">
                                @if($ticketDetalle->created_at)
                                    {{ $ticketDetalle->created_at->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted text-uppercase fw-semibold d-block mb-1">Categoría</small>
                            <div class="small fw-semibold">{{ $ticketDetalle->categoria->nombre ?? '—' }}</div>
                        </div>
                    </div>

                    <!-- Estado y Prioridad -->
                    <div class="row g-3 mb-3 pb-3" style="border-bottom: 1px solid var(--color-border);">
                        <div class="col-6">
                            <small class="text-muted text-uppercase fw-semibold d-block mb-2">Estado</small>
                            <span class="badge-estado
                                @if($ticketDetalle->estado == 'abierto') badge-abierto
                                @elseif($ticketDetalle->estado == 'en_proceso') badge-en-proceso
                                @elseif($ticketDetalle->estado == 'resuelto') badge-resuelto
                                @elseif($ticketDetalle->estado == 'cancelado') badge-cancelado
                                @elseif($ticketDetalle->estado == 're_abierto') badge-re-abierto
                                @endif">
                                {{ str_replace('_', ' ', $ticketDetalle->estado) }}
                            </span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted text-uppercase fw-semibold d-block mb-2">Prioridad</small>
                            <span class="badge-estado
                                @if($ticketDetalle->prioridad == 'alta') badge-alta
                                @elseif($ticketDetalle->prioridad == 'media') badge-media
                                @elseif($ticketDetalle->prioridad == 'baja') badge-baja
                                @endif">
                                {{ $ticketDetalle->prioridad }}
                            </span>
                        </div>
                    </div>

                    <!-- Descripción Principal -->
                    <div class="mb-3">
                        <small class="text-muted text-uppercase fw-semibold d-block mb-2">Descripción</small>
                        <div class="p-2 rounded-2 lh-base" style="background-color: var(--color-primary-light);">{{ $ticketDetalle->descripcion }}</div>
                    </div>

                    <!-- Adjunto y Contacto -->
                    @if($ticketDetalle->archivo_adjunto || $ticketDetalle->telefono)
                        <div class="d-flex flex-wrap gap-2">
                            @if($ticketDetalle->archivo_adjunto)
                                <a href="{{ asset('storage/' . $ticketDetalle->archivo_adjunto) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-secondary">
                                    Ver adjunto
                                </a>
                            @endif
                            
                            @if($ticketDetalle->telefono)
                                <a href="https://wa.me/57{{ preg_replace('/\D/', '', $ticketDetalle->telefono) }}"
                                    target="_blank"
                                    class="btn btn-sm"
                                    style="background-color: var(--color-primary); color: white; border-color: var(--color-primary);">
                                    {{ $ticketDetalle->telefono }}
                                </a>
                            @endif
                        </div>
                    @endif
                @endif
            </div>

            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>