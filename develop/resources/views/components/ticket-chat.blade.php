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
                        <div style="white-space: pre-wrap; font-size: 0.88rem;">{{ $msg->mensaje }}</div>
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

    <form wire:submit.prevent="enviarMensaje" class="d-flex gap-2 chat-input-wrapper">
        <input
            type="text"
            wire:model="nuevoMensaje"
            placeholder="Escribe un mensaje..."
            class="form-control @error('nuevoMensaje') is-invalid @enderror"
            autocomplete="off"
        >
        <button type="submit">
            <i class="fa-solid fa-paper-plane" style="color: #fff; font-size: 0.85rem;"></i>
        </button>
    </form>
    @error('nuevoMensaje')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>