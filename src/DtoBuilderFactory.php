<?php
declare(strict_types=1);

namespace IFrol\RESTTools;

use IFrol\RESTTools\Interfaces\DtoBuilderFactoryInterface;
use IFrol\RESTTools\Interfaces\DtoBuilderInterface;
use IFrol\RESTTools\Interfaces\IoCInterface;

/**
 * Class DtoBuilderFactory
 * @package IFrol\RESTTools\Dto
 */
class DtoBuilderFactory implements DtoBuilderFactoryInterface
{
    /**
     * @var string
     */
    private $dtoBuilderClass;
    /**
     * @var IoCInterface
     */
    private $ioC;

    /**
     * DtoBuilderFactoryInterface constructor.
     * @param string $dtoBuilderClass
     * @param IoCInterface $ioC
     */
    public function __construct(string $dtoBuilderClass, IoCInterface $ioC)
    {
        $this->dtoBuilderClass = $dtoBuilderClass;
        $this->ioC = $ioC;
    }

    /**
     * @return DtoBuilderInterface
     */
    public function create(): DtoBuilderInterface
    {
        return $this->ioC->get($this->dtoBuilderClass);
    }
}