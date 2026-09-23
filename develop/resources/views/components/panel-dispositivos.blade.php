<div class="container-fluid panel-noc">

    <div class="card-body d-flex justify-content-between align-items-center mb-4">
        <div>
            @if($sedeSeleccionada)
                <h3 class="mb-1 fw-bold">{{ $sedeSeleccionada }}</h3>
            @else
                <h3 class="mb-1 fw-bold">Monitoreo de Dispositivos</h3>
                <p class="text-muted small mb-0">Selecciona una sede para ver sus dispositivos</p>
            @endif
        </div>
        <div>
            @if($sedeSeleccionada)
                <button wire:click="volverASedes" class="btn btn-outline-secondary fw-semibold mb-2 my-2 mx-4">
                    <i class="fa-solid fa-arrow-left me-1"></i> SEDES
                </button>
            @else
                <span class="badge-role d-none d-sm-inline-block">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-success fw-semibold">
                        <i class="fa-solid fa-arrow-left me-1"></i> Volver
                    </a>
                </span>
            @endif
        </div>
    </div>

    @if(!$sedeSeleccionada)
        <div class="row g-3">
            @forelse($sedes as $s)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card kpi-card border-0 shadow-sm" style="cursor:pointer;" wire:click="seleccionarSede('{{ $s->sede }}')">
                        <div class="card-body">
                            <h5 class="fw-bold mb-2">{{ $s->sede ?? 'Sin sede' }}</h5>
                            <div class="d-flex gap-3 small">
                                <span class="text-success"><i class="fa-solid fa-circle" style="font-size:8px;"></i> {{ $s->online }} online</span>
                                <span class="text-danger"><i class="fa-solid fa-circle" style="font-size:8px;"></i> {{ $s->offline }} caídos</span>
                            </div>
                            <p class="text-muted small mb-0 mt-1">{{ $s->total }} dispositivos</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No hay dispositivos registrados.</p>
            @endforelse
        </div>
    @else
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card kpi-card shadow-sm border-0">
                    <div class="card-body gap-3 d-flex align-items-center">
                        <div class="kpi-icon kpi-icon-success">
                            <i class="fa-solid fa-signal"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0 kpi-value-success">{{ $dispositivos->where('estado', 'online')->count() }}</h4>
                            <p class="text-secondary small mb-0">En linea</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card kpi-card border-0">
                    <div class="card-body gap-3 d-flex align-items-center">
                        <div class="kpi-icon" style="background-color:#fee2e2;color:#b91c1c;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0 " style="color:#b91c1c;">{{ $dispositivos->where('estado', 'offline')->count() }}</h4>
                            <p class="text-secondary small mb-0">Caidos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card kpi-card border-0">
                    <div class="card-body gap-3 d-flex align-items-center">
                        <div class="kpi-icon kpi-icon-neutral">
                            <i class="fa-solid fa-server"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0 kpi-value-neutral">{{ $dispositivos->count() }}</h4>
                            <p class="text-secondary small mb-0">Total dispositivos</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card kpi-card border-0">
                    <div class="card-body gap-3 d-flex align-items-center">
                        <div class="kpi-icon kpi-icon-info">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div style="min-height: 49px; display: flex; flex-direction: column; justify-content: center;">
                            <h4 class="fw-bold mb-0 kpi-value-info" style="font-size: 0.95rem;">
                                {{ ucfirst($dispositivos->max('ultimo_check_at')?->diffForHumans() ?? '-') }}
                            </h4>
                            <p class="text-secondary small mb-0">Último chequeo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="border-bottom border-top">
                            <th class="ps-3">Estado</th>
                            <th>Nombre</th>
                            <th>IP</th>
                            <th>Último chequeo</th>
                            <th class="text-end pe-3">Acceso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dispositivos as $dispositivo)
                            <tr wire:key="dispositivo-{{ $dispositivo->id }}">
                                <td class="ps-3">
                                    <span class="badge-estado
                                        @if($dispositivo->estado === 'online') badge-resuelto
                                        @elseif($dispositivo->estado === 'offline') badge-cancelado
                                        @else badge-abierto
                                        @endif">
                                        {{ ucfirst($dispositivo->estado) }}
                                    </span>
                                </td>
                                <td class="fw-semibold small">{{ $dispositivo->nombre }}</td>
                                <td class="font-monospace small text-muted">{{ $dispositivo->ip }}</td>
                                <td class="small text-muted">{{ $dispositivo->ultimo_check_at?->diffForHumans() ?? 'nunca' }}</td>
                                <td class="text-end pe-3">
                                    <a href="http://{{ $dispositivo->ip }}" target="_blank" class="btn btn-sm btn-outline-secondary py-1 px-2">
                                        <i class="fa-solid fa-up-right-from-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>