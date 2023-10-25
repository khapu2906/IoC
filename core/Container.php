<?php

namespace Core;

use Core\Contracts\IContainer;
use Throwable;

class Container implements IContainer
{
	public static self $instance = null;

	/**
	 * Array contain binding of abstract
	 */
	protected array $bindings = [];

	/**
	 * Array is alias of bound class
	 */
	protected array $aliases = [];

	/**
	 * Array contain status resolve of abstract
	 *
	 * @var array
	 */
	protected array $resolvers = [];

	/**
	 * Array contain instance
	 *
	 * @var array
	 */
	protected array $instances = [];

	public static function getInstance(): static
	{
		if (null === self::$instance) {
			$instance = new static();
		}

		return $instance;
	}

	/**
	 * Binding abstract to namespace
	 *
	 * @param string $abstract
	 * @param string $namespace
	 * @return Container
	 */
	public function bind(string $abstract, string $namespace): Container
	{
		$this->bindings[$abstract] = $namespace;
		return $this;
	}

	/**
	 * Singleton class
	 *
	 * @param string $abstract
	 * @param object $namespace
	 * @return Container
	 */
	public function singleton(string $abstract, string $namespace): void
	{
		// Attach $namespace to binding
		$this->bindings[$abstract] = $namespace;

		// Resolve abstract and attract to instance
		$this->instances[$abstract] = $this->resolve($abstract);
	}

	public function get(string $abstract): string | Throwable
	{
		if ($this->has($abstract)) {
			return $this->bindings[$abstract];
		}
		throw new \Exception("Container entry not found for: {$abstract}");
	}

	public function has(string $abstract): bool
	{
		return array_key_exists($abstract, $this->bindings);
	}

	public function instance(string $abstract, object $value): void
	{
		$this->bind($abstract, $abstract);
		$this->instances[$abstract] = $value;
	}

	/**
	 * Resolve instance 
	 *  dependency invention
	 *
	 * @param string $abstract
	 * @param array $args
	 * @return object
	 */
	public function resolve(string $abstract, array $args = []): object
	{

		if ($this->_isSingleton($abstract)) {
			return $this->instances[$abstract];
		}

		$object = (new ClassResolver($this, $abstract, $args))->getInstance();

		$this->resolvers[$abstract] = true;

		return $object;
	}

	/**
	 * Resolve method of instance
	 * 
	 * @param string|object $abstract
	 * @param string $method
	 * @param array $args
	 */
	public function resolveMethod(object | string $instance, string $method, array $args = [])
	{
		if ('string' === gettype($instance)) {
			$instance = $this->resolve($instance);
		}
		return (new MethodResolver($this, $instance, $method, $args))->getValue();
	}

	/**
	 * Check abstract is resolved
	 *
	 * @param string $abstract
	 * @param array $args
	 * @return void
	 */
	private function _isResolved(string $abstract)
	{
		return isset($this->resolvers[$abstract]);
	}

	/**
	 * Check abstract is instance=
	 *
	 * @param string $abstract
	 * @return void
	 */
	private function _isSingleton(string $abstract)
	{
		return $this->_isResolved($abstract) &&
			isset($this->instances[$abstract]);
	}
}
