<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\exceptions\ExceptionsTrait;

/**
 * Базовый класс для моделей
 */
abstract class AbstractBaseModel extends ActiveRecord
{
    use ExceptionsTrait;
}
