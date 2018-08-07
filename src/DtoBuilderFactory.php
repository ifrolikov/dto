<?php
declare(strict_types=1);

namespace IFrol\RESTTools;

use IFrol\RESTTools\Interfaces\DtoBuilderFactoryInterface;
use IFrol\RESTTools\Interfaces\DtoBuilderInterface;
use Psr\Container\ContainerInterface;

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
     * @var ContainerInterface
     */
    private $ioC;

    /**
     * DtoBuilderFactoryInterface constructor.
     * @param string $dtoBuilderClass
     * @param ContainerInterface $ioC
     */
    public function __construct(string $dtoBuilderClass, ContainerInterface $ioC)
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