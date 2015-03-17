<?php
/**
 * Created by PhpStorm.
 * User: Maciej
 * Date: 2015-03-16
 * Time: 09:54
 */

namespace Webility\Bundle\EntityResourceBundle;


interface ResourceBundleInterface
{
    /**
     * Return array of interface => model class parameter name
     *
     * @return array
     */
    public function getModelInterfaces();
}