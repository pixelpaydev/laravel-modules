<?php

namespace Pixel\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Pixel\Modules\Contracts\RepositoryInterface;
use Pixel\Modules\Laravel\LaravelFileRepository;

class ContractsServiceProvider extends ServiceProvider
{
	/**
	 * Register some binding.
	 */
	public function register()
	{
		$this->app->bind(RepositoryInterface::class, LaravelFileRepository::class);
	}
}
