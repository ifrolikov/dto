<?php
declare(strict_types=1);

namespace ifrolikov\dto\Tests;

use ifrolikov\dto\DtoBuilder;
use ifrolikov\dto\DtoBuilderFactory;
use ifrolikov\dto\Facade;
use ifrolikov\dto\Tests\Data\BarDto;
use ifrolikov\dto\Tests\Data\BeerDto;
use ifrolikov\dto\Tests\Data\CafeDto;
use ifrolikov\dto\Tests\Data\IoC;
use PHPUnit\Framework\TestCase;

/**
 * Class FacadeTest
 * @package ifrolikov\dto\Tests
 */
class FacadeTest extends TestCase
{
	public function testGetDependenciesConfig()
	{
		$facade = new Facade();
		
		$this->assertEquals($facade->getDependenciesConfig(), require __DIR__.'/../src/config/di.php');
	}
}