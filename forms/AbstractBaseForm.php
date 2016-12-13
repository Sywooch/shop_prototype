<?php

namespace app\forms;

use yii\base\Model;
use app\exceptions\ExceptionsTrait;

/**
 * Базовый класс форм
 */
abstract class AbstractBaseForm extends Model
{
    use ExceptionsTrait;
}
