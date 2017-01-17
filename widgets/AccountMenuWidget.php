<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\widgets\Menu;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует меню раздела настроек аккаунта
 */
class AccountMenuWidget extends Menu
{
    use ExceptionsTrait;
    
    /**
     * @var array HTML атрибуты, которые будут применены к тегу-контейнеру меню (ul по-умолчанию)
     */
    public $options = ['class'=>'account-menu'];
    
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
                        'label'=>\Yii::t('base', 'General data'),
                        'url'=>['/account/index']
                    ],
                    [
                        'label'=>\Yii::t('base', 'Orders'),
                        'url'=>['/account/orders']
                    ],
                    [
                        'label'=>\Yii::t('base', 'Contact details'),
                        'url'=>['/account/contact-details']
                    ],
                    [
                        'label'=>\Yii::t('base', 'Change password'),
                        'url'=>['/account/change-password']
                    ],
                    [
                        'label'=>\Yii::t('base', 'Subscriptions'),
                        'url'=>['/account/subscriptions']
                    ],
                    [
                        'label'=>\Yii::t('base', 'Exit settings'),
                        'url'=>['/']
                    ],
                ];
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
