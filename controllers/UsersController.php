<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use yii\base\ErrorException;
use yii\helpers\Url;
use app\helpers\UserAuthenticationHelper;
use app\helpers\MappersHelper;
use app\helpers\ModelsInstancesHelper;
use app\helpers\SessionHelper;
use app\models\UsersModel;
use app\models\EmailsModel;

/**
 * Управляет работой с пользователями
 * @return string
 */
class UsersController extends AbstractBaseController
{
    /**
     * Управляет процессом создания учетной записи
     * @return string
     */
    public function actionAddUser()
    {
        try {
            $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_REGISTRATION_FORM]);
            $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
            
            if (\Yii::$app->request->isPost && $usersModel->load(\Yii::$app->request->post()) && $emailsModel->load(\Yii::$app->request->post())) {
                if ($usersModel->validate() && $emailsModel->validate()) {
                    if (!$emailsModel = MappersHelper::getEmailsByEmailOrSet($emailsModel)) {
                        throw new ErrorException('Ошибка при сохранении E-mail!');
                    }
                    $usersModel->id_emails = $emailsModel->id;
                    if (!MappersHelper::setUsersUpdateOrSet($usersModel)) {
                        throw new ErrorException('Ошибка при сохранении данных пользователя!');
                    }
                    return $this->redirect(Url::to(['users/login-user', 'added'=>true]));
                }
            }
            $renderArray = array();
            $renderArray['usersModel'] = $usersModel;
            $renderArray['emailsModel'] = $emailsModel;
            $renderArray['categoriesList'] = MappersHelper::getCategoriesList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('add-user.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом авторизации
     * @return string
     */
    public function actionLoginUser()
    {
        try {
            $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_LOGIN_FORM]);
            
            if (\Yii::$app->request->isPost && $usersModel->load(\Yii::$app->request->post())) {
                if ($usersModel->validate()) {
                    if (!UserAuthenticationHelper::fillFromForm($usersModel)) {
                        return $this->redirect(Url::to(['users/add-user', 'notexists'=>$usersModel->login]));
                    }
                    return $this->redirect(Url::to(['products-list/index']));
                }
            }
            $renderArray = array();
            $renderArray['usersModel'] = $usersModel;
            $renderArray['categoriesList'] = MappersHelper::getCategoriesList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('login-user.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом выхода из аккаунта
     * @return string
     */
    public function actionLogoutUser()
    {
        try {
            $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_LOGOUT_FORM]);
            
            if (\Yii::$app->request->isPost && $usersModel->load(\Yii::$app->request->post())) {
                if ($usersModel->validate()) {
                    if ($usersModel->id == \Yii::$app->user->id) {
                        if (!UserAuthenticationHelper::clean()) {
                            throw new ErrorException('Ошибка при попытку выхода из аккаунта!');
                        }
                        if (!SessionHelper::removeVarFromSession([\Yii::$app->params['usersKeyInSession']])) {
                            throw new ErrorException('Ошибка удалении переменной сессии!');
                        }
                    }
                }
            }
            return $this->redirect(Url::to(['products-list/index']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
