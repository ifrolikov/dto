<?php
declare(strict_types=1);

namespace IFrol\RESTTools\Interfaces;

/**
 * Interface IoCInterface
 * @package IFrol\RESTTools\Dto\Interfaces
 */
interface IoCInterface
{
    /**
     * @param string $alias
     * @return mixed
     */
    public function get(string $alias);
}