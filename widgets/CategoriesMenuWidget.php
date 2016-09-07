<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractMenuWidget;

/**
 * Формирует меню
 */
class CategoriesMenuWidget extends AbstractMenuWidget
{
    public function init()
    {
        parent::init();
        
        if (empty($this->route)) {
            $this->route = '/products-list/index';
        }
    }
    
    /**
     * Формирует HTML меню
     * @return string
     */
    public function run()
    {
        try {
            if (empty($this->objectsList)) {
                throw new ErrorException('Отсуствуют данные для построения меню!');
            }
            if (empty($this->route)) {
                throw new ErrorException('Отсуствует route для построения меню!');
            }
            
            foreach ($this->objectsList as $object) {
                $routeArray = array($this->route);
                if ($object->seocode) {
                    $routeArray['categories'] = $object->seocode;
                }
                $this->_menu .= '<li><a href="' . Url::to($routeArray) . '">' . $object->name . '</a>';
                if (!empty($object->subcategory)) {
                    $this->_menu .= '<ul>';
                    foreach ($object->subcategory as $subcategory) {
                        if ($subcategory->seocode) {
                            $routeArray['subcategory'] = $subcategory->seocode;
                        }
                        $this->_menu .= '<li><a href="' . Url::to($routeArray) . '">' . $subcategory->name . '</a></li>';
                    }
                    $this->_menu .= '</ul>';
                }
                $this->_menu .= '</li>';
            }
            return '<ul>' . $this->_menu . '</ul>';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
