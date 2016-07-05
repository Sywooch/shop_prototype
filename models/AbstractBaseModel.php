<?php

namespace app\models;

use yii\base\Model;
use app\traits\ExceptionsTrait;

abstract class AbstractBaseModel extends Model
{
    use ExceptionsTrait;
}
