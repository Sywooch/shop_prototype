<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractMenuWidget;

/**
 * Формирует меню
 */
class MenuWidget extends AbstractMenuWidget
{
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
