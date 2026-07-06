<div class="container-fluid">

    <!-- Header -->
    <div class="card-body d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold">Panel de Administración</h3>
            <p class="text-muted small mb-0">Gestiona todos los tickets de soporte</p>
        </div>
        <span class="badge-role d-none d-sm-inline-block">{{ auth()->user()->rol }}</span>
    </div>

    <!-- Tarjetas Resumen -->
    <div class="row g-3 mb-4">

        <div class="col-6 col-md-3">
            <div class="card kpi-card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="kpi-icon kpi-icon-primary">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 kpi-value-primary">{{ $totalTickets }}</h4>
                        <p class="text-muted small mb-0">Total de Tickets</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card kpi-card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="kpi-icon kpi-icon-neutral">
                        <i class="fa-solid fa-folder-open"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 kpi-value-neutral">{{ $totalAbiertos }}</h4>
                        <p class="text-muted small mb-0">Tickets Abiertos</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card kpi-card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="kpi-icon kpi-icon-info">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 kpi-value-info">{{ $totalEnProceso }}</h4>
                        <p class="text-muted small mb-0">Tickets en Proceso</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card kpi-card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="kpi-icon kpi-icon-success">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 kpi-value-success">{{ $totalResueltos }}</h4>
                        <p class="text-muted small mb-0">Tickets Resueltos</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Filtros -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <div class="row g-2">

                <div class="col-12 col-md-2">
                    <select wire:model.live="filtroEstado" class="form-select form-select-sm">
                        <option value="">Todos los estados</option>
                        <option value="abierto">Abierto</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="resuelto">Resuelto</option>
                        <option value="cancelado">Cancelado</option>
                        <option value="re_abierto">Reabierto</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <select wire:model.live="filtroCategoria" class="form-select form-select-sm">
                        <option value="">Todas las categorias</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <select wire:model.live="filtroPrioridad" class="form-select form-select-sm">
                        <option value="">Todas las prioridades</option>
                        <option value="baja">Baja</option>
                        <option value="media">Media</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 d-flex justify-content-end">
                    <button class="btn btn-primary px-3 fw-bold" wire:loading.attr="disabled" wire:click="abrirModalCrear" data-bs-target="#modalCrearTicket">
                        <i class="fa-solid fa-plus me-1"></i> Nuevo Ticket
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabla de Tickets -->
        <div class="card-body p-0">
            @if ($tickets->isEmpty())
                <div class="text-center py-5">
                    <i class="fa-solid fa-folder-open text-muted display-4 mb-3"></i>
                    <p class="text-muted mb-0">No hay tickets para mostrar</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="border-bottom border-top">
                                <th class="ps-3">Codigo</th>
                                <th>Usuario</th>
                                <th>Titulo</th>
                                <th>Categoria</th>
                                <th>Estado</th>
                                <th>Prioridad</th>
                                <th>Fecha</th>
                                <th class="text-end pe-3">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr wire:key="ticket-{{ $ticket->id }}">
                                    <td class="ps-3 font-monospace text-muted">TIC-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <div class="fw-semibold small">{{ $ticket->user->name }}</div>
                                    </td>
                                    <td class="fw-semibold small">{{ $ticket->titulo }}</td>
                                    <td class="small">{{ $ticket->categoria->nombre ?? '-' }}</td>

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
                                    <td>
                                        <span class="badge-estado
                                            @if($ticket->prioridad == 'alta') badge-alta
                                            @elseif($ticket->prioridad == 'media') badge-media
                                            @elseif($ticket->prioridad == 'baja') badge-baja
                                            @endif">
                                            {{ $ticket->prioridad }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">
                                        @if($ticket->created_at)
                                            {{ $ticket->created_at->format('d/m/Y h:i A') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex justify-content-end gap-1">
                                            {{-- Ver detalle --}}
                                            <button wire:click="verDetalle({{ $ticket->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="verDetalle"
                                                class="btn btn-sm btn-outline-secondary py-1 px-2">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>

                                            {{-- Editar --}}
                                            @if($ticket->estado !== 'cancelado')
                                                <button wire:click="abrirEditar({{ $ticket->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:target="abrirEditar"
                                                    class="btn btn-sm btn-outline-primary py-1 px-2">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-3">
                    {{ $tickets->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Ver Detalle -->
    @include('components.modals.ver-detalle')

    <!-- Modal Editar Ticket -->
    @include('components.modals.editar-ticket')

    <!-- Modal Crear Ticket -->
    @include('components.modals.crear-ticket')

    <!-- Cerrar modales al terminar una accion -->
    <script>
        window.addEventListener('abrirModalEditar', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditar')).show();
        });
        window.addEventListener('abrirModalCrearTicket', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCrearTicket')).show();
        });
        window.addEventListener('cerrarModalCrear', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCrearTicket')).hide();
        });
        window.addEventListener('cerrarModalEditar', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditar')).hide();
        });
        window.addEventListener('abrirModalDetalle', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDetalle')).show();
        });
    </script>
</div>