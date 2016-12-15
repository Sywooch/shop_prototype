<?php

namespace app\finders;

use yii\base\Object;
use app\exceptions\ExceptionsTrait;
use app\finders\FinderInterface;

/**
 * Базовый класс для finders
 */
abstract class AbstractBaseFinder extends Object implements FinderInterface
{
    use ExceptionsTrait;
}
