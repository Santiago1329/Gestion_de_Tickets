<x-guest-layout>
    <div class="mb-3 text-center">
        {{ __('Por favor, confirma tu contraseña antes de continuar.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Contraseña') }}</label>
            <input
                id="password" 
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required autocomplete="new-password"
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center justify-content-center gap-3">
            <button class="btn btn-primary" type="submit">{{ __('Confirmar') }}</button>
        </div>
    </form>
</x-guest-layout>
