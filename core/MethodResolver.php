<?php

namespace Core;

use Core\Contracts\IContainer;
use ReflectionMethod;

class MethodResolver
{
	public function __construct(
		protected IContainer $container,
		protected object $instance,
		protected string $method,
		protected array $args = []
	) {
	}

	public function getValue()
	{
		$method = new ReflectionMethod(
			$this->instance,
			$this->method
		);

		$argumentResolver = new ParametersResolver(
			$this->container,
			$method->getParameters(),
			$this->args
		);

		return $method->invokeArgs(
			$this->instance,
			$argumentResolver->getArguments()
		);
	}
}
