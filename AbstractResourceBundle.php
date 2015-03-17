<?php
/**
 * Created by PhpStorm.
 * User: Maciej
 * Date: 2015-03-16
 * Time: 09:53
 */

namespace Webility\Bundle\EntityResourceBundle;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Webility\Bundle\EntityResourceBundle\DependencyInjection\Compiler\ResolveDoctrineTargetEntitiesPass;

class AbstractResourceBundle extends Bundle implements ResourceBundleInterface
{
    use ResourceBundleTrait;

    public function build(ContainerBuilder $container){
        $interfaces = $this->getModelInterfaces();
        if (!empty($interfaces)) {
            $container->addCompilerPass(
                new ResolveDoctrineTargetEntitiesPass(
                    $interfaces
                )
            );
        }
    }
}