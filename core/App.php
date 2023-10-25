<?php

namespace Core;

use Core\Contracts\IContainer;

final class App extends Container implements IContainer
{
	public function __construct(
		protected array $config
	) {
		$this->_register();
	}

	public function handle(array $config)
	{
		$this->_handleProvider($config['provider']);
		$this->_handleAlias($config['alias']);
	}

	public function make(string $id, array $parameters  = [])
	{
		return $this->resolve($id, $parameters);
	}

	private function _register()
	{
		static::_setInstance($this);

		$this->instance('app', $this);
		$this->instance(Container::class, $this);
		$this->instance(IContainer::class, $this);
	}

	/**
	 * Instance provider 
	 *  after a provider is instanced, it will call register method
	 *  after all providers is instanced, it will call boot methods of all providers
	 *
	 * @param array $providers
	 * @return void
	 */
	private function _handleProvider(array $providers)
	{
		foreach ($providers as $value) {
			$this->singleton($value, $value);

			$provider = $this->resolve($value);
			$provider->register();
		}

		$this->_handleProviderBoot($providers);
	}

	private function _handleProviderBoot(array $providers)
	{
		foreach ($providers as $value) {
			$provider = $this->resolve($value);
			$provider->boot();
		}
	}

	private function _handleAlias(array $alias)
	{
		foreach ($alias as $key => $value) {
			$alias = $this->singleton($key, $value);
		}
	}

	private static function _setInstance(Container $container)
	{
		self::$instance = $container;
	}
}
