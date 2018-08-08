<?php
declare(strict_types=1);

namespace ifrolikov\dto;

use ifrolikov\dto\Interfaces\DtoBuilderInterface;
use ifrolikov\dto\Interfaces\DtoPackerInterface;
use Psr\Container\ContainerInterface;

class Facade
{
	/** @var ContainerInterface */
	private $container;
	
	public function __construct()
	{
		$this->container = new Container($this->getDependenciesConfig());
	}
	
	/**
	 * @return array
	 */
	public function getDependenciesConfig(): array
	{
		return require __DIR__.'/config/di.php';
	}
	
	/**
	 * @return DtoBuilderInterface
	 * @throws \Exception
	 */
	public function getDtoBuilder(): DtoBuilderInterface
	{
		return $this->container->get(DtoBuilder::class);
	}
	
	/**
	 * @return DtoPackerInterface
	 * @throws \Exception
	 */
	public function getJsonDtoPacker(): DtoPackerInterface
	{
		return $this->container->get(JsonDtoPacker::class);
	}
}