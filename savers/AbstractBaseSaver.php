<?php

namespace app\savers;

use yii\base\Object;
use app\exceptions\ExceptionsTrait;

/**
 * Абстрактный класс для savers
 */
abstract class AbstractBaseSaver extends Object
{
    use ExceptionsTrait;
}
