<div class="container-fluid">

    <div class="card-body d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold">Sistema de soporte TICs</h3>
            <p class="text-muted small mb-0">Bienvenido, {{ auth()->user()->name }} - Reporta y gestiona tus tickets.</p>
        </div>
        <span class="badge-role d-none d-sm-inline-block">{{ auth()->user()->rol }}</span>
    </div>

    <div class="row g-4">

        <div class="col-12 col-lg-5">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <h5 class="card-title fw-bold mb-4">Nuevo Ticket</h5>

                    <form wire:submit.prevent="guardarTicket" class="d-flex flex-column flex-grow-1">

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Titulo del ticket *</label>
                            <input type="text" wire:model="titulo" wire:key="titulo-{{ $tituloKey ?? 0 }}"
                                class="form-control @error('titulo') is-invalid @enderror"
                                placeholder="Ej: No funciona la impresora"
                            >
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Categoria *</label>
                            <select wire:model="categoria_id" wire:key="categoria-{{ $tituloKey ?? 0 }}"
                                class="form-select @error('categoria_id') is-invalid @enderror"
                            >
                                <option value="">-- Selecciona una categoria --</option>
                                @foreach ($categorias as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Descripción *</label>
                            <textarea wire:model="descripcion" wire:key="descripcion-{{ $tituloKey ?? 0 }}"
                                class="form-control @error('descripcion') is-invalid @enderror"
                                placeholder="Escribe los detalles aqui..." rows="4"></textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Titulo del ticket *</label>
                            <input type="text" wire:model="titulo" wire:key="titulo-{{ $tituloKey ?? 0 }}"
                                class="form-control @error('titulo') is-invalid @enderror"
                                placeholder="Ej: No funciona la impresora"
                            >
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Adjuntar Evidencia (opcional)</label>
                            <input type="file" wire:model="archivo_adjunto" wire:key="archivo-{{ $tituloKey ?? 0 }}"
                                class="form-control @error('archivo_adjunto') is-invalid @enderror"
                            >
                            <div wire:loading wire:target="archivo_adjunto" class="small text-success mt-2">
                                <span class="spinner-border spinner-border-sm me-1"></span> Subiendo...
                            </div>
                            @error('archivo_adjunto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mt-auto" wire:loading.attr="disabled" wire:target="guardarTicket">
                            <span wire:loading.remove wire:target="guardarTicket">Enviar Ticket</span>
                            <span wire:loading wire:target="guardarTicket">
                                <span class="spinner-border spinner-border-sm me-2"></span> Enviando...
                            </span>
                        </button>

                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <h5 class="card-title fw-bold mb-4">Mis Tickets</h5>

                    @if ($misTickets->isEmpty())
                        <div class="text-center py-5 my-auto">
                            <i class="fa-solid fa-folder-open text-muted display-4 mb-3"></i>
                            <p class="text-muted mb-0">No tienes tickets creados.</p>
                        </div>
                    @else
                        <div class="table-responsive flex-grow-1" style="max-height: 520px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="border-bottom border-top">
                                        <th class="ps-2">Codigo</th>
                                        <th>Titulo</th>
                                        <th>Categoria</th>
                                        <th style="min-width: 110px;">Estado</th>
                                        <th>Fecha</th>
                                        <th class="text-end pe-2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($misTickets as $ticket)
                                        <tr wire:key="mi-ticket-{{ $ticket->id }}">
                                            <td class="ps-2 font-monospace text-muted small">TIC-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td>
                                                <div class="fw-semibold small">{{ $ticket->titulo }}</div>
                                                <div class="small text-muted text-truncate" style="max-width: 195px;">{{ $ticket->descripcion }}</div>
                                            </td>
                                            <td class="small">{{ $ticket->categoria->nombre ?? 'Sin categoria' }}</td>
                                            <td>
                                                <span class="badge-estado
                                                    @if($ticket->estado == 'abierto') badge-abierto
                                                    @elseif($ticket->estado == 'en_proceso') badge-en-proceso
                                                    @elseif($ticket->estado == 'resuelto') badge-resuelto
                                                    @elseif($ticket->estado == 'cancelado') badge-cancelado
                                                    @elseif($ticket->estado == 're_abierto') badge-re-abierto
                                                    @endif">
                                                    {{ str_replace('_', ' ', $ticket->estado) }}
                                                </span>
                                            </td>
                                            <td class="small text-muted">{{ $ticket->created_at->format('d/m/Y h:i A') }}</td>
                                            <td class="text-end pe-2">
                                                <div class="d-flex justify-content-end gap-1">
                                                    @if ($ticket->archivo_adjunto)
                                                        <a href="{{ asset('storage/' . $ticket->archivo_adjunto) }}"
                                                            target="_blank"
                                                            class="btn btn-sm btn-outline-secondary py-1 px-2">
                                                            <i class="fa-solid fa-paperclip"></i>
                                                        </a>
                                                    @endif

                                                    @if (in_array($ticket->estado, ['abierto', 'en_proceso']))
                                                        <button wire:click="cancelarTicket({{ $ticket->id }})"
                                                            wire:confirm="¿Seguro que deseas cancelar este reporte?"
                                                            class="btn btn-sm btn-outline-danger py-1 px-2">
                                                            <i class="fa-solid fa-ban"></i>
                                                        </button>
                                                    @endif

                                                    @if ($ticket->estado == 'resuelto')
                                                        <button wire:click="reabrirTicket({{ $ticket->id }})"
                                                            class="btn btn-sm btn-outline-warning py-1 px-2">
                                                            <i class="fa-solid fa-rotate-left"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pt-3 mt-auto">
                            {{ $misTickets->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>