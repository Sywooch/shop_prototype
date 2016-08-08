<?php

namespace app\widgets;

use yii\base\Widget;
use app\traits\ExceptionsTrait;

/**
 * Формирует меню
 */
abstract class AbstractMenuWidget extends Widget
{
    use ExceptionsTrait;
    
    /**
     * @var array массив объектов CategoriesModel
     */
    public $objectsList;
    /**
     * @var string основной route
     */
    public $route;
    /**
     * @var string значение поля name записи, которую необходимо вывести первой
     */
    public $first;
    /**
     * @var string результирующая HTML строка меню
     */
    protected $_menu;
    /**
     * @var array массив данных для создания URL из текущего объекта
     */
    protected $_routeArray = array();
}
