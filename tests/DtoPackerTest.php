<?php
declare(strict_types=1);

namespace IFrol\RESTTools\Tests;

use IFrol\RESTTools\DtoBuilder;
use IFrol\RESTTools\DtoBuilderFactory;
use IFrol\RESTTools\DtoPackerFactory;
use IFrol\RESTTools\JsonDtoPacker;
use IFrol\RESTTools\Tests\Data\BarDto;
use IFrol\RESTTools\Tests\Data\BeerDto;
use IFrol\RESTTools\Tests\Data\CafeDto;
use IFrol\RESTTools\Tests\Data\IoC;
use PHPUnit\Framework\TestCase;

/**
 * Class DtoPackerTest
 * @package IFrol\RESTTools\Tests
 */
class DtoPackerTest extends TestCase
{
    public function testPack()
    {
        $originalJson = '{"name":"Shakespeare","bar":{"beers":[{"label":"newcastle"}],"officiants":["John Snow"]}}';

        $data = json_decode($originalJson, true);

        $ioc = new IoC();
        $ioc->add(DtoBuilderFactory::class, function (IoC $ioc) {
            return new DtoBuilderFactory(DtoBuilder::class, $ioc);
        });
        $ioc->add(DtoBuilder::class, function (IoC $ioc) {
            return new DtoBuilder($ioc->get(DtoBuilderFactory::class));
        });
        $ioc->add(JsonDtoPacker::class, function (IoC $ioc) {
            return new JsonDtoPacker();
        });
        $ioc->add(DtoPackerFactory::class, function (IoC $ioc) {
            return new DtoPackerFactory(JsonDtoPacker::class, $ioc);
        });

        try {
            /** @var DtoBuilder $dtoBuilder */
            $dtoBuilder = $ioc->get(DtoBuilder::class);
            $builderCafe = $dtoBuilder->setData($data)->build(CafeDto::class);

            /** @var DtoPackerFactory $packerFactory */
            $packerFactory = $ioc->get(DtoPackerFactory::class);
            $packerJson = $packerFactory->create()->pack($builderCafe);

            $this->assertEquals($originalJson, $packerJson);
        } catch (\Exception $exception) {
            $this->assertEquals(true, false, (string)$exception);
        }
    }
}