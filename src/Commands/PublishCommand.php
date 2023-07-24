<?php

namespace AlexGh12\BackupInGit\Commands;


use Illuminate\Console\Command;

class PublishCommand extends Command
{
    protected $signature = 'BackupInGit:publish';

    protected $description = 'Publish BackupInGit configuration';

    public function handle()
    {
		$this->call('vendor:publish', ['--tag' => 'BackupInGit:config', '--force' => true]);
    }
}
