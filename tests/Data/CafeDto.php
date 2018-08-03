<?php
declare(strict_types=1);

namespace IFrol\RESTTools\Tests\Data;

/**
 * Class CafeDto
 * @package IFrol\RESTTools\Tests\Data
 */
class CafeDto
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var BarDto
     */
    private $bar;

    /**
     * FooDto constructor.
     * @param string $name
     * @param BarDto $bar
     */
    public function __construct(string $name, BarDto $bar)
    {
        $this->name = $name;
        $this->bar = $bar;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return BarDto
     */
    public function getBar(): BarDto
    {
        return $this->bar;
    }
}