<?php

namespace Providers;

use Core\ServiceProvider;
use Demo\IMaterial;
use Demo\Earth;

class AppServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->app->bind(IMaterial::class, Earth::class);
	}

	public function boot(): void
	{
	}
}
