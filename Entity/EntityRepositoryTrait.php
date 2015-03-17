<?php
/**
 * Created by PhpStorm.
 * User: Maciej
 * Date: 2015-03-17
 * Time: 23:05
 */

namespace Webility\Bundle\EntityResourceBundle\Entity;


trait EntityRepositoryTrait
{
    public function create()
    {
        $className = $this->getClassName();
        return new $className();
    }
}