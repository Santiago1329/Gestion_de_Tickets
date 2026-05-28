<h5 class="card-title fw-bold mb-1">Información del Perfil</h5>
<p class="text-muted small mb-4">Actualiza tu nombre y dirección de correo electrónico.</p>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <!-- Name -->
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input
            id="name" 
            type="text"
            name="name"
            value="{{ old('name', $user->name) }}"
            class="form-control @error('name') is-invalid @enderror"
            required autofocus autocomplete="name"
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">Correo Electrónico</label>
        <input
            id="email" 
            type="email"
            name="email"
            value="{{ old('email', $user->email) }}"
            class="form-control @error('email') is-invalid @enderror"
            required autofocus autocomplete="username"
        >
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-primary" type="submit">Guardar</button>

        @if (session('status') === 'profile-updated')
            <span class="text-success small">
                <i class="fa-solid fa-check me-1"></i> Guardado correctamente
            </span>
        @endif
    </div>
</form>
