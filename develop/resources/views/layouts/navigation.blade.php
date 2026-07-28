<nav class="navbar navbar-expand-md navbar-espa">
    <div class="container-fluid px-4">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center gap-2">
            <div class="rounded d-flex align-items-center justify-content-center"
                style="width:32px;height:32px;background-color:var(--color-primary);">
                <i class="fa-solid fa-headset text-white" style="font-size:14px;"></i>
            </div>
            ESPA
        </a>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item d-flex align-items-center gap-3">
                @livewire('notificaciones-dropdown')

                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:30px;height:30px;background-color:var(--color-primary-light);border:2px solid var(--color-primary);">
                        <i class="fa-solid fa-user" style="font-size:12px;color:var(--color-primary);"></i>
                    </div>
                    <span>{{ Auth::user()->name }}</span>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="mb-0">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger" type="submit">
                        <i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Cerrar Sesión
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>