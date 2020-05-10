<?php

namespace Pixel\Modules\Laravel;

use Pixel\Modules\FileRepository;

class LaravelFileRepository extends FileRepository
{
	/**
	 * {@inheritdoc}
	 */
	protected function createModule(...$args)
	{
		return new Module(...$args);
	}
}
