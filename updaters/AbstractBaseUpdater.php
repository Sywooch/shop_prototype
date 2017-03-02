<?php

namespace app\updaters;

use yii\base\Object;
use app\exceptions\ExceptionsTrait;

/**
 * Абстрактный класс для updaters
 */
abstract class AbstractBaseUpdater extends Object
{
    use ExceptionsTrait;
}
