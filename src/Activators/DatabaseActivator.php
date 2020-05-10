<?php

namespace Pixel\Modules\Activators;

use Illuminate\Cache\CacheManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Schema;
use Illuminate\Config\Repository as Config;
use Illuminate\Database\QueryException;

use Pixel\Modules\Module;
use Pixel\Modules\Contracts\ActivatorInterface;

class ModulesDatabaseActivator implements ActivatorInterface
{
	/**
	 * Laravel cache instance
	 *
	 * @var CacheManager
	 */
	private $cache;

	/**
	 * Laravel config instance
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * @var string
	 */
	private $cacheKey;

	/**
	 * @var string
	 */
	private $cacheLifetime;

	/**
	 * Array of modules activation statuses
	 *
	 * @var array
	 */
	private $modules;

	/**
	 * Database table of all modules
	 *
	 * @var string
	 */
	protected $table;

	public function __construct(Container $app)
	{
		$this->cache = $app['cache'];
		$this->config = $app['config'];
		$this->table = $this->config('table');
		$this->cacheKey = $this->config('cache-key');
		$this->cacheLifetime = $this->config('cache-lifetime');
		$this->modules = $this->getModules();
	}

	public function enable(Module $module): void
	{
		$this->modules[$module->getName()] = true;
		$this->writeDB($module->getName(), true, $module);
		$this->flushCache();
	}

	public function disable(Module $module): void
	{
		$this->modules[$module->getName()] = false;
		$this->writeDB($module->getName(), false, $module);
		$this->flushCache();
	}

	public function hasStatus(Module $module, bool $status): bool
	{
		if (!isset($this->modules[$module->getName()])) {
			return $status === false;
		}

		return boolval($this->modules[$module->getName()]) === $status;
	}

	public function setActive(Module $module, bool $active): void
	{
		$this->modules[$module->getName()] = $active;
		$this->writeDB($module->getName(), $active, $module);
		$this->flushCache();
	}

	public function setActiveByName(string $name, bool $status): void
	{
		$this->modules[$name] = $status;
		$this->writeDB($name, $status);
		$this->flushCache();
	}

	public function delete(Module $module): void
	{
		if (!isset($this->modules[$module->getName()])) {
			return;
		}

		unset($this->modules[$module->getName()]);

		$this->table()->where('name', $module->getName())->delete();
		$this->flushCache();
	}

	public function reset(): void
	{
		$this->table()->truncate();
		$this->modules = array();
		$this->flushCache();
	}

	private function table()
	{
		return DB::table($this->table);
	}

	private function getModules(): array
	{
		if (!$this->config->get('modules.cache.enabled')) {
			return $this->readDB();
		}

		return $this->cache->remember($this->cacheKey, $this->cacheLifetime, function () {
			return $this->readDB();
		});
	}

	private function readDB(): array
	{
		try {
			return $this->table()->pluck('active', 'name')->toArray();
		} catch (QueryException $e) {
			return array();
		}
	}

	private function writeDB(string $name, bool $status)
	{
		if (!$this->table()->where('name', $name)->first()) {
			$this->table()->insert([
				'name' => $name,
				'active' => false
			]);
		}

		$this->table()->where('name', $name)->update(['active' => $status]);
	}

	private function config(string $key, $default = null)
	{
		return $this->config->get('modules.activators.database.' . $key, $default);
	}

	private function flushCache(): void
	{
		$this->cache->forget($this->cacheKey);
	}
}
