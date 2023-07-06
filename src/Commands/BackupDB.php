<?php

namespace AlexGh12\BackupInGit\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB as DBs;

class BackupDB extends Command
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

    /**
     * Execute the console command.
     */
    public function handle()
    {
		$dumpPath = base_path('/backup/');

		/* -------------- Verificamos que exista la carpeta de destino -------------- */
			if( !is_dir($dumpPath) ){

				$this->warn('========================================');
				$parametro = $this->ask(' 📁 La carpeta no existe, ¿desea crearla? (y/n)');
				$this->info('');

				if($parametro == 'y'){
					if (mkdir($dumpPath, 0777, true)) {
						$this->info(" 📁 El directorio se ha creado correctamente. ✅");
						$this->warn('========================================');
						$this->info('');

						/* -------------------- Agregarmos backup a .gitignore ------------------- */
							// Ruta y nombre del archivo .gitignore
							$gitignore = base_path(".gitignore");

							// Carpeta a validar
							$folderToIgnore = 'backup';

							// Leer el contenido del archivo .gitignore
							$gitIgnoreContent = file_get_contents($gitignore);

							// Verificar si la carpeta se encuentra en el archivo .gitignore
							if (strpos($gitIgnoreContent, $folderToIgnore) === false) {
								// Agregar la línea al archivo .gitignore
								$newGitIgnoreContent = $gitIgnoreContent . PHP_EOL . $folderToIgnore . PHP_EOL;

								// Escribir el contenido actualizado en el archivo .gitignore
								file_put_contents($gitignore, $newGitIgnoreContent);
							}
							$this->info('');
							$this->warn('========================================');
							$this->info(" 📁 ignoramos la carpeta de backup en git 🫥");
							$this->warn('========================================');
							$this->info('');
						/* -------------------------------------------------------------------------- */

						/* ------------------------ Agregamos repositorio git ----------------------- */

							$this->info('');
							$this->warn('========================================');
							$repository = $this->ask(' 📁 ¿Me podrias proporcionar el repositorio remoto? (git@gitlab.com:hd-latam/backup/***.git) ');
							$this->warn('========================================');
							$this->info('');

							$gitInitCommand = 'git init';
							$gitRemoteAddCommand = 'git remote add origin '.$repository;
							$gitPullCommand = 'git pull origin master';

							chdir($dumpPath);
							exec($gitInitCommand);
							exec($gitRemoteAddCommand);
							exec($gitPullCommand);

							$this->info('');
							$this->warn('========================================');
							$this->info(' 📁 Repositorio remoto Agregado y sincronizado ✅');
							$this->warn('========================================');
							$this->info('');

						/* -------------------------------------------------------------------------- */


					} else {
						$this->warn('========================================');
						$this->info(" 📁 Ha ocurrido un error al crear el directorio. ❌");
						$this->warn('========================================');
						$this->info('');
						return;
					}
				}else{
					$this->warn('========================================');
					$this->error(' 💾 No se creo la carpeta, ni el respaldo ❌');
					$this->warn('========================================');
					$this->info('');
					return;
				}

			}
		/* -------------------------------------------------------------------------- */

		/* -------------------------------- mysqldump ------------------------------- */
			$this->info('');
			$this->info('========================================');
			$this->info(' Inciado respaldo de base de datos... 📚');
			$this->info('========================================');
			$this->info('');

			$optionFile = "$dumpPath/mysqldump.cnf";

			// Contenido del archivo de opción
				$optionContent = "[mysqldump]".PHP_EOL;
				$optionContent .= "user=".env('DB_USERNAME').PHP_EOL;
				$optionContent .= "password=".env('DB_PASSWORD').PHP_EOL;
			//

			// Crea el archivo de opción
				file_put_contents($optionFile, $optionContent);
			//

			// Ruta donde deseas guardar el archivo de volcado
				$command = "mysqldump --defaults-extra-file={$optionFile} ".env('DB_DATABASE')." > ".escapeshellarg($dumpPath.env('DB_DATABASE').".sql");
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
		/* -------------------------------------------------------------------------- */

		/* ----------------------- Hacemos commit y push a git ---------------------- */
			$this->info('');
			$this->info('========================================');
			$this->info(' Iniciando subida a repositorio... 📤');
			$this->info('========================================');
			$this->info('');

			$comentario = 'Actualizar DBs '.date('Y-m-d H:i:s');

			chdir($dumpPath); // Redireccionamos a la carpeta del backup
			exec("git add -A"); // Agregamos los archivos al commit
			exec("git commit -m '{$comentario}'"); // Hacemos el commit
			exec("git push origin master");// Hacemos el push

			$this->info('');
			$this->info('========================================');
			$this->info(" Se ha subido correctamente. ✅");
			$this->info('========================================');
			$this->info('');
		/* -------------------------------------------------------------------------- */

		$this->info('');
		$this->info('========================================');
		$this->info(' 💾 Respando finalizado el respado! ✅  ');
		$this->info('========================================');
		$this->info('');
    }
}
