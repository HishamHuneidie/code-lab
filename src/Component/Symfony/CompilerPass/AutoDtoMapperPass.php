<?php

namespace Hisham\CodeLab\Component\Symfony\CompilerPass;

use Hisham\CodeLab\Component\Attribute\DtoMapper;
use Hisham\CodeLab\Component\Mapper\AutoDtoMapper;
use Hisham\CodeLab\Component\Mapper\DtoMapperInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class AutoDtoMapperPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     *
     * @return void
     * @throws ReflectionException
     */
    public function process(ContainerBuilder $container): void
    {
        $genericMapperDefinition = $container->getDefinition(DtoMapperInterface::class);

        $declaredMappers = array_filter(
            get_declared_classes(),
            fn(string $className) => in_array(DtoMapperInterface::class, class_implements($className)),
        );

        $addDefault = function (string $mapperClass) use (&$declaredMappers) {
            if (!in_array($mapperClass, $declaredMappers)) {
                $declaredMappers[] = $mapperClass;
            }
        };

        $addDefault(AutoDtoMapper::class);

        foreach ($declaredMappers as $declaredMapper) {
            $reflection = new ReflectionClass($declaredMapper);
            $mapperAttr = $reflection->getAttributes(DtoMapper::class)[0] ?? null;

            // If mapper does not declare mapper attribute, then required to auto-discover
            if (!$mapperAttr) {
                continue;
            }

            /**
             * @var DtoMapper $attrInstance
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
