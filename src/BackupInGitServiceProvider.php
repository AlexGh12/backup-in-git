<?php

namespace AlexGh12\BackupInGit;

use AlexGh12\BackupInGit\Console\database;
use Illuminate\Support\ServiceProvider;

class BackupInGitServiceProvider extends ServiceProvider
{
	/**
     * Bootstrap the application services.
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
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
