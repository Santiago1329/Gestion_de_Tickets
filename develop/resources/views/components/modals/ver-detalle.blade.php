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
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="text-muted small text-uppercase">Codigo</div>
                            <div class="fw-bold font-monospace">TIC-{{ str_pad($ticketDetalle->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small text-uppercase">Fecha</div>
                            <div class="fw-semibold">
                                @if($ticketDetalle->created_at)
                                    {{ $ticketDetalle->created_at->format('d/m/Y h:i A') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small text-uppercase">Usuario</div>
                            <div class="fw-semibold">{{ $ticketDetalle->user->name }}</div>
                            <div class="text-muted small">{{ $ticketDetalle->user->area ?? 'Sin área' }}</div>
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
                            <div class="text-muted small text-uppercase mb-2">Contacto</div>
                            <div class="p-3 bg-body-secondary rounded d-flex flex-wrap gap-3">
                                <a href="mailto:{{ $ticketDetalle->user->email }}"
                                    class="btn btn-sm btn-outline-secondary">
                                    <i class="fa-solid fa-envelope me-1"></i> {{ $ticketDetalle->user->email }}
                                </a>
                                @if($ticketDetalle->user->telefono)
                                    <a href="https://wa.me/57{{ preg_replace('/\D/', '', $ticketDetalle->user->telefono) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-success">
                                        <i class="fa-brands fa-whatsapp me-1"></i> {{ $ticketDetalle->user->telefono }}
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small text-uppercase mb-1">Descripción</div>
                            <div class="p-3 bg-body-secondary rounded small">{{ $ticketDetalle->descripcion }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small text-uppercase">Estado</div>
                            <span class="badge-estado
                                @if($ticket->estado == 'abierto') badge-abierto
                                @elseif($ticket->estado == 'en_proceso') badge-en-proceso
                                @elseif($ticket->estado == 'resuelto') badge-resuelto
                                @elseif($ticket->estado == 'cancelado') badge-cancelado
                                @elseif($ticket->estado == 're_abierto') badge-re-abierto
                                @endif">
                                {{ str_replace('_', ' ', $ticket->estado) }}
                            </span>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small text-uppercase">Prioridad</div>
                            <span class="badge-estado
                                @if($ticket->prioridad == 'alta') badge-alta
                                @elseif($ticket->prioridad == 'media') badge-media
                                @elseif($ticket->prioridad == 'baja') badge-baja
                                @endif">
                                {{ $ticket->prioridad }}
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