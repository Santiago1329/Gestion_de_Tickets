<div>
    <!-- Contenedor de mensajes -->
    <div
        id="chat-mensajes-{{ $ticket->id }}"
        wire:ignore.self
        x-data
        x-init="$el.scrollTop = $el.scrollHeight"
        x-on:chat-scroll-abajo.window="setTimeout(() => { $el.scrollTop = $el.scrollHeight }, 50)"
        class="p-2 rounded-2 mb-2"
        style="height: 320px; overflow-y: auto; background-color: var(--color-bg); border: 1px solid var(--color-border);"
    >
        @forelse ($mensajes as $msg)
            <div class="d-flex mb-2 {{ $msg->user_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                <div style="max-width: 75%;">
                    <div
                        class="px-3 py-2 rounded-3 small"
                        style="
                            {{ $msg->user_id === auth()->id()
                                ? 'background-color: var(--color-primary); color: #fff;'
                                : 'background-color: var(--color-surface); border: 1px solid var(--color-border); color: var(--color-text);' }}
                        "
                    >
                        @if($msg->user_id !== auth()->id())
                            <div class="fw-semibold" style="font-size: 0.72rem; opacity: 0.75;">
                                {{ $msg->user->name }}
                            </div>
                        @endif
                        <div style="white-space: pre-wrap;">{{ $msg->mensaje }}</div>
                    </div>
                    <div class="text-muted mt-1 {{ $msg->user_id === auth()->id() ? 'text-end' : 'text-start' }}" style="font-size: 0.68rem;">
                        {{ $msg->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted small mt-5">
                Aún no hay mensajes. Escribe el primero 👋
            </div>
        @endforelse
    </div>

    <!-- Formulario de envío -->
    <form wire:submit.prevent="enviarMensaje" class="d-flex gap-2">
        <input
            type="text"
            wire:model="nuevoMensaje"
            placeholder="Escribe un mensaje..."
            class="form-control form-control-sm @error('nuevoMensaje') is-invalid @enderror"
            autocomplete="off"
        >
        <button type="submit" class="btn btn-sm" style="background-color: var(--color-primary); color: #fff;">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </form>
    @error('nuevoMensaje')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>