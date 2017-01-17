<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\widgets\Menu;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует меню нижней части страницы
 */
class BottomMenuWidget extends Menu
{
    use ExceptionsTrait;
    
    /**
     * @var array HTML атрибуты, которые будут применены к тегу-контейнеру меню (ul по-умолчанию)
     */
    public $options = ['class'=>'bottom-menu'];
    
    public function init()
    {
        try {
            parent::init();
            
            $this->setItems();
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Формирует массив ссылок для создания меню
     */
    private function setItems()
    {
        try {
                $this->items = [
                    [
                        'label'=>\Yii::t('base', 'Mailings'),
                        'url'=>['/mailings/index']
                    ],
                ];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
