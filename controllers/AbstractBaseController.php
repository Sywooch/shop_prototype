<?php

namespace app\controllers;

use yii\web\Controller;
use app\exceptions\ExceptionsTrait;

/**
 * Определяет методы, общие для разных типов контроллеров
 */
abstract class AbstractBaseController extends Controller
{
    use ExceptionsTrait;
}
