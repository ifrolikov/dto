<?php
declare(strict_types=1);

namespace ifrolikov\dto;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
	/**
	 * @var array|callable[]
	 */
	private $_dependencies;
	
	/**
	 * DefaultContainer constructor.
	 *
	 * @param callable[] $dependencies
	 */
	public function __construct(array $dependencies)
	{
		$this->_dependencies = $dependencies;
	}
	
	/**
	 * Finds an entry of the container by its identifier and returns it.
	 *
	 * @param string $id Identifier of the entry to look for.
	 *
	 * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
	 * @throws ContainerExceptionInterface Error while retrieving the entry.
	 * @throws \Exception
	 *
	 * @return mixed Entry.
	 */
	public function get($id)
	{
		if ($this->has($id))
		{
			return $this->_dependencies[$id]($this);
		}
		throw new \Exception('dependency ' . $id . ' not found');
	}
	
	/**
	 * Returns true if the container can return an entry for the given identifier.
	 * Returns false otherwise.
	 *
	 * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
	 * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
	 *
	 * @param string $id Identifier of the entry to look for.
	 *
	 * @return bool
	 */
	public function has($id)
	{
		return isset($this->_dependencies[$id]);
	}
}