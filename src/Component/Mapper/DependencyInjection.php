<?php

declare(strict_types=1);

use Hisham\CodeLab\Component\Mapper\GenericDtoMapper;
use Hisham\CodeLab\Component\Mapper\DtoMapperInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->set(DtoMapperInterface::class, GenericDtoMapper::class)
        ->args([
            service(LoggerInterface::class),
            service('debug.stopwatch'),
        ]);
};