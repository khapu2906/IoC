<?php

namespace Core;

use Core\Contracts\IContainer;
use Core\Contracts\IProvider;

class ServiceProvider implements IProvider
{
	public function __construct(public IContainer $app)
	{
	}

	public function register(): void
	{
	}

	public function boot(): void
	{
	}
}
