<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Nombre') }}</label>
            <input
                id="name" 
                type="text"
                name="name"
                value="{{ old('name') }}"
                class="form-control @error('name') is-invalid @enderror"
                required autofocus autocomplete="name"
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

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

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('Confirmar contraseña') }}</label>
            <input
                id="password_confirmation" 
                type="password"
                name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                required autocomplete="new-password"
            >
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center justify-content-end gap-3">
            <a class="text-decoration-none text-secondary" 
                href="{{ route('login') }}">
                {{ __('Ya te registraste?') }}
            </a>

            <button class="btn btn-primary" type="submit">{{ __('Registrar') }}</button>
        </div>
    </form>
</x-guest-layout>
