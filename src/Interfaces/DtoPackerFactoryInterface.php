<?php
declare(strict_types=1);

namespace IFrol\RESTTools\Interfaces;

use Psr\Container\ContainerInterface;

/**
 * Interface DtoPackerFactoryInterface
 *
 * @package IFrol\RESTTools\Dto\Interfaces
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