<div class="dropdown">
    <button class="btn btn-sm btn-outline-secondary position-relative rounded-circle p-2"
        type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width:36px;height:36px;">
        <i class="fa-solid fa-bell" style="font-size:14px;"></i>
        @if($noLeidas > 0)
            <span class="badge-notif">{{ $noLeidas > 9 ? '9+' : $noLeidas }}</span>
        @endif
    </button>

    <ul class="dropdown-menu dropdown-menu-end notif-dropdown p-0">
        <li class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom"
            style="border-color: var(--color-border) !important;">
            <span class="fw-semibold small">Notificaciones</span>
            @if($noLeidas > 0)
                <button wire:click="marcarTodasLeidas" class="btn btn-sm p-0 border-0 text-primary"
                    style="font-size:0.75rem; background:none;">
                    Marcar todas leídas
                </button>
            @endif
        </li>

        <li class="notif-lista">
            @forelse($notificaciones as $notif)
                <button
                    wire:click="marcarLeida('{{ $notif->id }}')"
                    class="dropdown-item notif-item w-100 text-start {{ is_null($notif->read_at) ? 'notif-no-leida' : '' }}"
                >
                    <div class="d-flex gap-2 align-items-start">
                        <div class="notif-icono">
                            <i class="fa-solid {{ match($notif->data['tipo'] ?? '') {
                                'estado_actualizado' => 'fa-circle-check',
                                'nuevo_mensaje' => 'fa-comment-dots',
                                'nuevo_ticket' => 'fa-ticket',
                                'cambio_estado_usuario' => 'fa-rotate',
                                default => 'fa-bell',
                            } }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="small" style="line-height:1.3;">{{ $notif->data['mensaje'] ?? '' }}</div>
                            <div class="notif-fecha">{{ $notif->created_at->diffForHumans() }}</div>
                        </div>
                        @if(is_null($notif->read_at))
                            <div class="notif-punto"></div>
                        @endif
                    </div>
                </button>
            @empty
                <div class="text-center text-muted small py-4">
                    <i class="fa-regular fa-bell-slash mb-2 d-block" style="font-size:1.4rem; opacity:0.4;"></i>
                    No tienes notificaciones
                </div>
            @endforelse
        </li>
    </ul>
</div>