<?php
declare(strict_types=1);

return [
    \ifrolikov\dto\DtoBuilder::class => function (\Psr\Container\ContainerInterface $container) {
        return new \ifrolikov\dto\DtoBuilder(
            $container->get(\ifrolikov\dto\DtoBuilderFactory::class),
            $container->get(\ifrolikov\dto\PhpDocManager::class)
        );
    },
    \ifrolikov\dto\DtoBuilderFactory::class => function (\Psr\Container\ContainerInterface $container) {
        return new \ifrolikov\dto\DtoBuilderFactory(
            \ifrolikov\dto\DtoBuilder::class,
            $container
        );
    },
    \ifrolikov\dto\JsonDtoPacker::class => function (\Psr\Container\ContainerInterface $container) {
        return new \ifrolikov\dto\JsonDtoPacker($container->get(\ifrolikov\dto\ArrayPacker::class));
    },
    \ifrolikov\dto\ArrayPacker::class => function (\Psr\Container\ContainerInterface $container) {
        return new \ifrolikov\dto\ArrayPacker();
    },
    \ifrolikov\dto\PhpDocManager::class => function (\Psr\Container\ContainerInterface $container) {
        return new \ifrolikov\dto\PhpDocManager(
            new \PhpDocReader\PhpParser\UseStatementParser()
        );
    },
    \ifrolikov\dto\DtoFakeDataGenerator::class => function (\Psr\Container\ContainerInterface $container) {
        return new \ifrolikov\dto\DtoFakeDataGenerator(
            $container->get(\ifrolikov\dto\PhpDocManager::class)
        );
    }
];