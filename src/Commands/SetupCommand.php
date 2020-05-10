<?php

namespace Pixel\Modules\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SetupCommand extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'module:setup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Setting up modules package for first use.';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$this->generateModulesFolder();
		$this->generateAssetsFolder();
		$this->composerSetup();
	}

	/**
	 * Generate the modules folder.
	 */
	public function generateModulesFolder()
	{
		$this->generateDirectory(
			$this->laravel['modules']->config('paths.modules'),
			'Modules directory created successfully',
			'Modules directory already exist'
		);
	}

	/**
	 * Generate the assets folder.
	 */
	public function generateAssetsFolder()
	{
		$this->generateDirectory(
			$this->laravel['modules']->config('paths.assets'),
			'Assets directory created successfully',
			'Assets directory already exist'
		);
	}

	/**
	 * Generate the specified directory by given $dir.
	 *
	 * @param $dir
	 * @param $success
	 * @param $error
	 */
	protected function generateDirectory($dir, $success, $error)
	{
		if (!$this->laravel['files']->isDirectory($dir)) {
			$this->laravel['files']->makeDirectory($dir, 0755, true, true);

			$this->info($success);

			return;
		}

		$this->warn($error);
	}

	protected function composerSetup()
	{
		if (class_exists('\Nadar\PhpComposerReader\ComposerReader')) {
			$reader = new \Nadar\PhpComposerReader\ComposerReader(base_path('composer.json'));

			if (!$reader->canRead()) {
				$this->error("Unable to read json.");
			}

			if (!$reader->canWrite()) {
				$this->error("Unable to write to existing json.");
			}

			$psr4_exist = $this->psr4EntryExist($reader);
			$psr4 = new \Nadar\PhpComposerReader\Autoload($reader, 'Modules\\', 'modules/', \Nadar\PhpComposerReader\AutoloadSection::TYPE_PSR4);
			$section = new \Nadar\PhpComposerReader\AutoloadSection($reader);

			if (!$section->valid($psr4)) {
				$this->error('Invalid PSR4 entry.');
			}

			if ($section->valid($psr4) && !$psr4_exist) {
				$section->add($psr4)->save();
				$reader->runCommand('dump-autoload');

				$this->comment('Finish composer update.');
			}
		} else {
			$this->error("Unable to load composer reader.");
		}
	}

	protected function psr4EntryExist(\Nadar\PhpComposerReader\ComposerReader $reader)
	{
		$psr4_exist = false;
		$sections = new \Nadar\PhpComposerReader\AutoloadSection($reader, \Nadar\PhpComposerReader\AutoloadSection::TYPE_PSR4);

		foreach ($sections as $autoload) {
			if (Str::contains($autoload->namespace, 'Modules')) {
				$this->warn('PSR4 entry already exist');
				return true;
			}
		}

		return $psr4_exist;
	}
}
