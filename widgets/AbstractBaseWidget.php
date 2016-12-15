<?php

namespace app\widgets;

use yii\base\Widget;
use app\exceptions\ExceptionsTrait;

/**
 * Абстрактный класс для widgets
 */
abstract class AbstractBaseWidget extends Widget
{
    use ExceptionsTrait;
}
