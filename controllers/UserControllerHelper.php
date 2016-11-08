<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\{ArrayHelper,
    Url};
use yii\db\Transaction;
use app\controllers\AbstractControllerHelper;
use app\exceptions\ExceptionsTrait;
use app\models\{EmailsMailingListModel,
    EmailsModel,
    MailingListModel,
    UsersModel};
use app\helpers\{InstancesHelper,
    MailHelper,
    SessionHelper};
use app\validators\EmailExistsCreateValidator;

/**
 * Коллекция сервис-методов UserController
 */
class UserControllerHelper extends AbstractControllerHelper
{
    /**
     * @var object EmailsModel
     */
    private static $_rawEmailsModel;
    /**
     * @var object UsersModel
     */
    private static $_rawUsersModel;
    /**
     * @var object EmailsModel в контексте регистрации
     */
    private static $_rawEmailsModelReg;
    /**
     * @var object UsersModel в контексте регистрации
     */
    private static $_rawUsersModelReg;
    /**
     * @var object MailingListModel в контексте регистрации
     */
    private static $_rawMailingListModelReg;
    /**
     * @var bool флаг, отмечающий было ли отправлено письмо с новым паролем
     */
    private static $_sentPassword = false;
    
    /**
     * Конструирует данные для UserController::actionLogin()
     * @return array
     */
    public static function loginGet(): array
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            
            self::models();
            
            $renderArray['emailsModel'] = self::$_rawEmailsModel;
            $renderArray['usersModel'] = self::$_rawUsersModel;
            
            self::breadcrumbs();
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает POST запрос для UserController::actionLogin()
     * @return bool
     */
    public static function loginPost(): bool
    {
        try {
            self::models();
            
            if (self::$_rawEmailsModel->load(\Yii::$app->request->post()) && self::$_rawUsersModel->load(\Yii::$app->request->post())) {
                if (self::$_rawEmailsModel->validate() && self::$_rawUsersModel->validate()) {
                    
                    $usersModel = self::getUserByEmail(self::$_rawEmailsModel['email']);
                    
                    if (!empty($usersModel)) {
                        if (password_verify(self::$_rawUsersModel['password'], $usersModel['password'])) {
                            if ($isLogin = \Yii::$app->user->login($usersModel)) {
                                SessionHelper::write(\Yii::$app->params['userKey'], !empty($usersModel['name']) ? $usersModel['name']['name'] : $usersModel['email']['email']);
                            }
                            return $isLogin;
                        }
                        self::$_rawUsersModel->addError('password', \Yii::t('base', 'Password incorrect!'));
                    } else {
                        self::$_rawEmailsModel->addError('email', \Yii::t('base', 'Account with this email does not exist!'));
                    }
                }
            }
            
            return false;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает POST запрос для UserController::actionLogout()
     * @return bool
     */
    public static function logoutPost()
    {
        try {
            if (!empty(\Yii::$app->request->post('userId'))) {
                if (\Yii::$app->user->id === (int) \Yii::$app->request->post('userId')) {
                    \Yii::$app->user->logout();
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует данные для UserController::actionRegistration()
     * @return array
     */
    public static function registrationGet(): array
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            
            self::modelsReg();
            
            $renderArray['emailsModel'] = self::$_rawEmailsModelReg;
            $renderArray['usersModel'] = self::$_rawUsersModelReg;
            $renderArray['mailingListModel'] = self::$_rawMailingListModelReg;
            
            $renderArray['mailingListList'] = self::getMailingListMap(true);
            
            self::registrationBreadcrumbs();
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает POST запрос для UserController::actionRegistration()
     * @return bool
     */
    public static function registrationPost(): bool
    {
        try {
            self::modelsReg();
            
            if (self::$_rawEmailsModelReg->load(\Yii::$app->request->post()) && self::$_rawUsersModelReg->load(\Yii::$app->request->post()) && self::$_rawMailingListModelReg->load(\Yii::$app->request->post())) {
                if (self::$_rawEmailsModelReg->validate() && self::$_rawUsersModelReg->validate() && self::$_rawMailingListModelReg->validate()) {
                    
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        self::saveCheckEmail(self::$_rawEmailsModelReg);
                        $emailsModel = self::getEmail(self::$_rawEmailsModelReg->email);
                        
                        self::$_rawUsersModelReg->id_email = $emailsModel['id'];
                        self::$_rawUsersModelReg->password = password_hash(self::$_rawUsersModelReg['password'], PASSWORD_DEFAULT);
                        
                        if (!self::$_rawUsersModelReg->save(false)) {
                            throw new ErrorException(ExceptionsTrait::methodError('UsersModel::save'));
                        }
                        $usersModel = self::getUserPlus(0, self::$_rawUsersModelReg->id_email, false, true);
                        
                        if (!\Yii::$app->authManager->assign(\Yii::$app->authManager->getRole('user'), $usersModel['id'])) {
                            throw new ErrorException(ExceptionsTrait::methodError('\Yii::$app->authManager'));
                        }
                        
                        if (!empty(self::$_rawMailingListModelReg['id'])) {
                            $diff = EmailsMailingListModel::batchInsert($emailsModel, self::$_rawMailingListModelReg);
                            if (!empty($diff)) {
                                $subscribes = self::getMailing($diff, true);
                            }
                        }
                        
                        $sent = MailHelper::send([
                            [
                                'template'=>'@theme/mail/registration-mail.twig', 
                                'from'=>['admin@shop.com'=>'Shop'], 
                                'to'=>['timofey@localhost'=>'Timofey'], 
                                'subject'=>\Yii::t('base', 'Registration on shop.com'), 
                                'templateData'=>[
                                    'email'=>self::$_rawEmailsModelReg['email'],
                                    'subscribes'=>$subscribes ?? false,
                                ],
                            ]
                        ]);
                        if ($sent < 1) {
                            throw new ErrorException(ExceptionsTrait::methodError('MailHelper::send'));
                        }
                        
                        $transaction->commit();
                        
                        return true;
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        throw $t;
                    }
                }
            }
            
            return false;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует данные для UserController::actionForgot()
     * @return array
     */
    public static function forgotGet(): array
    {
        try {
            $renderArray = InstancesHelper::getInstances();
            
            self::models();
            
            $renderArray['emailsModel'] = self::$_rawEmailsModel;
            $renderArray['sent'] = self::$_sentPassword;
            
            self::forgotBreadcrumbs();
            
            return $renderArray;
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Обрабатывает POST запрос для UserController::actionForgot()
     */
    public static function forgotPost()
    {
        try {
            self::models();
            
            if (self::$_rawEmailsModel->load(\Yii::$app->request->post())) {
                if (self::$_rawEmailsModel->validate()) {
                    $emailsModel = self::getEmail(self::$_rawEmailsModel['email'], true);
                    
                    if (!empty($emailsModel)) {
                        $usersModel = self::getUserPlus(0, $emailsModel['id'], false, true);
                        
                        if (!empty($usersModel)) {
                            $newPassword = substr(sha1(time()), 0, 10);
                            
                            $usersModel->password = password_hash($newPassword, PASSWORD_DEFAULT);
                            if (!$usersModel->save(false)) {
                                throw new ErrorException(ExceptionsTrait::methodError('UsersModel::save'));
                            }
                            
                            $sent = MailHelper::send([
                                [
                                    'template'=>'@theme/mail/forgot-mail.twig', 
                                    'from'=>['admin@shop.com'=>'Shop'], 
                                    'to'=>['timofey@localhost'=>'Timofey'], 
                                    'subject'=>\Yii::t('base', 'Password restore'), 
                                    'templateData'=>[
                                        'password'=>$newPassword,
                                    ],
                                ]
                            ]);
                            
                            if ($sent < 1) {
                                throw new ErrorException(ExceptionsTrait::methodError('MailHelper::send'));
                            }
                            
                            self::$_sentPassword = true;
                        }
                    }
                }
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует модели
     */
    private static function models()
    {
        try {
            if (empty(self::$_rawEmailsModel)) {
                self::$_rawEmailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_AUTHENTICATION]);
            }
            if (empty(self::$_rawUsersModel)) {
                self::$_rawUsersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_AUTHENTICATION]);
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Конструирует модели для
     */
    private static function modelsReg()
    {
        try {
            if (empty(self::$_rawEmailsModelReg)) {
                self::$_rawEmailsModelReg = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_REGISTRATION]);
            }
            if (empty(self::$_rawUsersModelReg)) {
                self::$_rawUsersModelReg = new UsersModel(['scenario'=>UsersModel::GET_FROM_REGISTRATION]);
            }
            if (empty(self::$_rawMailingListModelReg)) {
                self::$_rawMailingListModelReg = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_REGISTRATION]);
            }
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет данными массив \Yii::$app->params['breadcrumbs']
     */
    private static function breadcrumbs()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/user/login'], 'label'=>\Yii::t('base', 'Authentication')];
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет данными массив \Yii::$app->params['breadcrumbs']
     */
    private static function registrationBreadcrumbs()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/user/login'], 'label'=>\Yii::t('base', 'Registration')];
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
    
    /**
     * Заполняет данными массив \Yii::$app->params['breadcrumbs'] 
     */
    private static function forgotBreadcrumbs()
    {
        try {
            \Yii::$app->params['breadcrumbs'][] = ['url'=>['/user/restore'], 'label'=>\Yii::t('base', 'Password restore')];
        } catch (\Throwable $t) {
            ExceptionsTrait::throwStaticException($t, __METHOD__);
        }
    }
}
