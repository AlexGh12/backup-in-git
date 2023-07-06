<?php

namespace AlexGh12\BackupInGit;

use AlexGh12\BackupInGit\Console\database;
use Illuminate\Support\ServiceProvider;

class BackupInGit extends ServiceProvider
{
	public function register()
    {
		$this->commands([
			database::class,
		]);
    }

}
