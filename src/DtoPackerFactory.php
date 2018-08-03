<?php
declare(strict_types=1);

namespace IFrol\RESTTools;

use IFrol\RESTTools\Interfaces\DtoPackerFactoryInterface;
use IFrol\RESTTools\Interfaces\DtoPackerInterface;
use IFrol\RESTTools\Interfaces\IoCInterface;

/**
 * Class DtoPackerFactory
 * @package IFrol\RESTTools\Dto
 */
class DtoPackerFactory implements DtoPackerFactoryInterface
{
    /**
     * @var string
     */
    private $dtoPackerClass;
    /**
     * @var IoCInterface
     */
    private $ioC;

    /**
     * DtoPackerFactoryInterface constructor.
     * @param string $dtoPackerClass
     * @param IoCInterface $ioC
     */
    public function __construct(string $dtoPackerClass, IoCInterface $ioC)
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