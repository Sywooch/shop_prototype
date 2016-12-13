<?php

namespace app\controllers;

use yii\web\Controller;
use app\exceptions\ExceptionsTrait;

/**
 * Базовый класс контроллеров
 */
abstract class AbstractBaseController extends Controller
{
    use ExceptionsTrait;
}
