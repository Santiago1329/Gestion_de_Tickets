<div>
    <div
        id="chat-mensajes-{{ $ticket->id }}"
        wire:ignore.self
        x-data
        x-init="$el.scrollTop = $el.scrollHeight"
        x-on:chat-scroll-abajo.window="setTimeout(() => { $el.scrollTop = $el.scrollHeight }, 50)"
        class="chat-mensajes p-3 rounded-3 mb-3"
        style="height: 340px; overflow-y: auto;"
    >
        @php $listaMensajes = $mensajes->values(); @endphp

        @forelse ($listaMensajes as $i => $msg)
            @php
                $esPropio = $msg->user_id === auth()->id();
                $anterior = $listaMensajes[$i - 1] ?? null;
                $seguidoDelMismo = $anterior && $anterior->user_id === $msg->user_id;
            @endphp
            <div class="d-flex {{ $seguidoDelMismo ? 'mb-1' : 'mb-3' }} {{ $esPropio ? 'justify-content-end' : 'justify-content-start' }}">
                <div style="max-width: 75%;">
                    @if(!$esPropio && !$seguidoDelMismo)
                        <div class="fw-semibold mb-1" style="font-size: 0.72rem; color: var(--color-text-muted);">
                            {{ $msg->user->name }}
                        </div>
                    @endif

                    <div class="chat-burbuja px-3 py-2 {{ $esPropio ? 'chat-burbuja-propia' : 'chat-burbuja-otro' }}">
                        @if ($msg->imagen)
                            <a href="{{ asset('storage/' . $msg->imagen) }}" target="_blank">
                                <img src="{{ asset('storage/' . $msg->imagen) }}" class="chat-imagen-mensaje mb-1" style="max-width: 200px; border-radius: 8px; display: block;">
                            </a>
                        @endif
                        @if ($msg->mensaje)
                            <div style="white-space: pre-wrap; font-size: 0.88rem;">{{ $msg->mensaje }}</div>
                        @endif
                    </div>

                    <div class="chat-timestamp mt-1 {{ $esPropio ? 'text-end' : 'text-start' }}">
                        {{ $msg->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center">
                <i class="fa-regular fa-comments text-muted mb-2" style="font-size: 1.6rem; opacity: 0.4;"></i>
                <span class="text-muted small">Aún no hay mensajes. Escribe el primero</span>
            </div>
        @endforelse
    </div>

    <form wire:submit.prevent="enviarMensaje" class="chat-input-wrapper">
        @if ($imagen)
            <div class="d-flex align-items-center gap-2 mb-2">
                <img src="{{ $imagen->temporaryUrl() }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                <button type="button" wire:click="quitarImagen" class="btn btn-sm">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <div class="d-flex gap-2">
            <label class="btn btn-outline-secondary mb-0 d-flex align-items-center" style="cursor: pointer;">
                <i class="fa-solid fa-paperclip"></i>
                <input type="file" wire:model="imagen" accept="image/*" style="display: none;">
            </label>

            <input
                type="text"
                wire:model="nuevoMensaje"
                placeholder="Escribe un mensaje..."
                class="form-control @error('nuevoMensaje') is-invalid @enderror"
                autocomplete="off"
                x-data
                x-on:paste="
                    const items = $event.clipboardData?.items;
                    if (!items) return;
                    for (const item of items) {
                        if (item.type.startsWith('image/')) {
                            $event.preventDefault();
                            const file = item.getAsFile();
                            $wire.upload('imagen', file);
                        }
                    }
                "
            >
            <button type="submit">
                <i class="fa-solid fa-paper-plane" style="color: #fff; font-size: 0.85rem;"></i>
            </button>
        </div>
    </form>
    @error('nuevoMensaje')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
    @error('imagen')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>