<?php

namespace app\models;

use yii\base\Model;
use app\exceptions\ExceptionsTrait;

/**
 * Базовый класс для моделей, представляющих данные форм
 */
abstract class AbstractFormModel extends Model
{
    use ExceptionsTrait;
}
