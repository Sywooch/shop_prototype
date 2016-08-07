<?php

namespace app\widgets;

use yii\base\{Widget,
    ErrorException};
use yii\helpers\Url;
use app\traits\ExceptionsTrait;

/**
 * Формирует меню
 */
class MenuWidget extends Widget
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
    private $_menu;
    /**
     * @var array массив данных для создания URL из текущего объекта
     */
    private $_routeArray = array();
    
    public function run()
    {
        try {
            foreach ($this->objectsList as $object) {
                $this->_routeArray[] = $this->route;
                if ($object->seocode) {
                    $this->_routeArray['categories'] = $object->seocode;
                }
                $this->_menu .= '<li><a href="' . Url::to($this->_routeArray) . '">' . $object->name . '</a>';
                if (!empty($object->subcategory)) {
                    $this->_menu .= '<ul>';
                    foreach ($object->subcategory as $subcategory) {
                        if ($subcategory->seocode) {
                            $this->_routeArray['subcategory'] = $subcategory->seocode;
                        }
                        $this->_menu .= '<li><a href="' . Url::to($this->_routeArray) . '">' . $subcategory->name . '</a></li>';
                    }
                    $this->_menu .= '</ul>';
                }
                $this->_menu .= '</li>';
                $this->_routeArray = array();
            }
            return '<ul>' . $this->_menu . '</ul>';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
