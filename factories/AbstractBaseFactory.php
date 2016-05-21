<?php

namespace app\factories;

use yii\base\Object;

abstract class AbstractBaseFactory extends Object
{
    abstract public function getObjects();
}
