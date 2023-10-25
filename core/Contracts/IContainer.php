<?php

namespace Core\Contracts;

use Throwable;

interface IContainer
{
	/**
	 * Get namespace of bound class
	 *
	 * @param string $abstract
	 * @return void
	 */
	public function get(string $abstract): string | Throwable;

	/**
	 * Check existence of abstract in binding
	 *
	 * @param string $abstract
	 * @return boolean
	 */
	public function has(string $abstract): bool;

	/**
	 * Bind class
	 *
	 * @param string $abstract
	 * @param object $namespace
	 * @return Container
	 */
	public function bind(string $abstract, string $namespace): self;
}
