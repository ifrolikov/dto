<?php
declare(strict_types=1);

return [
	\ifrolikov\dto\DtoBuilder::class        => function (\Psr\Container\ContainerInterface $container) {
		return new \ifrolikov\dto\DtoBuilder(
			$container->get(\ifrolikov\dto\DtoBuilderFactory::class),
			new \PhpDocReader\PhpParser\UseStatementParser()
		);
	},
	\ifrolikov\dto\DtoBuilderFactory::class => function (\Psr\Container\ContainerInterface $container) {
		return new \ifrolikov\dto\DtoBuilderFactory(
			\ifrolikov\dto\DtoBuilder::class,
			$container
		);
	},
	\ifrolikov\dto\JsonDtoPacker::class     => function (\Psr\Container\ContainerInterface $container) {
		return new \ifrolikov\dto\JsonDtoPacker();
	},
	\ifrolikov\dto\ArrayPacker::class       => function (\Psr\Container\ContainerInterface $container) {
		return new \ifrolikov\dto\ArrayPacker();
	}
];