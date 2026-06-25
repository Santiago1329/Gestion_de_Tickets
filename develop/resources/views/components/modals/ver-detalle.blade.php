<div class="modal fade" id="modalDetalle" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-eye me-2 text-secondary"></i> Detalle del Ticket
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($ticketDetalle)
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-muted small text-uppercase">ID</div>
                            <div class="fw-bold font-monospace">#{{ $ticketDetalle->id }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small text-uppercase">Fecha</div>
                            <div class="fw-semibold">{{ $ticketDetalle->created_at->format('d/m/Y h:i A') }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small text-uppercase">Usuario</div>
                            <div class="fw-semibold">{{ $ticketDetalle->user->name }}</div>
                            <div class="text-muted small">{{ $ticketDetalle->user->email }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small text-uppercase">Categoría</div>
                            <div class="fw-semibold">{{ $ticketDetalle->categoria->nombre ?? '—' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small text-uppercase">Título</div>
                            <div class="fw-bold">{{ $ticketDetalle->titulo }}</div>
                        </div>
                        @if($ticketDetalle->archivo_adjunto)
                            <div class="col-6">
                                <div class="text-muted small text-uppercase mb-1">Adjunto</div>
                                <a href="{{ asset('storage/' . $ticketDetalle->archivo_adjunto) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-secondary">
                                    <i class="fa-solid fa-paperclip me-1"></i> Ver adjunto
                                </a>
                            </div>
                        @endif
                        <div class="col-12">
                            <div class="text-muted small text-uppercase mb-1">Descripción</div>
                            <div class="p-3 bg-body-secondary rounded small">{{ $ticketDetalle->descripcion }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small text-uppercase">Estado</div>
                            <span class="badge text-uppercase mt-1
                                @if($ticketDetalle->estado == 'abierto') bg-secondary-subtle text-secondary border border-secondary
                                @elseif($ticketDetalle->estado == 'en_proceso') bg-primary-subtle text-primary border border-primary
                                @elseif($ticketDetalle->estado == 'resuelto') bg-success-subtle text-success border border-success
                                @elseif($ticketDetalle->estado == 'cancelado') bg-danger-subtle text-danger border border-danger
                                @elseif($ticketDetalle->estado == 're_abierto') bg-warning-subtle text-warning border border-warning
                                @endif">
                                {{ str_replace('_', ' ', $ticketDetalle->estado) }}
                            </span>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small text-uppercase">Prioridad</div>
                            <span class="badge text-uppercase mt-1
                                @if($ticketDetalle->prioridad == 'alta') bg-danger-subtle text-danger border border-danger
                                @elseif($ticketDetalle->prioridad == 'media') bg-warning-subtle text-warning border border-warning
                                @elseif($ticketDetalle->prioridad == 'baja') bg-success-subtle text-success border border-success
                                @endif">
                                {{ $ticketDetalle->prioridad }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>