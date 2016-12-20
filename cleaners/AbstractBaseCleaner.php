<?php

namespace app\cleaners;

use yii\base\Object;
use app\exceptions\ExceptionsTrait;
use app\cleaners\CleanerInterface;

/**
 * Абстрактный класс для cleaners
 */
abstract class AbstractBaseCleaner extends Object implements CleanerInterface
{
    use ExceptionsTrait;
}
