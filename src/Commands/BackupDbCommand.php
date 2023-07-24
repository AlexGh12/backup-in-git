<?php

namespace AlexGh12\BackupInGit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB as DBs;

class BackupDbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BackupInGit:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'El comando descarga una copia de la base de datos y la guarda en la carpeta ../backup_{APP_NAME}/db y sube cambios a un repositorio de git';

	public $pathBackup;

    /**
     * Execute the console command.
     */
    public function handle()
    {
		$this->createPathBackup();
		$this->verifyFolder();

		$this->info('');
		$this->info('========================================');
		$this->info(' Inciado respaldo de base de datos... 📚');
		$this->info('========================================');
		$this->info('');

		$this->runMySqlDump();
		$this->runGitPush();

		$this->info('');
		$this->info('========================================');
		$this->info(' 💾 Respando finalizado el respado! ✅  ');
		$this->info('========================================');
		$this->info('');
    }

	public function createPathBackup(){
		$this->pathBackup = base_path(config('BackupInGit.folder_backup','backup/'));
	}

	public function verifyFolder(){
		// Verificamos que exista la carpeta de destino
		if( !is_dir($this->pathBackup) ){

			$this->info('');
			$this->warn('========================================');
			$this->error(' 💾 Es necesio instalar ❌');
			$this->warn('========================================');
			$this->info('');
			$this->info('Ejecuta: php artisan BackupInGit:install');
			$this->info('');
			return;

		}
	}

	public function runMySqlDump(){
		$optionFile = $this->pathBackup."/mysqldump.cnf";

		// Contenido del archivo de opción
			$optionContent = "[mysqldump]".PHP_EOL;
			$optionContent .= "user=".env('DB_USERNAME').PHP_EOL;
			$optionContent .= "password=".env('DB_PASSWORD').PHP_EOL;
		//

		// Crea el archivo de opción
			file_put_contents($optionFile, $optionContent);
		//

		// Ruta donde deseas guardar el archivo de volcado
			$command = "mysqldump --defaults-extra-file={$optionFile} ".env('DB_DATABASE')." > ".escapeshellarg($this->pathBackup.env('DB_DATABASE').".sql");
			exec($command, $output, $returnVar);
			unlink($optionFile);
		//

		if ($returnVar != 0) {
			$this->info('');
			$this->info('========================================');
			$this->error(" Ha ocurrido un error al crear el volcado. ❌");
			$this->info('========================================');
			$this->info('');
			return;
		}

		$this->info('');
		$this->info('========================================');
		$this->info(" El volcado se ha creado correctamente. ✅");
		$this->info('========================================');
		$this->info('');
	}

	public function runGitPush(){
		$this->info('');
		$this->info('========================================');
		$this->info(' Iniciando subida a repositorio... 📤');
		$this->info('========================================');
		$this->info('');

		$comentario = 'Actualizar DBs '.date('Y-m-d H:i:s');

		chdir($this->pathBackup); // Redireccionamos a la carpeta del backup
		exec("git add -A"); // Agregamos los archivos al commit
		exec("git commit -m '{$comentario}'"); // Hacemos el commit
		exec("git push origin master");// Hacemos el push

		$this->info('');
		$this->info('========================================');
		$this->info(" Se ha subido correctamente. ✅");
		$this->info('========================================');
		$this->info('');
	}
}
