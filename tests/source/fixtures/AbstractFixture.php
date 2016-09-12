<?php

namespace app\tests\source\fixtures;

use yii\test\ActiveFixture;
use app\traits\ExceptionsTrait;

abstract class AbstractFixture extends ActiveFixture
{
    use ExceptionsTrait;
}
