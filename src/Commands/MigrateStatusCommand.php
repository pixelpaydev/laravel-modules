<?php

namespace Pixel\Modules\Commands;

use Illuminate\Console\Command;
use Pixel\Modules\Migrations\Migrator;
use Pixel\Modules\Module;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrateStatusCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:migrate-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Status for all module migrations';

    /**
     * @var \Pixel\Modules\Contracts\RepositoryInterface
     */
    protected $module;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->module = $this->laravel['modules'];

        $module_name = $this->argument('module');

        if ($module_name) {
            $module_model = $this->module->findOrFail($module_name);

            return $this->migrateStatus($module_model);
        }

        foreach ($this->module->getOrdered($this->option('direction')) as $module_model) {
            $this->line('Running for module: <info>' . $module_model->getName() . '</info>');
            $this->migrateStatus($module_model);
        }
    }

    /**
     * Run the migration from the specified module.
     *
     * @param Module $module
     */
    protected function migrateStatus(Module $module)
    {
        $path = str_replace(base_path(), '', (new Migrator($module, $this->getLaravel()))->getPath());

        $this->call('migrate:status', [
            '--path' => $path,
            '--database' => $this->option('database'),
        ]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['direction', 'd', InputOption::VALUE_OPTIONAL, 'The direction of ordering.', 'asc'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
        ];
    }
}
