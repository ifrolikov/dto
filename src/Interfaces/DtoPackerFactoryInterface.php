<?php
declare(strict_types=1);

namespace ifrolikov\dto\Interfaces;

use Psr\Container\ContainerInterface;

/**
 * Interface DtoPackerFactoryInterface
 *
 * @package ifrolikov\dto\Dto\Interfaces
 */
interface DtoPackerFactoryInterface
{
    /**
     * DtoPackerFactoryInterface constructor.
     * @param string $dtoPackerClass
     * @param ContainerInterface $ioC
     */
    public function __construct(string $dtoPackerClass, ContainerInterface $ioC);

    /**
     * @return DtoPackerInterface
     */
    public function create(): DtoPackerInterface;
}