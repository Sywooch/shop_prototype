<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\controllers\AbstractBaseController;
use app\helpers\{UserAuthenticationHelper, MappersHelper, ModelsInstancesHelper, SessionHelper};
use app\models\{UsersModel, EmailsModel};

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
                    if ($emailsModelFromDb = MappersHelper::getEmailsByEmail($emailsModel)) {
                        $emailsModel = $emailsModelFromDb;
                    } else {
                        if (!MappersHelper::setEmailsInsert($emailsModel)) {
                            throw new ErrorException('Ошибка при сохранении E-mail!');
                        }
                    }
                    $usersModel->id_emails = $emailsModel->id;
                    if (!MappersHelper::setUsersInsert($usersModel)) {
                        throw new ErrorException('Ошибка при сохранении данных пользователя!');
                    }
                    if (!MappersHelper::setUsersRulesInsert($usersModel)) {
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
    
    /**
     * Формирует суммарную информацию о действиях пользователя и состоянии его аккаунта 
     * @return string
     */
    public function actionShowUserAccount()
    {
        try {
            $renderArray = array();
            $renderArray['usersModel'] = \Yii::$app->user;
            $renderArray['purchasesList'] = MappersHelper::getPurchasesForUserList(\Yii::$app->user);
            $renderArray['categoriesList'] = MappersHelper::getCategoriesList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('show-user-account.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
