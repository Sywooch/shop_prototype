<?php

namespace app\widgets;

use yii\base\ErrorException;
use yii\widgets\Menu;
use app\exceptions\ExceptionsTrait;
use app\finders\PurchasesIdUserFinder;

/**
 * Формирует меню раздела настроек аккаунта
 */
class AdminUserMenuWidget extends Menu
{
    use ExceptionsTrait;
    
    /**
     * @var int
     */
    private $id_user;
    
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
            if (empty($this->id_user)) {
                throw new ErrorException($this->emptyError('id_user'));
            }
            
            $finder = \Yii::$app->registry->get(PurchasesIdUserFinder::class, [
                'id_user'=>$this->id_user
            ]);
            $purchasesArray = $finder->find();
            
            $this->items = [
                [
                    'label'=>\Yii::t('base', 'General data'),
                    'url'=>['/admin/user-detail', \Yii::$app->params['userId']=>$this->id_user]
                ],
            ];
            
            if (!empty($purchasesArray)) {
                $this->items[] = [
                    'label'=>\Yii::t('base', 'Orders'),
                    'url'=>['/admin/user-orders', \Yii::$app->params['userId']=>$this->id_user]
                ];
            }
            
            $this->items = array_merge($this->items, [
                [
                    'label'=>\Yii::t('base', 'Change data'),
                    'url'=>['/admin/user-data', \Yii::$app->params['userId']=>$this->id_user]
                ],
                [
                    'label'=>\Yii::t('base', 'Change password'),
                    'url'=>['/admin/user-password', \Yii::$app->params['userId']=>$this->id_user]
                ],
                [
                    'label'=>\Yii::t('base', 'Manage subscriptions'),
                    'url'=>['/admin/user-subscriptions', \Yii::$app->params['userId']=>$this->id_user]
                ],
            ]);
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminUserDetailBreadcrumbsWidget::id_user
     * @param Model $id_user
     */
    public function setId_user(int $id_user)
    {
        try {
            $this->id_user = $id_user;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
