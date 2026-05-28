<x-guest-layout>
    <div class="mb-3 text-center">
        {{ __('Escribe aqui tu direccion de correo electronico, te llegara un enlace para restablecer tu contraseña. ') }}
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Correo') }}</label>
            <input
                id="email" 
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                required autofocus autocomplete="username"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center justify-content-center gap-3">
            <a href="{{ route('login') }}" class="btn btn-secondary">{{ __('Volver') }}</a>
            <button class="btn btn-primary" type="submit">{{ __('Enviar enlace') }}</button>
        </div>
    </form>
</x-guest-layout>
