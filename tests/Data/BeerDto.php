<?php
declare(strict_types=1);

namespace ifrolikov\dto\Tests\Data;

/**
 * Class BeerDto
 * @package ifrolikov\dto\Tests\Data
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