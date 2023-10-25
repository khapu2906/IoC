<?php

namespace Core\Contracts;

use Core\Contracts\IContainer;

interface IProvider
{
	public IContainer $app;

	public function register(): void;
	public function boot(): void;
}
