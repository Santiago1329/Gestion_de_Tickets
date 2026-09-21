<?php

namespace App\Providers;

use Native\Desktop\Facades\Window;
use Native\Desktop\Contracts\ProvidesPhpIni;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Window::open()
        ->url('http://localhost:8000')
        ->webPreferences([
            'partition' => 'persist:gestiontics',
        ])
        ->title('Gestion TICS')
        ->width(1280)
        ->height(800);
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
            'extension=pdo_mysql',
            'extension=mysqli',
            'extension=openssl',
            'extension=curl',
            'extension=mbstring',
            'extension=fileinfo',
            'extension=gd',
            'extension=zip',
        ];
    }
}
