<?php
declare(strict_types=1);

namespace IFrol\RESTTools\Interfaces;

use Psr\Container\ContainerInterface;

/**
 * Interface DtoBuilderFactoryInterface
 *
 * @package IFrol\RESTTools\Dto\Interfaces
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