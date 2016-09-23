<?php

namespace app\controllers;

use yii\base\ErrorException;
use app\controllers\AbstractBaseController;
use app\helpers\InstancesHelper;
use app\models\{EmailsModel,
    UsersModel};

/**
 * Управляет работой с пользователями
 * @return string
 */
class UserController extends AbstractBaseController
{
    /**
     * Управляет процессом авторизации
     * @return string
     */
    public function actionLogin()
    {
        try {
            $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_AUTHENTICATION]);
            $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_AUTHENTICATION]);
            
            if (\Yii::$app->request->isPost && $emailsModel->load(\Yii::$app->request->post()) && $usersModel->load(\Yii::$app->request->post())) {
                if ($emailsModel->validate() && $usersModel->validate()) {
                    
                }
            }
            
            $renderArray = array();
            $renderArray['emailsModel'] = $emailsModel;
            $renderArray['usersModel'] = $usersModel;
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/user/login'], 'label'=>\Yii::t('base', 'Authentication')];
            
            return $this->render('login.twig', array_merge($renderArray, InstancesHelper::getInstances()));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
