<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\db\Transaction;
use app\controllers\AbstractBaseController;
use app\helpers\{HashHelper,
    MailHelper,
    InstancesHelper};
use app\models\{EmailsMailingListModel,
    EmailsModel,
    MailingListModel,
    UsersModel};
use app\validators\EmailExistsCreateValidator;

/**
 * Управляет работой с пользователями
 * @return string
 */
class UserController extends AbstractBaseController
{
    /**
     * Управляет процессом аутентификации
     * @return string
     */
    public function actionLogin()
    {
        try {
            $rawEmailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_AUTHENTICATION]);
            $rawUsersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_AUTHENTICATION]);
            
            if (\Yii::$app->request->isPost && $rawEmailsModel->load(\Yii::$app->request->post()) && $rawUsersModel->load(\Yii::$app->request->post())) {
                if ($rawEmailsModel->validate() && $rawUsersModel->validate()) {
                    $usersModel = UsersModel::find()->extendSelect(['id', 'id_email', 'password', 'name', 'surname', 'id_phone', 'id_address'])->innerJoin('emails', '[[users.id_email]]=[[emails.id]]')->where(['emails.email'=>$rawEmailsModel->email])->one();
                    if (!$usersModel instanceof UsersModel) {
                        $rawEmailsModel->addError('email', \Yii::t('base', 'Account with this email does not exist!'));
                    }
                    
                    if (password_verify($rawUsersModel->password, $usersModel->password)) {
                        \Yii::$app->user->login($usersModel);
                        return $this->redirect(Url::to(['/products-list/index']));
                    }
                    
                    $rawUsersModel->addError('password', \Yii::t('base', 'Password incorrect!'));
                }
            }
            
            $renderArray = array();
            $renderArray['emailsModel'] = $rawEmailsModel;
            $renderArray['usersModel'] = $rawUsersModel;
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/user/login'], 'label'=>\Yii::t('base', 'Authentication')];
            
            return $this->render('login.twig', array_merge($renderArray, InstancesHelper::getInstances()));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом logout
     * @return string
     */
    public function actionLogout()
    {
        try {
            if (\Yii::$app->request->isPost && !empty(\Yii::$app->request->post('userId'))) {
                if (\Yii::$app->user->id === (int) \Yii::$app->request->post('userId')) {
                    \Yii::$app->user->logout();
                }
            }
            
            return $this->redirect(Url::to(['/products-list/index']));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом создания учетной записи
     * @return string
     */
    public function actionRegistration()
    {
        try {
            $rawEmailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_REGISTRATION]);
            $rawUsersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_REGISTRATION]);
            $rawMailingListModel = new MailingListModel(['scenario'=>MailingListModel::GET_FROM_REGISTRATION]);
            
            if (\Yii::$app->request->isPost && $rawEmailsModel->load(\Yii::$app->request->post()) && $rawUsersModel->load(\Yii::$app->request->post()) && $rawMailingListModel->load(\Yii::$app->request->post())) {
                if ($rawEmailsModel->validate() && $rawUsersModel->validate() && $rawMailingListModel->validate()) {
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!(new EmailExistsCreateValidator())->validate($rawEmailsModel->email)) {
                            if (!$rawEmailsModel->save()) {
                                throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'EmailsModel::save']));
                            }
                        }
                        $emailsModel = EmailsModel::find()->extendSelect(['id', 'email'])->where(['emails.email'=>$rawEmailsModel->email])->one();
                        if (!$emailsModel instanceof EmailsModel || $rawEmailsModel->email != $emailsModel->email) {
                            throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'EmailsModel']));
                        }
                        
                        $rawUsersModel->id_email = $emailsModel->id;
                        $rawUsersModel->password = password_hash($rawUsersModel->password, PASSWORD_DEFAULT);
                        
                        if (!$rawUsersModel->save()) {
                            throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'UsersModel::save']));
                        }
                        $usersModel = UsersModel::find()->extendSelect(['id', 'id_email'])->where(['users.id_email'=>$rawUsersModel->id_email])->one();
                        if (!$usersModel instanceof UsersModel || $rawUsersModel->id_email != $usersModel->id_email) {
                            throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'UsersModel']));
                        }
                        
                        \Yii::$app->authManager->assign(\Yii::$app->authManager->getRole('user'), $usersModel->id);
                        
                        if (!empty($rawMailingListModel->id)) {
                            $diff = EmailsMailingListModel::batchInsert($rawMailingListModel, $emailsModel);
                            if (!empty($diff)) {
                                $subscribes = MailingListModel::find()->extendSelect(['name'])->where(['mailing_list.id'=>$diff])->all();
                            }
                        }
                        
                        if (!MailHelper::send([
                            [
                                'template'=>'@theme/mail/registration-mail.twig', 
                                'setFrom'=>['admin@shop.com'=>'Shop'], 
                                'setTo'=>['timofey@localhost.localdomain'=>'Timofey'], 
                                'setSubject'=>\Yii::t('base', 'Registration on shop.com'), 
                                'dataForTemplate'=>[
                                    'email'=>$rawEmailsModel->email,
                                    'subscribes'=>isset($subscribes) ? $subscribes : false,
                                ],
                            ]
                        ])) {
                            throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'MailHelper::send']));
                        }
                        
                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                    
                    return $this->redirect(Url::to(['/user/login']));
                }
            }
            
            $renderArray = array();
            $renderArray['emailsModel'] = $rawEmailsModel;
            $renderArray['usersModel'] = $rawUsersModel;
            $renderArray['mailingListModel'] = $rawMailingListModel;
            
            $mailingListQuery = MailingListModel::find();
            $mailingListQuery->extendSelect(['id', 'name']);
            $renderArray['mailingListList'] = $mailingListQuery->all();
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/user/registration'], 'label'=>\Yii::t('base', 'Registration')];
            
            return $this->render('registration.twig', array_merge($renderArray, InstancesHelper::getInstances()));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом отправки ссылки для смены пароля
     * @return string
     */
    public function actionForgot()
    {
        try {
            $rawEmailsModel = new EmailsModel(['scenario'=>EmailsModel::GET_FROM_AUTHENTICATION]);
            
            $renderArray = array();
            
            if (\Yii::$app->request->isPost && $rawEmailsModel->load(\Yii::$app->request->post())) {
                if ($rawEmailsModel->validate()) {
                    $emailsModel = EmailsModel::find()->extendSelect(['id', 'email'])->where(['emails.email'=>$rawEmailsModel->email])->one();
                    if (!$emailsModel instanceof EmailsModel || $emailsModel->email != $rawEmailsModel->email) {
                        throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'EmailsModel']));
                    }
                    $usersModel = UsersModel::find()->extendSelect(['id', 'id_email'])->where(['users.id_email'=>$emailsModel->id])->one();
                    if (!$usersModel instanceof UsersModel || $usersModel->id_email != $emailsModel->id) {
                        throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'UsersModel']));
                    }
                    if (!MailHelper::send([
                        [
                            'template'=>'@theme/mail/forgot-mail.twig', 
                            'setFrom'=>['admin@shop.com'=>'Shop'], 
                            'setTo'=>['timofey@localhost.localdomain'=>'Timofey'], 
                            'setSubject'=>\Yii::t('base', 'Password restore to shop.com'), 
                            'dataForTemplate'=>[
                                'email'=>$emailsModel->email,
                                'restoreKey'=>HashHelper::createHash([$emailsModel->email, $emailsModel->id, $usersModel->id]),
                            ],
                        ]
                    ])) {
                        throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'MailHelper::send']));
                    }
                    $renderArray['sent'] = true;
                }
            }
            
            $renderArray['emailsModel'] = $rawEmailsModel;
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/user/restore'], 'label'=>\Yii::t('base', 'Password restore')];
            
            return $this->render('forgot.twig', array_merge($renderArray, InstancesHelper::getInstances()));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
    
    /**
     * Управляет процессом замены пароля при восстановлении
     * @return string
     */
    public function actionRestore()
    {
        try {
            $rawUsersModel = new UsersModel(['scenario'=>UsersModel::GET_FROM_AUTHENTICATION]);
            $rawUsersModelConfirm = new UsersModel(['scenario'=>UsersModel::GET_FROM_AUTHENTICATION]);
            
            $renderArray = array();
            
            if (\Yii::$app->request->isGet && \Yii::$app->request->get('email') && \Yii::$app->request->get('key')) {
                $emailsModel = EmailsModel::find()->extendSelect(['id', 'email'])->where(['emails.email'=>\Yii::$app->request->get('email')])->one();
                $usersModel = UsersModel::find()->extendSelect(['id'])->where(['users.id_email'=>$emailsModel->id])->one();
                
                $expectedHash = HashHelper::createHash([$emailsModel->email, $emailsModel->id, $usersModel->id]);
                $renderArray['allow'] = ($expectedHash == \Yii::$app->request->get('key')) ? true : false;
            }
            
            $renderArray['usersModel'] = $rawUsersModel;
            $renderArray['usersModelConfirm'] = $rawUsersModelConfirm;
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/user/restore'], 'label'=>\Yii::t('base', 'Password restore')];
            
            return $this->render('restore.twig', array_merge($renderArray, InstancesHelper::getInstances()));
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
