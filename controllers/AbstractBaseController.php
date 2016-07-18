<?php

namespace app\controllers;

use yii\web\Controller;
use app\traits\ExceptionsTrait;

/**
 * Определяет функции, общие для разных типов контроллеров
 */
abstract class AbstractBaseController extends Controller
{
    use ExceptionsTrait;
}
