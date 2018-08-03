<?php
declare(strict_types=1);

namespace IFrol\RESTTools\Tests\Data;

/**
 * Class BeerDto
 * @package IFrol\RESTTools\Tests\Data
 */
class BeerDto
{
    /**
     * @var string
     */
    private $label;

    /**
     * BeerDto constructor.
     * @param string $label
     */
    public function __construct(string $label = "guinness")
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
}