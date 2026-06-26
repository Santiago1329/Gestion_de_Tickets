<nav class="navbar navbar-expand-md navbar-dark bg-dark border-bottom shadow-sm">
    <div class="container-fluid px-4">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="navbar-brand">
            {{ config('app.name') }}
        </a>

        <!-- Boton movil -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Links Izquierda -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>
            </ul>

            <!-- Menu usuario Derecha -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                        <div class="d-flex flex-column lh-sm">
                            <span>{{ Auth::user()->name }}</span>
                            <small class="text-muted" style="font-size: 11px;">
                                {{ Auth::user()->area ?? 'Sin área' }}
                            </small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fa-solid fa-user me-2"></i> Perfil
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">
                                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
