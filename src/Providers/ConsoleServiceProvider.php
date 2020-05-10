<?php

namespace Pixel\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Pixel\Modules\Commands\DisableCommand;
use Pixel\Modules\Commands\DumpCommand;
use Pixel\Modules\Commands\EnableCommand;
use Pixel\Modules\Commands\InstallCommand;
use Pixel\Modules\Commands\ListCommand;
use Pixel\Modules\Commands\ModuleDeleteCommand;
use Pixel\Modules\Commands\SeedCommand;
use Pixel\Modules\Commands\SetupCommand;
use Pixel\Modules\Commands\UnUseCommand;
use Pixel\Modules\Commands\UpdateCommand;
use Pixel\Modules\Commands\UseCommand;

use Pixel\Modules\Commands\LaravelModulesV6Migrator;

use Pixel\Modules\Commands\MigrateCommand;
use Pixel\Modules\Commands\MigrateRefreshCommand;
use Pixel\Modules\Commands\MigrateResetCommand;
use Pixel\Modules\Commands\MigrateRollbackCommand;
use Pixel\Modules\Commands\MigrateStatusCommand;

use Pixel\Modules\Commands\CommandMakeCommand;
use Pixel\Modules\Commands\ControllerMakeCommand;
use Pixel\Modules\Commands\EventMakeCommand;
use Pixel\Modules\Commands\FactoryMakeCommand;
use Pixel\Modules\Commands\JobMakeCommand;
use Pixel\Modules\Commands\ListenerMakeCommand;
use Pixel\Modules\Commands\MailMakeCommand;
use Pixel\Modules\Commands\MiddlewareMakeCommand;
use Pixel\Modules\Commands\MigrationMakeCommand;
use Pixel\Modules\Commands\ModelMakeCommand;
use Pixel\Modules\Commands\ModuleMakeCommand;
use Pixel\Modules\Commands\NotificationMakeCommand;
use Pixel\Modules\Commands\PolicyMakeCommand;
use Pixel\Modules\Commands\ProviderMakeCommand;
use Pixel\Modules\Commands\RequestMakeCommand;
use Pixel\Modules\Commands\ResourceMakeCommand;
use Pixel\Modules\Commands\RouteProviderMakeCommand;
use Pixel\Modules\Commands\RuleMakeCommand;
use Pixel\Modules\Commands\SeedMakeCommand;
use Pixel\Modules\Commands\TestMakeCommand;

use Pixel\Modules\Commands\PublishCommand;
use Pixel\Modules\Commands\PublishConfigurationCommand;
use Pixel\Modules\Commands\PublishMigrationCommand;
use Pixel\Modules\Commands\PublishTranslationCommand;

class ConsoleServiceProvider extends ServiceProvider
{
	/**
	 * The available commands
	 *
	 * @var array
	 */
	protected $commands = [
		DisableCommand::class,
		DumpCommand::class,
		EnableCommand::class,
		InstallCommand::class,
		ListCommand::class,
		ModuleDeleteCommand::class,
		SeedCommand::class,
		SetupCommand::class,
		UnUseCommand::class,
		UpdateCommand::class,
		UseCommand::class,

		// MigrateCommand::class,
		// MigrateRefreshCommand::class,
		// MigrateResetCommand::class,
		// MigrateRollbackCommand::class,
		// MigrateStatusCommand::class,

		// CommandMakeCommand::class,
		// ControllerMakeCommand::class,
		// EventMakeCommand::class,
		// JobMakeCommand::class,
		// ListenerMakeCommand::class,
		// MailMakeCommand::class,
		// MiddlewareMakeCommand::class,
		// NotificationMakeCommand::class,
		// ProviderMakeCommand::class,
		// RouteProviderMakeCommand::class,
		// ModuleMakeCommand::class,
		// FactoryMakeCommand::class,
		// PolicyMakeCommand::class,
		// RequestMakeCommand::class,
		// RuleMakeCommand::class,
		// MigrationMakeCommand::class,
		// ModelMakeCommand::class,
		// SeedMakeCommand::class,
		// ResourceMakeCommand::class,
		// TestMakeCommand::class,
		
		// LaravelModulesV6Migrator::class,

		PublishCommand::class,
		PublishConfigurationCommand::class,
		PublishMigrationCommand::class,
		PublishTranslationCommand::class,
	];

	/**
	 * Register the commands.
	 */
	public function register()
	{
		$this->commands($this->commands);
	}

	/**
	 * @return array
	 */
	public function provides()
	{
		return $this->commands;
	}
}
