<?php
declare(strict_types=1);

namespace ifrolikov\dto\Tests\Data;

/**
 * Class BarDto
 * @package ifrolikov\dto\Tests\Data
 */
class BarDto
{
    /**
     * @var \ifrolikov\dto\Tests\Data\BeerDto[]
     */
    private $beers;
    /**
     * @var array|string[]
     */
    private $officiants;

    /**
     * BarDto constructor.
     * @param BeerDto[] $beers
     * @param string[] $officiants
     */
    public function __construct(array $beers, array $officiants = ['Hugo', 'John'])
    {
        $this->beers = $beers;
        $this->officiants = $officiants;
    }

    /**
     * @return BeerDto[]
     */
    public function getBeers(): array
    {
        return $this->beers;
    }

    /**
     * @return array|string[]
     */
    public function getOfficiants()
    {
        return $this->officiants;
    }
}