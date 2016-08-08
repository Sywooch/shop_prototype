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
    public function run()
    {
        try {
            if (empty($this->objectsList)) {
                throw new ErrorException('Отсуствуют данные для построения меню!');
            }
            
            foreach ($this->objectsList as $object) {
                $this->_routeArray[] = $object->route;
                $this->_menu .= '<li><a href="' . Url::to($this->_routeArray) . '">' . $object->name . '</a></li>';
                $this->_routeArray = array();
            }
            return '<ul>' . $this->_menu . '</ul>';
        } catch (\Exception $e) {
            $this->throwException($e, __METHOD__);
        }
    }
}
