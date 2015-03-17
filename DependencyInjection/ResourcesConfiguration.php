<?php
/**
 * Created by PhpStorm.
 * User: Maciej
 * Date: 2015-03-14
 * Time: 09:06
 */

namespace Webility\Bundle\EntityResourceBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

abstract class ResourcesConfiguration
{
    protected function setModelConfig(ArrayNodeDefinition $nodeDefinition, array $config, $defaultRepositoryClass = null){
        $childrenNode = $nodeDefinition->addDefaultsIfNotSet()->children();
        $childrenNode->scalarNode('default_repository')->defaultValue($defaultRepositoryClass)->end();
        $classesNode = $childrenNode->arrayNode('classes');


        foreach($config as $model => $modelProperties){
            $classesNode
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode($model)
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('default_model')->defaultValue(@$modelProperties['default_class'] ?: $modelProperties['class'])->end()
                            ->scalarNode('model')->defaultValue($modelProperties['class'])->end()
                            ->scalarNode('repository')->defaultValue(@$modelProperties['repository'])->end()
                        ->end()
                    ->end()
                ->end();
        }
    }
}