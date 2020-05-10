<?php

namespace Pixel\Modules;

use Illuminate\Support\ServiceProvider;
use Pixel\Modules\Providers\BootstrapServiceProvider;
use Pixel\Modules\Providers\ConsoleServiceProvider;
use Pixel\Modules\Providers\ContractsServiceProvider;

abstract class ModulesServiceProvider extends ServiceProvider
{
	/**
	 * Booting the package.
	 */
	public function boot()
	{
	}

	/**
	 * Register all modules.
	 */
	public function register()
	{
	}

	/**
	 * Register all modules.
	 */
	protected function registerModules()
	{
		$this->app->register(BootstrapServiceProvider::class);
	}

	/**
	 * Register package's namespaces.
	 */
	protected function registerNamespaces()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/modules.php', 'modules');

		$this->publishes([
			__DIR__ . '/../config/publish.php' => config_path('modules.php'),
		], 'modules-config');

		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

		$this->publishes([
			__DIR__ . '/../database/migrations' => database_path('migrations'),
		], 'modules-migrations');
	}

	/**
	 * Register the service provider.
	 */
	abstract protected function registerServices();

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [Contracts\RepositoryInterface::class, 'modules'];
	}

	/**
	 * Register providers.
	 */
	protected function registerProviders()
	{
		$this->app->register(ConsoleServiceProvider::class);
		$this->app->register(ContractsServiceProvider::class);
	}
}
