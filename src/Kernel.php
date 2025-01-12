<?php

namespace Hisham\CodeLab;

use Hisham\CodeLab\Component\Symfony\CompilerPass\AutoMapperPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * @param ContainerBuilder $container
     *
     * @return void
     */
    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AutoMapperPass());
    }
}
