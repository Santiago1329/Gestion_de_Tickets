# Guia tecnica del entorno

Esta guia describe como funciona el entorno de desarrollo de Gestion de Tickets. No pretende explicar el negocio de la aplicacion, sino las piezas necesarias para instalarla, configurarla, levantarla y diagnosticarla.

**Ultima verificacion:** 2026-09-24  
**Carpeta del proyecto:** `C:\xampp\htdocs\ESPA\Gestion_de_Tickets\develop`

## 1. Mapa mental rapido

La aplicacion tiene varias piezas que se ejecutan por separado:

| Pieza | Para que sirve | Cuando se necesita |
| --- | --- | --- |
| PHP + Laravel | Backend, rutas, modelos, vistas, comandos y API | Siempre |
| MySQL de XAMPP | Base de datos, sesiones, cache y colas configuradas | Siempre |
| Vite | Compila y sirve CSS/JavaScript durante el desarrollo | Para trabajar con frontend |
| Livewire | Interfaz dinamica desde Laravel | Ya forma parte de la aplicacion |
| Reverb | WebSockets para actualizaciones en tiempo real | Si se usan eventos/chat en tiempo real |
| Scheduler | Ejecuta tareas programadas | Para monitorear dispositivos cada 5 minutos |
| Queue worker | Procesa trabajos pendientes en la tabla `jobs` | Solo si hay trabajos encolados |
| NativePHP Desktop | Permite empaquetar/ejecutar la aplicacion como escritorio | Solo para el flujo Desktop |

En desarrollo web, Apache no es estrictamente necesario porque se usa `php artisan serve`. XAMPP si es util para ejecutar MySQL y, opcionalmente, Apache.

## 2. Versiones y ubicaciones comprobadas

Estas son las versiones observadas en este equipo el 2026-09-24:

| Herramienta | Version/ruta observada | Fuente o comando |
| --- | --- | --- |
| PHP CLI | `8.3.33` en `C:\php\php.exe` | `php -v` |
| Composer | `2.9.5` en `/c/composer/composer` | `composer --version` |
| Laravel Framework | `12.64.0` | `php artisan --version` |
| Node.js | `v24.13.0` | `node --version` |
| npm | `11.6.2` | `npm --version` |
| pnpm | `11.5.0` | `pnpm --version` |
| MySQL | No se fija desde el repositorio | XAMPP; revisar desde XAMPP o `mysql --version` |

El requisito declarado por el proyecto es PHP `^8.2` y Laravel `^12.0`. Por eso PHP 8.3.33 cumple. La ruta importante es `C:\php\php.exe`: actualmente los comandos usan ese PHP, no hay que asumir que usan `C:\xampp\php\php.exe`.

Laravel tambien confirmo que el `.env` real de esta maquina usa `GestionTICS`, URL `http://localhost:8000`, broadcaster `reverb`, MySQL, cache/sesiones/colas en base de datos y zona horaria `America/Bogota`. Es normal que estos valores sean distintos de los valores genericos de `.env.example`.

Para verificarlo en cualquier momento:

```powershell
where.exe php
php -v
where.exe composer
composer --version
where.exe node
node --version
where.exe pnpm
pnpm --version
php artisan --version
```

Si `where.exe php` muestra primero otra ruta, esa sera la version que ejecutara la terminal. El orden del `PATH` manda.

## 3. Dependencias del proyecto

### PHP / Composer

Las versiones instaladas pueden cambiar al ejecutar `composer update`; las restricciones oficiales estan en `composer.json` y las versiones exactas resueltas en `composer.lock`.

| Paquete | Funcion |
| --- | --- |
| `laravel/framework` | Framework principal |
| `livewire/livewire` | Componentes reactivos sin construir una SPA completa |
| `laravel/reverb` | Servidor WebSocket de Laravel |
| `laravel/socialite` + `socialiteproviders/microsoft` | Inicio de sesion con Microsoft |
| `maatwebsite/excel` | Exportaciones/importaciones de Excel |
| `nativephp/desktop` | Ejecucion/empaquetado como app de escritorio |
| `laravel/tinker` | Consola interactiva para probar Laravel |
| `laravel/breeze` | Scaffolding de autenticacion, de desarrollo |
| `phpunit/phpunit` | Pruebas |
| `laravel/pint` | Formateo de PHP |
| `fakerphp/faker` | Datos falsos para pruebas/seeders |
| `laravel-lang/lang` | Traducciones |

### JavaScript / pnpm

El proyecto tiene `pnpm-lock.yaml`, por lo que pnpm es el gestor preferido para reproducir las dependencias frontend.

| Paquete | Funcion |
| --- | --- |
| `vite` | Servidor y compilador frontend |
| `laravel-vite-plugin` | Integra Vite con Laravel |
| `tailwindcss` + `@tailwindcss/forms` | Estilos y formularios |
| `alpinejs` | Interactividad JavaScript ligera |
| `laravel-echo` + `pusher-js` | Cliente para eventos WebSocket |
| `axios` | Peticiones HTTP desde JavaScript |
| `concurrently` | Ejecuta varios procesos a la vez |
| `postcss` + `autoprefixer` | Procesamiento CSS |

No conviene alternar `npm install` y `pnpm install` sin necesidad: cada uno puede generar o modificar su propio lockfile. El script `setup` heredado de Laravel usa npm, pero el repositorio conserva `pnpm-lock.yaml` y el flujo `dev` usa `pnpm dev`; para el trabajo diario se recomienda pnpm.

## 4. Instalacion inicial o recuperacion

Abrir una terminal en la carpeta `develop`:

```powershell
cd C:\xampp\htdocs\ESPA\Gestion_de_Tickets\develop
```

### Paso 1: servicios y base de datos

1. Abrir XAMPP.
2. Iniciar **MySQL**.
3. Crear una base de datos llamada `gestion_tickets` en phpMyAdmin o con MySQL.
4. Apache solo hace falta si se quiere servir el proyecto mediante Apache; con `php artisan serve` no es obligatorio.

La configuracion actual espera:

```text
host: 127.0.0.1
puerto: 3306
base de datos: gestion_tickets
usuario: root
contrasena: vacia (solo si asi esta configurado localmente)
```

### Paso 2: dependencias PHP

```powershell
composer install
```

`composer install` usa `composer.lock` y descarga `vendor/`. No usar `composer update` para una instalacion normal, porque puede cambiar versiones.

### Paso 3: archivo de entorno

Si no existe `.env`:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

`.env` contiene secretos y configuracion local. No se debe subir al repositorio ni copiar sus contrasenas a documentacion.

### Paso 4: dependencias frontend

```powershell
pnpm install
```

### Paso 5: estructura de base de datos

```powershell
php artisan migrate
```

Para borrar y reconstruir toda la base local, perdiendo sus datos:

```powershell
php artisan migrate:fresh --seed
```

Usar ese comando solo cuando se quiera reiniciar la base de datos de desarrollo.

## 5. Arranque diario, en orden

### Opcion recomendada: todo junto

```powershell
composer run dev
```

Ese script levanta simultaneamente:

- `php artisan serve`: backend en `http://localhost:8000`.
- `php artisan reverb:start`: WebSockets.
- `php artisan schedule:work`: scheduler de Laravel.
- `pnpm dev`: Vite, normalmente en `http://localhost:5173`.

Si un proceso falla, el comando conjunto puede cerrar los demas. Para diagnosticar es mejor ejecutar cada proceso en una terminal separada.

### Opcion diagnostica: una terminal por proceso

Terminal 1, backend:

```powershell
php artisan serve
```

Terminal 2, frontend:

```powershell
pnpm dev
```

Terminal 3, solo si se necesita tiempo real:

```powershell
php artisan reverb:start
```

Terminal 4, necesario para las tareas programadas:

```powershell
php artisan schedule:work
```

Terminal 5, solo si hay trabajos pendientes en la tabla `jobs`:

```powershell
php artisan queue:work
```

La tarea programada actual es `dispositivos:monitorear` cada cinco minutos. Hace ping a los dispositivos registrados y notifica a administradores cuando uno pasa a offline. En Windows usa el comando `ping` del sistema, por lo que el firewall y la red pueden afectar el resultado.

## 6. Variables de `.env`, explicadas

### Aplicacion

| Variable | Para que sirve |
| --- | --- |
| `APP_NAME` | Nombre de la aplicacion en textos/configuracion |
| `APP_ENV` | Entorno, normalmente `local` en desarrollo |
| `APP_KEY` | Clave de cifrado de Laravel; se genera con `key:generate` |
| `APP_DEBUG` | Muestra errores detallados. `true` solo en local |
| `APP_URL` | URL base usada para enlaces y callbacks |
| `APP_LOCALE` | Idioma principal; la configuracion del proyecto fuerza `es` |
| `APP_TIMEZONE` | No existe en el `.env` actual; la zona esta fijada en `config/app.php` como `America/Bogota` |

### Base de datos

| Variable | Para que sirve |
| --- | --- |
| `DB_CONNECTION` | Motor; actualmente `mysql` |
| `DB_HOST` / `DB_PORT` | Direccion y puerto de MySQL |
| `DB_DATABASE` | Nombre de la base |
| `DB_USERNAME` / `DB_PASSWORD` | Credenciales de MySQL |

### Persistencia interna de Laravel

| Variable | Valor actual | Efecto |
| --- | --- | --- |
| `SESSION_DRIVER` | `database` | Sesiones en la tabla `sessions` |
| `SESSION_LIFETIME` | `120` | Minutos de inactividad antes de expirar |
| `CACHE_STORE` | `database` | Cache en la tabla `cache` |
| `QUEUE_CONNECTION` | `database` | Trabajos pendientes en la tabla `jobs` |
| `FILESYSTEM_DISK` | `local` | Archivos en `storage/app` |

Por estos valores, las migraciones deben estar ejecutadas. Si faltan las tablas `sessions`, `cache` o `jobs`, la aplicacion puede fallar aunque MySQL este encendido.

### Tiempo real

| Variable | Para que sirve |
| --- | --- |
| `BROADCAST_CONNECTION` | Transporte de eventos; el ejemplo trae `log`, pero el `.env` real actual usa `reverb` |
| `REVERB_APP_ID` / `REVERB_APP_KEY` / `REVERB_APP_SECRET` | Credenciales de la aplicacion WebSocket |
| `REVERB_HOST` / `REVERB_PORT` / `REVERB_SCHEME` | Donde escucha Reverb |
| `VITE_REVERB_*` | Valores que el navegador necesita para conectarse a Reverb |

Si el chat o las notificaciones en vivo no funcionan, revisar que el broadcaster del `.env`, las variables `REVERB_*`, el cliente Vite y el proceso `php artisan reverb:start` esten alineados. El archivo `.env.example` actual no trae todas las variables de Reverb, aunque el `.env` real de esta instalacion ya esta configurado para usarlo; al preparar otra maquina hay que trasladar esa configuracion sin copiar secretos.

### Integraciones externas

| Variable | Para que sirve |
| --- | --- |
| `MICROSOFT_CLIENT_ID` | Identificador de la aplicacion registrada en Microsoft |
| `MICROSOFT_CLIENT_SECRET` | Secreto de Microsoft; nunca publicarlo |
| `MICROSOFT_TENANT_ID` | Tenant/directorio de Microsoft |
| `MICROSOFT_REDIRECT_URI` | URL a la que Microsoft devuelve el login |
| `TELEGRAM_BOT_TOKEN` | Token del bot de Telegram |
| `TELEGRAM_CHAT_ID` | Chat destino de Telegram |
| `MAIL_*` | Actualmente el mailer `log` escribe correos en logs, no los envia |
| `AWS_*` | Solo necesarios si se usa S3, SES u otro servicio AWS |
| `VITE_APP_NAME` | Variable publica para el frontend; no poner secretos con prefijo `VITE_` |

El callback de Microsoft debe coincidir exactamente con `APP_URL` y con la URL registrada en Microsoft. Si se cambia el puerto o dominio local, hay que actualizar ambos.

## 7. Archivos que controlan cada cosa

| Necesidad | Archivo |
| --- | --- |
| Versiones PHP y paquetes | [`composer.json`](composer.json) y `composer.lock` |
| Versiones JS y scripts | [`package.json`](package.json) y `pnpm-lock.yaml` |
| Variables de ejemplo | [`.env.example`](.env.example) |
| Variables reales locales | `.env` (no versionarlo) |
| Base de datos | [`config/database.php`](config/database.php) |
| Sesiones | [`config/session.php`](config/session.php) |
| Cache | [`config/cache.php`](config/cache.php) |
| Colas | [`config/queue.php`](config/queue.php) |
| WebSockets | [`config/broadcasting.php`](config/broadcasting.php) |
| Microsoft y Telegram | [`config/services.php`](config/services.php) |
| Entrada Vite | [`vite.config.js`](vite.config.js) |
| Rutas web y canales | `routes/web.php`, `routes/channels.php` |
| Tareas programadas | [`routes/console.php`](routes/console.php) |
| Comando de dispositivos | [`app/Console/Commands/MonitorearDispositivos.php`](app/Console/Commands/MonitorearDispositivos.php) |
| CSS y JavaScript | `resources/css` y `resources/js` |
| Logs | `storage/logs/laravel.log` |

## 8. Comandos de mantenimiento

```powershell
php artisan about
php artisan config:show database
php artisan route:list
php artisan migrate:status
php artisan optimize:clear
php artisan test
vendor\bin\pint
```

- `about`: resumen del entorno Laravel.
- `config:show database`: muestra la configuracion que Laravel esta leyendo.
- `route:list`: lista rutas y middleware.
- `migrate:status`: indica que migraciones faltan.
- `optimize:clear`: limpia cache de configuracion, rutas y vistas.
- `test`: ejecuta pruebas.
- `pint`: formatea PHP.

Cuando se cambia `.env` y Laravel parece seguir usando valores viejos, ejecutar:

```powershell
php artisan config:clear
php artisan cache:clear
```

## 9. Construccion para entregar

Para compilar el frontend:

```powershell
pnpm build
```

Esto genera los archivos en `public/build`. En produccion no se debe usar `APP_DEBUG=true` ni dejar secretos en el repositorio. Antes de desplegar, revisar especialmente `APP_URL`, base de datos, correo, Microsoft, Telegram, broadcaster y permisos de `storage` y `bootstrap/cache`.

## 10. Diagnostico rapido

- **`php` usa otra version:** ejecutar `where.exe php`; corregir el orden del `PATH` o invocar `C:\php\php.exe` directamente.
- **Error de conexion MySQL:** iniciar MySQL en XAMPP y revisar `DB_HOST`, `DB_PORT`, `DB_DATABASE`, usuario y contrasena.
- **Error de tabla inexistente:** ejecutar `php artisan migrate` y revisar `php artisan migrate:status`.
- **La pagina carga sin estilos:** mantener `pnpm dev` activo o ejecutar `pnpm build`.
- **No llegan cambios en vivo:** levantar Reverb, revisar `BROADCAST_CONNECTION` y las variables `REVERB_*`/`VITE_REVERB_*`.
- **No se ejecuta el monitoreo:** mantener `php artisan schedule:work` activo o ejecutar manualmente `php artisan dispositivos:monitorear`.
- **Cambios de `.env` no se reflejan:** ejecutar `php artisan optimize:clear`.
- **Falla el login Microsoft:** comprobar que `MICROSOFT_REDIRECT_URI` coincide con Microsoft y con `APP_URL`.

## Regla de oro

Antes de modificar una configuracion, buscar primero la variable en `.env`, despues su lectura en `config/*.php` o `config/services.php`, y finalmente el lugar donde se consume. No editar `vendor/` ni `public/build`; son resultados generados por Composer/Vite.
