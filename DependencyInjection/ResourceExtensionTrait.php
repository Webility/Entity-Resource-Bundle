<?php

namespace Webility\Bundle\EntityResourceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
trait ResourceExtensionTrait
{
    protected function loadResourcesConfig($appName, array $config, ContainerBuilder $container)
    {
        $classes = isset($config['classes']) ? $config['classes'] : array();
        $defaultRepositoryClass = $config['default_repository'];

        $this->mapClassParameters($classes, $container, $appName, $defaultRepositoryClass);
        $this->prepareRepositoriesServices($classes, $container, $appName);

        if ($container->hasParameter('webility_entity_resource.config.classes')) {
            $classes = array_merge($classes, $container->getParameter('webility_entity_resource.config.classes'));
        }

        $container->setParameter('webility_entity_resource.config.classes', $classes);
    }

    protected function mapClassParameters($classes, ContainerBuilder $container, $appName, $defaultRepositoryClass)
    {
        foreach($classes as $model => $serviceClasses){
            foreach($serviceClasses as $service => $class){
                if($service == 'repository' && $class === null){
                    $class = $defaultRepositoryClass ?: 'Doctrine\ORM\EntityRepository';
                }

                $container->setParameter($this->getParameterName($appName, $service, $model), $class);
            }
        }
    }

    protected function prepareRepositoriesServices($classes, ContainerBuilder $container, $appName)
    {
        foreach(array_keys($classes) as $model){
            $repositoryClassKey = $this->getParameterName($appName, 'repository', $model);
            $repositoryClass = $container->getParameter($repositoryClassKey);

            $repositoryDefinition = new Definition($repositoryClass);
            $repositoryDefinition->setFactoryService('doctrine.orm.entity_manager')
                ->setFactoryMethod('getRepository')
                ->setArguments([
                    $container->getParameter($this->getParameterName($appName, 'model', $model))
                ]);

            $container->setDefinition($appName.'.repository.'.$model, $repositoryDefinition);
        }

    }

    protected function getParameterName($appName, $service, $model, $suffix = 'class')
    {
        $name = sprintf('%s.%s.%s', $appName, $service, $model);

        if($suffix !== null){
            $name .= '.'.$suffix;
        }

        return $name;
    }
}
