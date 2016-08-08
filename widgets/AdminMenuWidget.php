<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\widgets\AbstractMenuWidget;

/**
 * Формирует меню
 */
class AdminMenuWidget extends AbstractMenuWidget
{
    private $_elmsArray = array();
    
    public function run()
    {
        try {
            if (empty($this->objectsList)) {
                throw new ErrorException('Отсуствуют данные для построения меню!');
            }
            
            foreach ($this->objectsList as $object) {
                $this->_routeArray[] = $object->route;
                $elm = '<li><a href="' . Url::to($this->_routeArray) . '">' . $object->name . '</a></li>';
                if (!empty($this->first) && $object->name == $this->first) {
                    array_unshift($this->_elmsArray, $elm);
                } else {
                    $this->_elmsArray[] = $elm;
                }
                $this->_routeArray = array();
            }
            $this->_menu = implode('', $this->_elmsArray);
            return '<ul>' . $this->_menu . '</ul>';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
