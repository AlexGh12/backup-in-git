<?php

namespace AlexGh12\BackupInGit\Commands;


use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'BackupInGit:install';

    protected $description = 'Publish BackupInGit configuration';

	public $pathBackup;

    public function handle()
    {
		$this->publishConfig();
		$this->createPathBackup();
		$this->installGitIgnore();
		$this->checkFolder();
    }

	public function publishConfig(){
		$this->call('BackupInGit:publish');
	}

	public function createPathBackup(){
		$this->pathBackup = base_path(config('BackupInGit.folder_backup','backup/'));
	}

	public function installGitIgnore(){

		/* -------------------- Agregarmos backup a .gitignore ------------------- */
			// Ruta y nombre del archivo .gitignore
			$gitignore = base_path(".gitignore");

			// Carpeta a validar
			$folderToIgnore = config('BackupInGit.folder_backup','backup/');

			// Leer el contenido del archivo .gitignore
			$gitIgnoreContent = file_get_contents($gitignore);

			// Verificar si la carpeta se encuentra en el archivo .gitignore
			if (strpos($gitIgnoreContent, $folderToIgnore) === false) {
				// Agregar la línea al archivo .gitignore
				$newGitIgnoreContent = $gitIgnoreContent . PHP_EOL . $folderToIgnore . PHP_EOL;

				// Escribir el contenido actualizado en el archivo .gitignore
				file_put_contents($gitignore, $newGitIgnoreContent);

				$this->info('');
				$this->warn('========================================');
				$this->info(" 📁 ignoramos la carpeta de ".config('BackupInGit.folder_backup','backup/')." en git 🫥");
				$this->warn('========================================');
				$this->info('');
			}else{
				$this->info('');
				$this->warn('========================================');
				$this->info(" 📁 Ya estaba ignoramos ✅ la carpeta: ".config('BackupInGit.folder_backup','backup/')." en git 🫥");
				$this->warn('========================================');
				$this->info('');
			}
		/* -------------------------------------------------------------------------- */
	}

	public function checkFolder(){
		// Verificamos que exista la carpeta de destino
		if( !is_dir($this->pathBackup) ){

			$this->info('');
			$this->warn('========================================');
			$parametro = $this->ask(' 📁 La carpeta no existe, ¿desea crearla? (y/n)');
			$this->warn('========================================');
			$this->info('');

			if($parametro == 'y'){
				if (mkdir($this->pathBackup, 0777, true)) {
					$this->info('');
					$this->warn('========================================');
					$this->info(" 📁 El directorio se ha creado correctamente. ✅");
					$this->warn('========================================');
					$this->info('');

					$this->createRepoGit();

				} else {
					$this->info('');
					$this->warn('========================================');
					$this->info(" 📁 No hay permisos para crear el directorio. ❌");
					$this->warn('========================================');
					$this->info('');
					return;
				}
			}else{
				$this->info('');
				$this->warn('========================================');
				$this->error(' 💾 No se creo la carpeta ❌');
				$this->warn('========================================');
				$this->info('');
				return;
			}

		}else{
			$this->info('');
			$this->warn('========================================');
			$this->info(' 📁 La carpeta ya existe ✅ ');
			$this->warn('========================================');
			$this->info('');
		}
	}

	public function createRepoGit(){
		// Agregamos repositorio git

		$this->info('');
		$this->warn('========================================');
		$repository = $this->ask(' 📁 ¿Me podrias proporcionar el repositorio remoto? (git@gitlab.com:hd-latam/backup/***.git) ');
		$this->warn('========================================');
		$this->info('');

		$gitInitCommand = 'git init';
		$gitRemoteAddCommand = 'git remote add origin '.$repository;
		$gitPullCommand = 'git pull origin master';

		chdir($this->pathBackup);
		exec($gitInitCommand);
		exec($gitRemoteAddCommand);
		exec($gitPullCommand);

		$this->info('');
		$this->warn('========================================');
		$this->info(' 📁 Repositorio remoto Agregado y sincronizado ✅');
		$this->warn('========================================');
		$this->info('');
	}
}
