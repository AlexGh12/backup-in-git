<p align="center"><img src="art/logo.svg" alt="Logo Alex Gh"></p>

<p align="center">
    <a href="https://packagist.org/packages/alexgh12/backup-in-git">
        <img src="https://img.shields.io/packagist/dt/alexgh12/backup-in-git" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/alexgh12/backup-in-git">
        <img src="https://img.shields.io/packagist/v/alexgh12/backup-in-git" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/alexgh12/backup-in-git">
        <img src="https://img.shields.io/packagist/l/alexgh12/backup-in-git" alt="License">
    </a>
</p>

# Introducción

Respalda tus bases de datos productivas en un repositorio de git. Ejecuta un comenado de laravel para realizar el respado, el mismo comando hace un commit y sube cambios al repositorio, esta pensado para que lo dejes programado y se hagan respaldos automaticamente.

## Instalación

```bash
composer require AlexGh12/backup-in-git
```

## Uso

La primera ves que se ocupe, se tiene que ejecutar en la terminal para configurar el repositorio.

```bash
php artisan backup:db
```

Preguntara, si quieres crear la carpta para alojar el nuevo repositorio.
y despues solicita el repositorio

Una vez configurado podemos dejar el comando programado en: `app/Console/Kernel.php`

```php
    protected function schedule(Schedule $schedule): void
    {
        // Recomendado si tienes telescope
		$schedule->command('telescope:prune --hours=48')->daily(); 

        // Respando de Base de datos
        $schedule->command('backuo:db')->daily();
    }
```

## Licencia

AlexGh12 es de codigo abierto software con licencia [MIT](LICENSE.md).
