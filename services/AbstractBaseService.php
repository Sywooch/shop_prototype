<?php

namespace app\services;

use yii\base\Object;
use app\exceptions\ExceptionsTrait;
use app\savers\SessionSaver;

/**
 * Базовый класс для services
 */
abstract class AbstractBaseService extends Object implements ServiceInterface
{
    use ExceptionsTrait;
}
