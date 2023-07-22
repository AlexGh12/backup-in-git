<?php

namespace AlexGh12\BackupInGit;

use AlexGh12\BackupInGit\Console\database;
use Illuminate\Support\ServiceProvider;

class BackupInGitServiceProvider extends ServiceProvider
{
	/**
     * Inicia los servicios de la aplicación
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\BackupDB::class,
				Commands\BackupDBs::class,
            ]);
        }

		$this->publishes([
			__DIR__.'/../config/BackupInGit.php' => base_path('config/BackupInGit.php'),
		], [
			'BackupInGit',
			'BackupInGit:config'
		]);
    }

    /**
     * Registra los servicios de la aplicación.
     *
     * @return void
     */
    public function register()
    {
    }
}
