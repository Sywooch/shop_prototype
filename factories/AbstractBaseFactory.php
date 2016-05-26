<?php

namespace app\factories;

use yii\base\Object;
use app\traits\ExceptionsTrait;

/**
 * Определяет интерфейс классов-наследников, конструирующих объекты из строк БД
 */
abstract class AbstractBaseFactory extends Object
{
    use ExceptionsTrait;
    
    /**
     * @var object объект класса модели для создания экземпляров
     */
    public $model;
    /**
     * @var object объект на основании данных которого создаются объекты данных,
     * объекты данных сохраняются в свойство objectsArray этого объекта
     */
    protected $_mapperObject;
    
    /**
     * Создает на основе массива строк массив объектов
     */
    abstract public function getObjects();
}
