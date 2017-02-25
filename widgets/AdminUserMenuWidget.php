<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
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
     * @var UsersModel
     */
    private $usersModel;
    
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
            if (empty($this->usersModel)) {
                throw new ErrorException($this->emptyError('usersModel'));
            }
            
            $finder = \Yii::$app->registry->get(PurchasesIdUserFinder::class, [
                'id_user'=>$this->usersModel->id
            ]);
            $purchasesArray = $finder->find();
            
            $email = $this->usersModel->email->email;
            
            $this->items = [
                [
                    'label'=>\Yii::t('base', 'General data'),
                    'url'=>['/admin/user-detail', \Yii::$app->params['userEmail']=>$email]
                ],
            ];
            
            if (!empty($purchasesArray)) {
                $this->items[] = [
                    'label'=>\Yii::t('base', 'Orders'),
                    'url'=>['/admin/user-orders', \Yii::$app->params['userEmail']=>$email]
                ];
            }
            
            $this->items = array_merge($this->items, [
                [
                    'label'=>\Yii::t('base', 'Change data'),
                    'url'=>['/admin/user-data', \Yii::$app->params['userEmail']=>$email]
                ],
                [
                    'label'=>\Yii::t('base', 'Change password'),
                    'url'=>['/admin/user-password', \Yii::$app->params['userEmail']=>$email]
                ],
                [
                    'label'=>\Yii::t('base', 'Manage subscriptions'),
                    'url'=>['/admin/user-subscriptions', \Yii::$app->params['userEmail']=>$email]
                ],
            ]);
            
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
    
    /**
     * Присваивает значение AdminUserDetailBreadcrumbsWidget::usersModel
     * @param Model $usersModel
     */
    public function setUsersModel(Model $usersModel)
    {
        try {
            $this->usersModel = $usersModel;
        } catch (\Throwable $t) {
            $this->throwException($t, __METHOD__);
        }
    }
}
