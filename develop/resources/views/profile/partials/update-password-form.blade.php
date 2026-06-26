<h5 class="card-title fw-bold mb-1">Actualizar contraseña</h5>
<p class="text-muted small mb-4">Usa una contraseña segura.</p>

<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <!-- Contraseña actual -->
    <div class="mb-3">
        <label for="current_password" class="form-label">Contraseña actual</label>
        <input 
            type="password"
            id="current_password"
            name="current_password"
            class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
            autocomplete="current-password"
        >
        @error('current_password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label">Nueva contraseña</label>
        <input
            id="password" 
            type="password"
            name="password"
            class="form-control @error('password', 'updatePassword') is-invalid @enderror"
            autocomplete="new-password"
        >
        @error('password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
        <input
            id="password_confirmation" 
            type="password"
            name="password_confirmation"
            class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
            autocomplete="new-password"
        >
        @error('password_confirmation', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-primary" type="submit">Actualizar</button>

        @if (session('status') === 'password-updated')
            <span class="text-success small">
                <i class="fa-solid fa-check me-1"></i> Contraseña actualizada
            </span>
        @endif
    </div>
</form>
