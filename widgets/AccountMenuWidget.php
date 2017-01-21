<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\widgets\Menu;
use app\exceptions\ExceptionsTrait;
use app\finders\PurchasesIdUserFinder;

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
            if (\Yii::$app->user->isGuest === true) {
                throw new ErrorException($this->emptyError('user'));
            }
            
            $user = \Yii::$app->user->identity;
            
            $finder = \Yii::$app->registry->get(PurchasesIdUserFinder::class, ['id_user'=>$user->id]);
            $purchasesArray = $finder->find();
            
            $this->items = [
                [
                    'label'=>\Yii::t('base', 'General data'),
                    'url'=>['/account/index']
                ],
            ];
            
            if (!empty($purchasesArray)) {
                $this->items[] = [
                    'label'=>\Yii::t('base', 'Orders'),
                    'url'=>['/account/orders']
                ];
            }
            
            $this->items = array_merge($this->items, [
                [
                    'label'=>\Yii::t('base', 'Change data'),
                    'url'=>['/account/data']
                ],
                [
                    'label'=>\Yii::t('base', 'Change password'),
                    'url'=>['/account/password']
                ],
                [
                    'label'=>\Yii::t('base', 'Manage subscriptions'),
                    'url'=>['/account/subscriptions']
                ],
                [
                    'label'=>\Yii::t('base', 'Exit settings'),
                    'url'=>['/']
                ],
            ]);
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
