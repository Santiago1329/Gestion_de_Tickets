<div class="modal fade" id="modalReporte" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Generar reporte mensual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Mes</label>
                        <select wire:model="reporteMes" class="form-select">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Año</label>
                        <select wire:model="reporteAnio" class="form-select">
                            @foreach (range(now()->year, now()->year - 1) as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
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