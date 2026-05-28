<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0">Mi Perfil</h2>
    </x-slot>

    <div class="container py-12" style="max-width: 80%;">

        <!-- Informacion del perfil -->
        <div class="card shadow-sm mb-4">
            <div class="p-4 card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Cambiar contraseña -->
        <div class="card shadow-sm mb-4">
            <div class="p-4 card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Eliminar cuenta -->
        <div class="card shadow-sm border-danger mb-4">
            <div class="p-4 card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
        
    </div>
</x-app-layout>
