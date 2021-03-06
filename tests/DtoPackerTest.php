<?php
declare(strict_types=1);

namespace ifrolikov\dto\Tests;

use ifrolikov\dto\ArrayPacker;
use ifrolikov\dto\DtoBuilder;
use ifrolikov\dto\DtoBuilderFactory;
use ifrolikov\dto\DtoPackerFactory;
use ifrolikov\dto\JsonDtoPacker;
use ifrolikov\dto\PhpDocManager;
use ifrolikov\dto\Tests\Data\BarDto;
use ifrolikov\dto\Tests\Data\BeerDto;
use ifrolikov\dto\Tests\Data\CafeDto;
use ifrolikov\dto\Tests\Data\IoC;
use PhpDocReader\PhpParser\UseStatementParser;
use PHPUnit\Framework\TestCase;

/**
 * Class DtoPackerTest
 * @package ifrolikov\dto\Tests
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
            return new DtoBuilder($ioc->get(DtoBuilderFactory::class), new PhpDocManager(new UseStatementParser()));
        });
        $ioc->add(JsonDtoPacker::class, function (IoC $ioc) {
            return new JsonDtoPacker(new ArrayPacker());
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