<?php
declare(strict_types=1);

namespace ifrolikov\dto;

use ifrolikov\dto\Interfaces\DtoPackerFactoryInterface;
use ifrolikov\dto\Interfaces\DtoPackerInterface;
use Psr\Container\ContainerInterface;

/**
 * Class DtoPackerFactory
 * @package ifrolikov\dto\Dto
 */
class DtoPackerFactory implements DtoPackerFactoryInterface
{
    /**
     * @var string
     */
    private $dtoPackerClass;
    /**
     * @var ContainerInterface
     */
    private $ioC;

    /**
     * DtoPackerFactoryInterface constructor.
     * @param string $dtoPackerClass
     * @param ContainerInterface $ioC
     */
    public function __construct(string $dtoPackerClass, ContainerInterface $ioC)
    {
        $this->dtoPackerClass = $dtoPackerClass;
        $this->ioC = $ioC;
    }

    /**
     * @return DtoPackerInterface
     */
    public function create(): DtoPackerInterface
    {
        return $this->ioC->get($this->dtoPackerClass);
    }
}