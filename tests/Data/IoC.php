<?php
declare(strict_types=1);

namespace IFrol\RESTTools\Tests\Data;

use IFrol\RESTTools\Interfaces\IoCInterface;

/**
 * Class IoC
 * @package IFrol\RESTTools\Tests\Data
 */
class IoC implements IoCInterface
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
     * @param string $alias
     * @return mixed
     * @throws \Exception
     */
    public function get(string $alias)
    {
        if (!isset($this->dependencies)) {
            throw new \Exception("not found dependency " . $alias);
        }
        return $this->dependencies[$alias]($this);
    }
}