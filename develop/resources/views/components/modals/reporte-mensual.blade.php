<div class="modal fade" id="modalReporte" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Generar reporte mensual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Selector del tipo de filtro -->
                <div class="mb-3">
                    <label class="form-label small fw-bold">Filtrar por</label>
                    <select wire:model.live="tipoReporte" class="form-select">
                        <option value="mes">Mes y año</option>
                        <option value="rango">Rango de fechas</option>
                    </select>
                </div>

                <!-- Por mes y año -->
                @if ($tipoReporte === 'mes')
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Mes</label>
                            <select wire:model="reporteMes" class="form-select">
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                                @endforeach
                            </select>
                            @error('reporteMes') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Año</label>
                            <select wire:model="reporteAnio" class="form-select">
                                @foreach (range(now()->year, now()->year - 1) as $y)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endforeach
                            </select>
                            @error('reporteAnio') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif

                <!-- Rango de fechas -->
                @if ($tipoReporte === 'rango')
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Fecha inicio</label>
                            <input type="date" wire:model="fechaInicio" class="form-control">
                            @error('fechaInicio') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Fecha fin</label>
                            <input type="date" wire:model="fechaFin" class="form-control">
                            @error('fechaFin') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success fw-bold" wire:click="generarReporte" wire:loading.attr="disabled" wire:target="generarReporte">
                    <span wire:loading.remove wire:target="generarReporte">Descargar</span>
                    <span wire:loading wire:target="generarReporte">Generando...</span>
                </button>
            </div>
        </div>
    </div>
</div>