<?php

namespace app\savers;

use yii\base\Object;
use app\exceptions\ExceptionsTrait;
use app\savers\SaverInterface;

/**
 * Абстрактный класс для savers
 */
abstract class AbstractBaseSaver extends Object implements SaverInterface
{
    use ExceptionsTrait;
}
