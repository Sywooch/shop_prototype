<?php

namespace app\widgets;

use yii\base\{ErrorException,
    Model};
use app\widgets\BreadcrumbsWidget;
use app\exceptions\ExceptionsTrait;

/**
 * Формирует breadcrumbs для страницы аккаунта
 */
class AdminUserDetailBreadcrumbsWidget extends BreadcrumbsWidget
{
    use ExceptionsTrait;
    
    /**
     * @var UsersModel
     */
    private $usersModel;
    
    public function init()
    {
        try {
            if (empty($this->usersModel)) {
                throw new ErrorException($this->emptyError('usersModel'));
            }
            
            \Yii::$app->params['breadcrumbs'][] = ['label'=>\Yii::t('base', 'Basic data'), 'url'=>['/admin/index']];
            \Yii::$app->params['breadcrumbs'][] = ['label'=>\Yii::t('base', 'Users'), 'url'=>['/admin/users']];
            \Yii::$app->params['breadcrumbs'][] = ['label'=>$this->usersModel->email->email, 'url'=>['/admin/user-detail', \Yii::$app->params['userId']=>$this->usersModel->id]];
            
            parent::init();
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
