<?php

namespace app\handlers;

use yii\base\Object;
use app\exceptions\ExceptionsTrait;
use app\handlers\HandlerInterface;

/**
 * Базовый класс для обработчиков запроса
 */
abstract class AbstractBaseHandler extends Object implements HandlerInterface
{
    use ExceptionsTrait;
}
