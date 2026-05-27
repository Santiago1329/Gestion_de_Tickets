<div class="container-fluid bg-light min-vh-screen py-4">
    
    <div class="card shadow-sm border-0 border-start border-primary border-5 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-1 fw-bold text-dark">Panel de Soporte TICs</h1>
                <p class="text-muted small mb-0">Bienvenido, {{ auth()->user()->name }} — Reporta y gestiona tus incidentes técnicos.</p>
            </div>
            <div class="text-end d-none d-sm-block">
                <span class="text-uppercase text-muted tracking-wider small font-weight-bold">Rol</span>
                <div class="h6 fw-bold text-primary text-uppercase mb-0">{{ auth()->user()->rol }}</div>
            </div>
        </div>
    </div>

    @if (session()->has('mensaje'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        
        <div class="col-12 col-lg-5">
            <div class="card shadow-sm border-0 border-top border-primary border-4 h-100">
                <div class="card-body p-4">
                    <h2 class="h5 card-title fw-bold text-dark mb-4">
                        <i class="fa-solid fa-square-plus me-2 text-primary"></i> Registrar Nuevo Incidente
                    </h2>

                    <form wire:submit.prevent="guardarTicket" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">¿Qué está fallando? *</label>
                            <input type="text" wire:model="titulo" placeholder="Ej: No conecta la impresora" 
                                class="form-control @error('titulo') is-invalid @enderror">
                            @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Categoría *</label>
                            <select wire:model="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror">
                                <option value="">-- Selecciona una opción --</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                @endforeach
                            </select>
                            @error('categoria_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary small">Descripción detallada *</label>
                            <textarea wire:model="descripcion" placeholder="Escribe los detalles aquí..." rows="4"
                                class="form-control @error('descripcion') is-invalid @enderror"></textarea>
                            @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-secondary small">
                                <i class="fa-solid fa-paperclip me-2 text-muted"></i> Adjuntar Evidencia (Opcional)
                            </label>
                            <input type="file" wire:model="evidencia" class="form-control @error('evidencia') is-invalid @enderror">
                            
                            <div wire:loading wire:target="evidencia" class="small text-primary mt-2">
                                <i class="fa-solid fa-spinner fa-spin me-2"></i> Subiendo archivo...
                            </div>
                            @error('evidencia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-full py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-paper-plane me-2"></i> Enviar a Soporte
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="card shadow-sm border-0 border-top border-success border-4 h-100">
                <div class="card-body p-4">
                    <h2 class="h5 card-title fw-bold text-dark mb-4">
                        <i class="fa-solid fa-clock-history me-2 text-success"></i> Seguimiento de Mis Tickets
                    </h2>

                    @if($misTickets->isEmpty())
                        <div class="text-center py-5 border border-dashed rounded-3">
                            <i class="fa-solid fa-folder-open text-muted display-4 mb-3"></i>
                            <p class="text-muted mb-0">No has reportado incidentes todavía.</p>
                        </div>
                    @else
                        <div class="overflow-auto pe-2" style="max-height: 500px;">
                            @foreach($misTickets as $ticket)
                                <div class="card mb-3 border-2 border-light hover-shadow transition">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <span class="badge bg-light text-dark border font-monospace">#{{ $ticket->id }}</span>
                                                <h3 class="h6 fw-bold text-dark mt-2 mb-1">{{ $ticket->titulo }}</h3>
                                                <p class="text-muted text-xs mb-2">
                                                    <span class="mx-1">•</span>
                                                    <i class="fa-solid fa-calendar me-1"></i> {{ $ticket->created_at->format('d/m/Y h:i A') }}
                                                </p>
                                                <p class="card-text text-secondary small bg-light p-2 rounded mb-2 text-truncate" style="max-width: 450px;">
                                                    {{ $ticket->descripcion }}
                                                </p>

                                                @if($ticket->evidencia)
                                                    <a href="{{ asset('storage/' . $ticket->evidencia) }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2 text-xs">
                                                        <i class="fa-solid fa-image me-1"></i> Ver adjunto
                                                    </a>
                                                @endif
                                            </div>

                                            <div class="text-end d-flex flex-column align-items-end gap-2">
                                                <span class="badge text-uppercase font-weight-bold
                                                    {{ $ticket->estado == 'abierto' ? 'bg-success-subtle text-success border border-success' : '' }}
                                                    {{ $ticket->estado == 'en_proceso' ? 'bg-primary-subtle text-primary border border-primary' : '' }}
                                                    {{ $ticket->estado == 'resuelto' ? 'bg-secondary-subtle text-secondary border border-secondary text-decoration-line-through' : '' }}
                                                    {{ $ticket->estado == 'cancelado' ? 'bg-red-subtle text-danger border border-danger' : '' }}
                                                    {{ $ticket->estado == 'reabierto' ? 'bg-warning-subtle text-warning border border-warning' : '' }}">
                                                    {{ $ticket->estado }}
                                                </span>
                                                <span class="text-uppercase text-muted style-priority small" style="font-size: 10px;">
                                                    Prioridad: <strong>{{ $ticket->prioridad }}</strong>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 mt-3 pt-2 border-top">
                                            @if(in_array($ticket->estado, ['abierto', 'en_proceso']))
                                                <button wire:click="cancelarTicket({{ $ticket->id }})" 
                                                    wire:confirm="¿Seguro que deseas cancelar este reporte?"
                                                    class="btn btn-sm btn-outline-danger py-1 px-2 font-weight-bold">
                                                    <i class="fa-solid fa-ban me-1"></i> Cancelar
                                                </button>
                                            @endif

                                            @if($ticket->estado == 'resuelto')
                                                <button wire:click="reabrirTicket({{ $ticket->id }})" 
                                                    class="btn btn-sm btn-outline-warning py-1 px-2 font-weight-bold">
                                                    <i class="fa-solid fa-rotate-left me-1"></i> Reabrir
                                                </button>
                                            @endif
                                        </div>
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