<?php

namespace app\actions;

use yii\base\Action;
use app\exceptions\ExceptionsTrait;

/**
 * Базовый класс action-классов
 */
abstract class AbstractBaseAction extends Action
{
    use ExceptionsTrait;
}
