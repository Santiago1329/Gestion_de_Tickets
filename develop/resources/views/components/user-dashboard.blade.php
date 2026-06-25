<div class="container-fluid py-4">

    <!-- Header -->
    <div class="card shadow-sm border-0 border-start border-primary border-5 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1 fw-bold">Sistema de soporte TICs</h3>
                <p class="text-muted small mb-0">Bienvenido, {{ auth()->user()->name }} - Reporta y gestiona tus tickets.</p>
            </div>
            <div class="text-end d-none d-sm-block">
                <span class="text-uppercase small text-muted">Rol</span>
                <h6 class="fw-bold text-primary text-uppercase mb-0">{{ auth()->user()->rol }}</h6>
            </div>
        </div>
    </div>

    <!-- Mensaje de exito -->
    @if (session()->has('mensaje'))
        <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">

        <!-- Formulario de nuevo ticket -->
        <div class="col-12 col-lg-5">
            <div class="card shadow-sm border-0 border-top border-primary border-4 h-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">
                        <i class="fa-solid fa-square-plus me-2"></i> Nuevo Ticket
                    </h5>

                    <form wire:submit.prevent="guardarTicket">

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Titulo del ticket *</label>
                            <input type="text" wire:model="titulo"
                                class="form-control @error('titulo') is-invalid @enderror"
                                placeholder="Ej: No funciona la impresora"
                            >
                            @error('titulo') 
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Categoria *</label>
                            <select wire:model="categoria_id" 
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
                            <textarea wire:model="descripcion"
                                class="form-control @error('descripcion') is-invalid @enderror"
                                placeholder="Escribe los detalles aqui..." rows="4">
                            </textarea>
                            @error('descripcion') 
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">
                                <i class="fa-solid fa-paperclip me-1"></i> Adjuntar Evidencia (opcional)
                            </label>
                            <input type="file" wire:model="archivo_adjunto"
                                class="form-control @error('archivo_adjunto') is-invalid @enderror"
                            >
                            <div wire:loading wire:target="archivo_adjunto" class="small text-primary mt-2">
                                <i class="fa-solid fa-spinner fa-spin me-1"></i> Subiendo archivo...
                            </div>
                            @error('archivo_adjunto') 
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary bg-gradient w-100 py-2 fw-bold">Enviar Ticket</button>

                    </form>
                </div>
            </div>
        </div>

        <!-- Lista de Tickets -->
        <div class="col-12 col-lg-7">
            <div class="card shadow-sm border-0 border-top border-success border-4 h-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">
                        <i class="fa-solid fa-clock-rotate-left me-2 text-success"></i> Mis Tickets
                    </h5>

                    @if ($misTickets->isEmpty())
                        <div class="text-center py-5 border border-dashed rounded-3">
                            <i class="fa-solid fa-folder-open text-muted display-4 mb-3"></i>
                            <p class="text-muted mb-0">No tienes tickets creados.</p>
                        </div>
                    @else
                        <div class="overflow-auto pe-1" style="max-height: 520px;">
                            @foreach ($misTickets as $ticket)
                                <div class="card mb-3 border">
                                    <div class="card-body p-3">

                                        <div class="d-flex justify-content-between aling-items-start gap-2">
                                            <!-- Info Izquierda -->
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <span class="badge border font-monospace text-body">TIC-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</span>
                                                    <h6 class="fw-bold mb-0">{{ $ticket->titulo }}</h6>
                                                </div>

                                                <p class="text-muted small mb-2">
                                                    <i class="fa-solid fa-tag me-1"></i> {{ $ticket->categoria->nombre ?? 'Sin categoria' }}
                                                    <span class="mx-1">·</span>
                                                    <i class="fa-solid fa-calendar me-1"></i> {{ $ticket->created_at->format('d/m/Y h:i A') }}
                                                </p>

                                                <p class="small text-muted mb-2 text-truncate" style="max-width: 380px;">
                                                    {{ $ticket->descripcion }}
                                                </p>

                                                @if ($ticket->archivo_adjunto)
                                                    <a href="{{ asset('storage/' . $ticket->archivo_adjunto) }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-outline-secondary py-0 px-2">
                                                        <i class="fa-solid fa-paperclip me-1"></i> Ver archivo adjunto
                                                    </a>
                                                @endif
                                            </div>

                                            <!-- Estado Derecha -->
                                            <div class="text-end d-flex flex-column">
                                                <span class="badge text-uppercase
                                                    @if ($ticket->estado == 'abierto') bg-secondary-subtle text-secondary border border-secondary
                                                    @elseif ($ticket->estado == 'en_proceso') bg-primary-subtle text-primary border border-primary
                                                    @elseif ($ticket->estado == 'resuelto') bg-success-subtle text-success border border-success
                                                    @elseif ($ticket->estado == 'cancelado') bg-danger-subtle text-danger border border-danger
                                                    @elseif ($ticket->estado == 're_abierto') bg-warning-subtle text-warning border border-warning
                                                    @endif
                                                ">
                                                    {{ str_replace('_', ' ', $ticket->estado) }}
                                                </span>
                                            </div>
                                        </div>

                                        @if (in_array($ticket->estado,['abierto', 'en_proceso', 'resuelto']))
                                            <div class="d-flex justify-content-end gap-2 mt-3 pt-2 border-top">
                                                @if (in_array($ticket->estado, ['abierto', 'en_proceso']))
                                                    <button wire:click="cancelarTicket({{ $ticket->id }})"
                                                        wire:confirm="¿Seguro que deseas cancelar este reporte?"
                                                        class="btn btn-sm btn-outline-danger">
                                                        <i class="fa solid fa-ban me-1"></i> Cancelar
                                                    </button>
                                                @endif

                                                @if($ticket->estado == 'resuelto')
                                                    <button wire:click="reabrirTicket({{ $ticket->id }})"
                                                        class="btn btn-sm btn-outline-warning">
                                                        <i class="fa-solid fa-rotate-left me-1"></i> Reabrir
                                                    </button>
                                                @endif

                                            </div>
                                        @endif

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>