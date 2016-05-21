<?php

namespace app\factories;

use yii\base\Object;

abstract class AbstractBaseFactory extends Object
{
    protected $objectsArray = array();
    abstract public function getObjects(Array $DbArray);
}
