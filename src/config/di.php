<?php
declare(strict_types=1);

return [
	\IFrol\RESTTools\DtoBuilder::class        => function (\Psr\Container\ContainerInterface $container) {
		return new \IFrol\RESTTools\DtoBuilder($container->get(\IFrol\RESTTools\DtoBuilderFactory::class));
	},
	\IFrol\RESTTools\DtoBuilderFactory::class => function (\Psr\Container\ContainerInterface $container) {
		return new \IFrol\RESTTools\DtoBuilderFactory(
			\IFrol\RESTTools\DtoBuilder::class,
			$container
		);
	},
	\IFrol\RESTTools\JsonDtoPacker::class     => function (\Psr\Container\ContainerInterface $container) {
		return new \IFrol\RESTTools\JsonDtoPacker();
	}
];