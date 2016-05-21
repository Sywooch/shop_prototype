<?php

namespace app\queries;

use yii\base\Object;

abstract class AbstractBaseQueryCreator extends Object
{
    abstract public function getSelectQuery();
}
