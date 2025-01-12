<?php

namespace Hisham\CodeLab\Component\Symfony\CompilerPass;

use Hisham\CodeLab\Component\Attribute\Mapper;
use Hisham\CodeLab\Component\Mapper\AutoMapper;
use Hisham\CodeLab\Component\Mapper\MapperInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class AutoMapperPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     * @throws ReflectionException
     */
    public function process(ContainerBuilder $container): void
    {
        $genericMapperDefinition = $container->getDefinition(MapperInterface::class);

        $declaredMappers = array_filter(
            get_declared_classes(),
            fn(string $className) => in_array(MapperInterface::class, class_implements($className)),
        );

        $addDefault = function (string $mapperClass) use (&$declaredMappers) {
            if (!in_array($mapperClass, $declaredMappers)) {
                $declaredMappers[] = $mapperClass;
            }
        };

        $addDefault(AutoMapper::class);

        foreach ($declaredMappers as $declaredMapper) {
            $reflection = new ReflectionClass($declaredMapper);
            $mapperAttr = $reflection->getAttributes(Mapper::class)[0] ?? null;

            // If mapper does not declare mapper attribute, then required to auto-discover
            if (!$mapperAttr) {
                continue;
            }

            /**
             * @var Mapper $attrInstance
             */
            $attrInstance = $mapperAttr->newInstance();
            if (!$attrInstance->isValid()) {
                continue;
            }

            // Get data to declare mapper definitions
            $dto = $attrInstance->dto;
            $entity = $attrInstance->entity;
            $mapperDefinition = $this->resolveMapperDefinition($container, $declaredMapper);

            // Declare mapper definitions
            $genericMapperDefinition->addMethodCall('addMapper', [$dto, $mapperDefinition]);
            $genericMapperDefinition->addMethodCall('addMapper', [$entity, $mapperDefinition]);
        }
    }

    /**
     * Get mapper container definition or create a new one
     *
     * @param ContainerBuilder $container
     * @param string           $mapper
     *
     * @return Definition
     */
    private function resolveMapperDefinition(ContainerBuilder $container, string $mapper): Definition
    {
        if ($container->hasDefinition($mapper)) {
            return $container->getDefinition($mapper);
        }

        $definition = new Definition($mapper);
        $definition->setAutowired(true);

        $container->setDefinition($mapper, $definition);

        return $definition;
    }

}
