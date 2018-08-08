<?php
declare(strict_types=1);

namespace ifrolikov\dto\Interfaces;

use Psr\Container\ContainerInterface;

/**
 * Interface DtoBuilderFactoryInterface
 *
 * @package ifrolikov\dto\Dto\Interfaces
 */
interface DtoBuilderFactoryInterface
{
    /**
     * DtoBuilderFactoryInterface constructor.
     * @param string $dtoBuilderClass
     * @param ContainerInterface $ioC
     */
    public function __construct(string $dtoBuilderClass, ContainerInterface $ioC);

    /**
     * @return DtoBuilderInterface
     */
    public function create(): DtoBuilderInterface;
}