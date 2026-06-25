<x-guest-layout>

    @if (session('status'))
        <div class="alert alert-success mb-3">
            {{ session('status') }}
        </div>
    @endif

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-4" id="authTabs">
        <li class="nav-item">
            <button class="nav-link {{ $errors->hasAny(['email', 'password']) || !$errors->hasAny(['name', 'password_confirmation']) ? 'active' : '' }}"
                data-bs-toggle="tab" data-bs-target="#tabLogin">
                Iniciar Sesión
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $errors->hasAny(['name', 'password_confirmation']) ? 'active' : '' }}"
                data-bs-toggle="tab" data-bs-target="#tabRegister">
                Registrarse
            </button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- Tab Login --}}
        <div class="tab-pane fade {{ $errors->hasAny(['name', 'password_confirmation']) ? '' : 'show active' }}" id="tabLogin">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Correo</label>
                    <input id="email" type="email" name="email"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        required autofocus autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input id="password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required autocomplete="current-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label for="remember_me" class="form-check-label">Recordarme</label>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-3">
                    @if (Route::has('password.request'))
                        <a class="text-decoration-none text-secondary small"
                            href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                    <button class="btn btn-primary" type="submit">Iniciar Sesión</button>
                </div>
            </form>
        </div>

        {{-- Tab Register --}}
        <div class="tab-pane fade {{ $errors->hasAny(['name', 'password_confirmation']) ? 'show active' : '' }}" id="tabRegister">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input id="name" type="text" name="name"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                        required autocomplete="name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="reg_email" class="form-label">Correo</label>
                    <input id="reg_email" type="email" name="email"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        required autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="reg_password" class="form-label">Contraseña</label>
                    <input id="reg_password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">Registrarse</button>
                </div>
            </form>
        </div>

    </div>

</x-guest-layout>