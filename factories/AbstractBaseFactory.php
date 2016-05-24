<?php

namespace app\factories;

use yii\base\Object;

/**
 * Определяет интерфейс классов-наследников, конструирующих объекты из строк БД
 */
abstract class AbstractBaseFactory extends Object
{
    abstract public function getObjects();
}
