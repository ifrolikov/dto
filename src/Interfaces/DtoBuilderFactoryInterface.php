<?php
declare(strict_types=1);

namespace IFrol\RESTTools\Interfaces;

/**
 * Interface DtoBuilderFactoryInterface
 * @package IFrol\RESTTools\Dto\Interfaces
 */
interface DtoBuilderFactoryInterface
{
    /**
     * DtoBuilderFactoryInterface constructor.
     * @param string $dtoBuilderClass
     * @param IoCInterface $ioC
     */
    public function __construct(string $dtoBuilderClass, IoCInterface $ioC);

    /**
     * @return DtoBuilderInterface
     */
    public function create(): DtoBuilderInterface;
}