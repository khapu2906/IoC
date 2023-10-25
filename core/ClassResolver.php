<?php

namespace Core;

use Core\Contracts\IContainer;
use ReflectionClass;

class ClassResolver
{
	public function __construct(
		protected IContainer $container,
		protected string $namespace,
		protected array $args = []
	) {
	}

	public function getInstance(): ?object
	{
		/**
		 * Check namespace exist in container with type object or class
		 *   if it is object, so return it directly 
		 *   if it is a class, it will define $this->namespace = binding
		 * 
		 */
		if ($this->container->has($this->namespace)) {
			$binding = $this->container->get($this->namespace);

			if (is_object($binding)) {
				return $binding;
			}

			$this->namespace = $binding;
		}

		/**
		 * Check info of class need to instance 
		 *   get dependency of class
		 */
		$refClass = new ReflectionClass($this->namespace);

		$constructor = $refClass->getConstructor();

		// Handle parameter of class by constructor
		if ($constructor && $constructor->isPublic()) {
			if (count($constructor->getParameters()) > 0) {
				$argumentResolver = new ParametersResolver(
					$this->container,
					$constructor->getParameters(),
					$this->args
				);
				$this->args = $argumentResolver->getArguments();
			}
			return $refClass->newInstanceArgs($this->args);
		}

		return $refClass->newInstanceWithoutConstructor();
	}
}
