<?php
declare(strict_types=1);

namespace IFrol\RESTTools\Interfaces;

/**
 * Interface DtoPackerFactoryInterface
 * @package IFrol\RESTTools\Dto\Interfaces
 */
interface DtoPackerFactoryInterface
{
    /**
     * DtoPackerFactoryInterface constructor.
     * @param string $dtoPackerClass
     * @param IoCInterface $ioC
     */
    public function __construct(string $dtoPackerClass, IoCInterface $ioC);

    /**
     * @return DtoPackerInterface
     */
    public function create(): DtoPackerInterface;
}