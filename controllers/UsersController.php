<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\controllers\AbstractBaseController;
use app\helpers\{UserAuthenticationHelper, MappersHelper, ModelsInstancesHelper, SessionHelper};
use app\models\{UsersModel, EmailsModel, PhonesModel, AddressModel};

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
            $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_REGISTRATION_FORM]);
            $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_REGISTRATION_FORM]);
            
            if (\Yii::$app->request->isPost && $emailsModel->load(\Yii::$app->request->post()) && $usersModel->load(\Yii::$app->request->post())) {
                if ($emailsModel->validate() && $usersModel->validate()) {
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
            $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_LOGIN_FORM]);
            $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_LOGIN_FORM]);
            
            if (\Yii::$app->request->isPost && $emailsModel->load(\Yii::$app->request->post()) && $usersModel->load(\Yii::$app->request->post())) {
                if ($emailsModel->validate() && $usersModel->validate()) {
                    if (!$emailsModel = MappersHelper::getEmailsByEmail($emailsModel)) {
                        throw new ErrorException('Ошибка при получении объекта EmailsModel в контектсе аутентификации!');
                    }
                    $usersModel->id_emails = $emailsModel->id;
                    if (!UserAuthenticationHelper::fillFromForm($usersModel)) {
                        return $this->redirect(Url::to(['users/add-user', 'notexists'=>$usersModel->id_emails->email]));
                    }
                    return $this->redirect(Url::to(['products-list/index']));
                }
            }
            $renderArray = array();
            $renderArray['usersModel'] = $usersModel;
            $renderArray['emailsModel'] = $emailsModel;
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
            $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_UPDATE_FORM]);
            if (!empty(\Yii::$app->user)) {
                \Yii::configure($usersModel, \Yii::$app->user->getDataArray());
            }
            
            $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
            if (!empty(\Yii::$app->user->emails)) {
                \Yii::configure($emailsModel, \Yii::$app->user->emails->getDataArray());
            }
            
            $phonesModel = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_UPDATE_FORM]);
            if (!empty(\Yii::$app->user->phones)) {
                \Yii::configure($phonesModel, \Yii::$app->user->phones->getDataArray());
            }
            
            $addressModel = new AddressModel(['scenario'=>AddressModel::GET_FROM_UPDATE_FORM]);
            if (!empty(\Yii::$app->user->address)) {
                \Yii::configure($addressModel, \Yii::$app->user->address->getDataArray());
            }
            
            $renderArray = array();
            $renderArray['usersModel'] = $usersModel;
            $renderArray['emailsModel'] = $emailsModel;
            $renderArray['phonesModel'] = $phonesModel;
            $renderArray['addressModel'] = $addressModel;
            $renderArray['purchasesList'] = MappersHelper::getPurchasesForUserList(\Yii::$app->user);
            $renderArray['categoriesList'] = MappersHelper::getCategoriesList();
            $renderArray = array_merge($renderArray, ModelsInstancesHelper::getInstancesArray());
            return $this->render('show-user-account.twig', $renderArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет обновлением данных пользователя
     * @return string
     */
    public function actionUpdateUser()
    {
        try {
            $usersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_UPDATE_FORM]);
            $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
            $phonesModel = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_UPDATE_FORM]);
            $addressModel = new AddressModel(['scenario'=>AddressModel::GET_FROM_UPDATE_FORM]);
            
            if (\Yii::$app->request->isPost && $emailsModel->load(\Yii::$app->request->post()) && $usersModel->load(\Yii::$app->request->post()) && $phonesModel->load(\Yii::$app->request->post()) && $addressModel->load(\Yii::$app->request->post())) {
                if ($emailsModel->validate() && $usersModel->validate() && $phonesModel->validate() && $addressModel->validate()) {
                    
                    if (empty(\Yii::$app->user->emails) || \Yii::$app->user->emails->email != $emailsModel->email) {
                         if ($result = MappersHelper::getEmailsByEmail($emailsModel)) {
                            $emailsModel = $result;
                        } else {
                            if (!MappersHelper::setEmailsInsert($emailsModel)) {
                                throw new ErrorException('Ошибка при сохранении E-mail!');
                            }
                        }
                    }
                    $usersModel->id_emails = $emailsModel->id;
                    
                    if (empty(\Yii::$app->user->phones) || \Yii::$app->user->phones->phone != $phonesModel->phone) {
                        if ($result = MappersHelper::getPhonesByPhone($phonesModel)) {
                            $phonesModel = $result;
                        } else {
                            if (!MappersHelper::setPhonesInsert($phonesModel)) {
                                throw new ErrorException('Ошибка при сохранении address!');
                            }
                        }
                    }
                    $usersModel->id_phones = $phonesModel->id;
                    
                    if (empty(\Yii::$app->user->address) || !empty(array_diff_assoc($addressModel->getDataArray(), \Yii::$app->user->address->getDataArray()))) {
                        if ($result = MappersHelper::getAddressByAddress($addressModel)) {
                            $addressModel = $result;
                        } else {
                            if (!MappersHelper::setAddressInsert($addressModel)) {
                                throw new ErrorException('Ошибка при сохранении address!');
                            }
                        }
                    }
                    $usersModel->id_address = $addressModel->id;
                    
                    if ($usersModel->id_emails && $usersModel->id == \Yii::$app->user->id) {
                        if (!empty(array_diff_assoc($usersModel->getDataForСomparison(), \Yii::$app->user->getDataForСomparison()))) {
                            if (!MappersHelper::setUsersUpdate($usersModel)) {
                                throw new ErrorException('Ошибка при обновлении users!');
                            }
                            \Yii::configure(\Yii::$app->user, $usersModel->getDataArray());
                        }
                    }
                    
                    return $this->redirect(Url::to(['users/show-user-account']));
                }
            }
            return $this->redirect(Url::to(['products-list/index']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
