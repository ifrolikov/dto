<?php
declare(strict_types=1);

namespace ifrolikov\dto\Tests\Data;

use Psr\Container\ContainerInterface;

/**
 * Class IoC
 *
 * @package ifrolikov\dto\Tests\Data
 */
class IoC implements ContainerInterface
{
    /**
     * @var array
     */
    private $dependencies = [];

    public function add(string $alias, callable $implement)
    {
        $this->dependencies[$alias] = $implement;
    }
	
	/**
	 * @param $id
	 * @return mixed
	 * @throws \Exception
	 */
    public function get($id)
    {
        if (!isset($this->dependencies)) {
            throw new \Exception("not found dependency " . $id);
        }
        return $this->dependencies[$id]($this);
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
		return isset($this->dependencies[$id]);
	}
}