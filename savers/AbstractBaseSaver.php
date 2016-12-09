<?php

namespace app\savers;

use yii\base\{ErrorException,
    Model};
use app\exceptions\ExceptionsTrait;
use app\savers\{BaseTrait,
    SaverInterface};

/**
 * Абстрактный класс для savers
 */
abstract class AbstractBaseSaver extends Model implements SaverInterface
{
    use ExceptionsTrait, BaseTrait;
}
