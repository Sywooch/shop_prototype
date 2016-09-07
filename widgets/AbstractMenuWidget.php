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
     * @var string результирующая HTML строка меню
     */
    protected $_menu;
}
