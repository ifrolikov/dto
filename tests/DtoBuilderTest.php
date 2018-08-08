<?php
declare(strict_types=1);

namespace ifrolikov\dto\Tests;

use ifrolikov\dto\DtoBuilder;
use ifrolikov\dto\DtoBuilderFactory;
use ifrolikov\dto\Tests\Data\BarDto;
use ifrolikov\dto\Tests\Data\BeerDto;
use ifrolikov\dto\Tests\Data\CafeDto;
use ifrolikov\dto\Tests\Data\IoC;
use PHPUnit\Framework\TestCase;

/**
 * Class DtoBuilderTest
 * @package ifrolikov\dto\Tests
 */
class DtoBuilderTest extends TestCase
{
    public function testBuild()
    {
        $json = '
        {
          "name": "Shakespeare",
          "bar": {
            "beers": [
              {
                "_ignoreField": "_someValue"
              },
              {
                "label": "newcastle"
              }
            ]
          }
        }
        ';

        $data = json_decode($json, true);

        $manualCafe = new CafeDto(
            "Shakespeare",
            new BarDto([
                new BeerDto(),
                new BeerDto("newcastle")
            ])
        );

        $ioc = new IoC();
        $ioc->add(DtoBuilderFactory::class, function (IoC $ioc) {
            return new DtoBuilderFactory(DtoBuilder::class, $ioc);
        });
        $ioc->add(DtoBuilder::class, function (IoC $ioc) {
            return new DtoBuilder($ioc->get(DtoBuilderFactory::class));
        });

        try {
            /** @var DtoBuilder $dtoBuilder */
            $dtoBuilder = $ioc->get(DtoBuilder::class);
            $builderCafe = $dtoBuilder->setData($data)->build(CafeDto::class);

            $this->assertEquals($manualCafe, $builderCafe);
        } catch (\Exception $exception) {
            $this->assertEquals(true, false, (string)$exception);
        }
    }
}