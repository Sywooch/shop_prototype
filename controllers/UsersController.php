<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\controllers\AbstractBaseController;
use app\helpers\{UserAuthenticationHelper, 
    MappersHelper, 
    ModelsInstancesHelper, 
    SessionHelper};
use app\models\{UsersModel, 
    EmailsModel, 
    PhonesModel, 
    AddressModel, 
    MailingListModel};

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
            $mailingListModel = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_MAILING_FORM]);
            
            if (\Yii::$app->request->isPost && $emailsModel->load(\Yii::$app->request->post()) && $usersModel->load(\Yii::$app->request->post()) && $mailingListModel->load(\Yii::$app->request->post())) {
                if ($emailsModel->validate() && $usersModel->validate() && $mailingListModel->validate()) {
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
                    if (!empty($mailingListModel->idFromForm)) {
                        if (!MappersHelper::setEmailsMailingListInsert($emailsModel, $mailingListModel)) {
                            throw new ErrorException('Ошибка при сохранении связи email с подписками на рассылки!');
                        }
                    }
                    return $this->redirect(Url::to(['users/login-user', 'added'=>true]));
                }
            }
            $renderArray = array();
            $renderArray['usersModel'] = $usersModel;
            $renderArray['emailsModel'] = $emailsModel;
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
                    return $this->redirect(Url::to(['users/show-user-account']));
                }
            }
            $renderArray = array();
            $renderArray['usersModel'] = $usersModel;
            $renderArray['emailsModel'] = $emailsModel;
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
                    if ($usersModel->id == \Yii::$app->shopUser->id) {
                        if (!UserAuthenticationHelper::clean()) {
                            throw new ErrorException('Ошибка при попытку выхода из аккаунта!');
                        }
                        if (!\Yii::$app->cart->cleanUser()) {
                            throw new ErrorException('Ошибка при удалении пользователя!');
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
            if (!empty(\Yii::$app->shopUser)) {
                \Yii::configure($usersModel, \Yii::$app->shopUser->getDataArray());
            }
            
            $emailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_FORM]);
            if (!empty(\Yii::$app->shopUser->emails)) {
                \Yii::configure($emailsModel, \Yii::$app->shopUser->emails->getDataArray());
            }
            
            $phonesModel = new PhonesModel(['scenario'=>PhonesModel::GET_FROM_UPDATE_FORM]);
            if (!empty(\Yii::$app->shopUser->phones)) {
                \Yii::configure($phonesModel, \Yii::$app->shopUser->phones->getDataArray());
            }
            
            $addressModel = new AddressModel(['scenario'=>AddressModel::GET_FROM_UPDATE_FORM]);
            if (!empty(\Yii::$app->shopUser->address)) {
                \Yii::configure($addressModel, \Yii::$app->shopUser->address->getDataArray());
            }
            
            $renderArray = array();
            $renderArray['usersModel'] = $usersModel;
            $renderArray['emailsModel'] = $emailsModel;
            $renderArray['phonesModel'] = $phonesModel;
            $renderArray['addressModel'] = $addressModel;
            $renderArray['purchasesList'] = MappersHelper::getPurchasesForUserList(\Yii::$app->shopUser);
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
                    
                    if (empty(\Yii::$app->shopUser->emails) || \Yii::$app->shopUser->emails->email != $emailsModel->email) {
                        if (!empty($emailsModel->email)) {
                            if ($result = MappersHelper::getEmailsByEmail($emailsModel)) {
                                $emailsModel = $result;
                            } else {
                                if (!MappersHelper::setEmailsInsert($emailsModel)) {
                                    throw new ErrorException('Ошибка при сохранении E-mail!');
                                }
                            }
                        }
                    }
                    if (!empty($emailsModel->id)) {
                        $usersModel->id_emails = $emailsModel->id;
                    }
                    
                    if (empty(\Yii::$app->shopUser->phones) || \Yii::$app->shopUser->phones->phone != $phonesModel->phone) {
                        if (!empty($phonesModel->phone)) {
                            if ($result = MappersHelper::getPhonesByPhone($phonesModel)) {
                                $phonesModel = $result;
                            } else {
                                if (!MappersHelper::setPhonesInsert($phonesModel)) {
                                    throw new ErrorException('Ошибка при сохранении address!');
                                }
                            }
                        }
                    }
                    if (!empty($phonesModel->id)) {
                        $usersModel->id_phones = $phonesModel->id;
                    }
                    
                    if (empty(\Yii::$app->shopUser->address) || !empty(array_diff_assoc($addressModel->getDataArray(), \Yii::$app->shopUser->address->getDataArray()))) {
                        if (!empty($addressModel->address) || !empty($addressModel->city) || !empty($addressModel->postcode) || !empty($addressModel->country)) {
                            if ($result = MappersHelper::getAddressByAddress($addressModel)) {
                                $addressModel = $result;
                            } else {
                                if (!MappersHelper::setAddressInsert($addressModel)) {
                                    throw new ErrorException('Ошибка при сохранении address!');
                                }
                            }
                        }
                    }
                    if (!empty($addressModel->id)) {
                        $usersModel->id_address = $addressModel->id;
                    }
                    
                    if ($usersModel->id_emails && $usersModel->id == \Yii::$app->shopUser->id) {
                        if (!empty(array_diff_assoc($usersModel->getDataForСomparison(), \Yii::$app->shopUser->getDataForСomparison()))) {
                            if (!MappersHelper::setUsersUpdate($usersModel)) {
                                throw new ErrorException('Ошибка при обновлении users!');
                            }
                            \Yii::configure(\Yii::$app->shopUser, $usersModel->getDataArray());
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
