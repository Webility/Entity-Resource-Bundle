<?php
/**
 * Created by PhpStorm.
 * User: Maciej
 * Date: 2015-03-14
 * Time: 08:54
 */

namespace Webility\Bundle\EntityResourceBundle\EventListener;


use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

class LoadORMMetadataSubscriber implements EventSubscriber
{
    protected $classes;

    protected $entityClasses;

    function __construct($classes)
    {
        $this->classes = $classes;
    }

    protected function getEntityClassesState()
    {
        if($this->entityClasses === null){
            $this->entityClasses = [];

            foreach($this->classes as $model => $modelArguments){
                $model = $modelArguments['model'];
                $defaultModel = $modelArguments['default_model'];

                if($model != $defaultModel ) {
                    $this->entityClasses[$defaultModel] = [
                        'active' => false
                    ];
                }

                $this->entityClasses[$model] = [
                    'active' => true,
                ];

                if(isset($modelArguments['repository']) && !empty($modelArguments['repository'])){
                    $this->entityClasses[$model]['repository'] = $modelArguments['repository'];
                }
            }
        }

        return $this->entityClasses;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'loadClassMetadata',
        );
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();
        $className = $metadata->getName();

        $entityClassesState = $this->getEntityClassesState();

        if($entityClassesState) {
            if (array_key_exists($className, $entityClassesState)) {
                $metadata->isMappedSuperclass = !$entityClassesState[$className]['active'];
                if (isset($entityClassesState[$className]['repository'])) {
                    $metadata->setCustomRepositoryClass($entityClassesState[$className]['repository']);
                }
            }
        }
    }
}