<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\db\Transaction;
use yii\base\Model;
use app\controllers\AbstractBaseController;
use app\helpers\{HashHelper,
    MailHelper,
    InstancesHelper,
    SessionHelper};
use app\models\{EmailsMailingListModel,
    EmailsModel,
    MailingListModel,
    UsersModel};
use app\validators\EmailExistsCreateValidator;
use app\exceptions\ExecutionException;

/**
 * Управляет работой с пользователями
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
                    $usersQuery = UsersModel::find();
                    $usersQuery->extendSelect(['id', 'id_email', 'password', 'name', 'surname', 'id_phone', 'id_address']);
                    $usersQuery->innerJoin('{{emails}}', '[[users.id_email]]=[[emails.id]]');
                    $usersQuery->where(['[[emails.email]]'=>$rawEmailsModel->email]);
                    $usersModel = $usersQuery->one();
                    if (!$usersModel instanceof UsersModel) {
                        if (YII_ENV_DEV) {
                            throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'UsersModel / email: ' . $rawEmailsModel->email]));
                        } else {
                            $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'UsersModel / email: ' . $rawEmailsModel->email]), __METHOD__);
                            $rawEmailsModel->addError('email', \Yii::t('base', 'Account with this email does not exist!'));
                        }
                    } else {
                        if (password_verify($rawUsersModel->password, $usersModel->password)) {
                            \Yii::$app->user->login($usersModel);
                            return $this->redirect(Url::to(['/products-list/index']));
                        }
                        $rawUsersModel->addError('password', \Yii::t('base', 'Password incorrect!'));
                    }
                }
            }
            
            $renderArray = InstancesHelper::getInstances();
            if (!is_array($renderArray) || empty($renderArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray']));
            }
            
            $renderArray['emailsModel'] = $rawEmailsModel;
            $renderArray['usersModel'] = $rawUsersModel;
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/user/login'], 'label'=>\Yii::t('base', 'Authentication')];
            
            return $this->render('login.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
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
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
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
            
            $renderArray = InstancesHelper::getInstances();
            if (!is_array($renderArray) || empty($renderArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray']));
            }
            
            if (\Yii::$app->request->isPost && $rawEmailsModel->load(\Yii::$app->request->post()) && $rawUsersModel->load(\Yii::$app->request->post()) && $rawMailingListModel->load(\Yii::$app->request->post())) {
                if ($rawEmailsModel->validate() && $rawUsersModel->validate() && $rawMailingListModel->validate()) {
                    $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                    
                    try {
                        if (!(new EmailExistsCreateValidator())->validate($rawEmailsModel->email)) {
                            if (!$rawEmailsModel->save(false)) {
                                throw new ExecutionException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'EmailsModel::save']));
                            }
                        }
                        
                        $emailsQuery = EmailsModel::find();
                        $emailsQuery->extendSelect(['id', 'email']);
                        $emailsQuery->where(['[[emails.email]]'=>$rawEmailsModel->email]);
                        $emailsModel = $emailsQuery->one();
                        if (!$emailsModel instanceof EmailsModel || $rawEmailsModel->email != $emailsModel->email) {
                            throw new ExecutionException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'EmailsModel']));
                        }
                        
                        $rawUsersModel->id_email = $emailsModel->id;
                        $rawUsersModel->password = password_hash($rawUsersModel->password, PASSWORD_DEFAULT);
                        
                        if (!$rawUsersModel->save(false)) {
                            throw new ExecutionException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'UsersModel::save']));
                        }
                        
                        $usersQuery = UsersModel::find();
                        $usersQuery->extendSelect(['id', 'id_email']);
                        $usersQuery->where(['[[users.id_email]]'=>$rawUsersModel->id_email]);
                        $usersModel = $usersQuery->one();
                        if (!$usersModel instanceof UsersModel || $rawUsersModel->id_email != $usersModel->id_email) {
                            throw new ExecutionException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'UsersModel']));
                        }
                        
                        if (!\Yii::$app->authManager->assign(\Yii::$app->authManager->getRole('user'), $usersModel->id)) {
                            throw new ExecutionException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'\Yii::$app->authManager']));
                        }
                        
                        if (!empty($rawMailingListModel->id)) {
                            $diff = EmailsMailingListModel::batchInsert($rawMailingListModel, $emailsModel);
                            if (!empty($diff)) {
                                $mailingListQuery = MailingListModel::find();
                                $mailingListQuery->extendSelect(['name']);
                                $mailingListQuery->where(['[[mailing_list.id]]'=>$diff]);
                                $subscribes = $mailingListQuery->all();
                                if (!is_array($subscribes) || (!empty($subscribes) && !$subscribes[0] instanceof MailingListModel)) {
                                    throw new ExecutionException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'MailingListModel']));
                                }
                            }
                        }
                        
                        $sent = MailHelper::send([
                            [
                                'template'=>'@theme/mail/registration-mail.twig', 
                                'setFrom'=>['admin@shop.com'=>'Shop'], 
                                'setTo'=>['timofey@localhost.localdomain'=>'Timofey'], 
                                'setSubject'=>\Yii::t('base', 'Registration on shop.com'), 
                                'dataForTemplate'=>[
                                    'email'=>$rawEmailsModel->email,
                                    'subscribes'=>$subscribes ?? false,
                                ],
                            ]
                        ]);
                        
                        if ($sent < 1) {
                            throw new ExecutionException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'MailHelper::send']));
                        }
                        
                        $transaction->commit();
                        return $this->redirect(Url::to(['/user/login']));
                    } catch (ExecutionException $t) {
                        $transaction->rollBack();
                        if (YII_ENV_DEV) {
                            throw $t;
                        } else {
                            $this->writeMessageInLogs(\Yii::t('base/errors', 'Registration error!'), __METHOD__);
                            $renderArray['errorMessage'] = \Yii::t('base/errors', 'Registration error!');
                        }
                    } catch (\Throwable $t) {
                        $transaction->rollBack();
                        throw $t;
                    }
                }
            }
            
            $renderArray['emailsModel'] = $rawEmailsModel;
            $renderArray['usersModel'] = $rawUsersModel;
            $renderArray['mailingListModel'] = $rawMailingListModel;
            
            $mailingListQuery = MailingListModel::find();
            $mailingListQuery->extendSelect(['id', 'name']);
            $renderArray['mailingListList'] = $mailingListQuery->allMap('id', 'name');
            if (!is_array($renderArray['mailingListList']) || empty($renderArray['mailingListList'])) {
                if (YII_ENV_DEV) {
                    throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'mailingListList\']']));
                } else {
                    $renderArray['mailingListList'] = [];
                    $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray[\'mailingListList\']']), __METHOD__);
                }
            }
            asort($renderArray['mailingListList'], SORT_STRING);
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/user/registration'], 'label'=>\Yii::t('base', 'Registration')];
            
            return $this->render('registration.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
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
            
            $renderArray = InstancesHelper::getInstances();
            if (!is_array($renderArray) || empty($renderArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray']));
            }
            
            if (\Yii::$app->request->isPost && $rawEmailsModel->load(\Yii::$app->request->post())) {
                if ($rawEmailsModel->validate()) {
                    $emailsQuery = EmailsModel::find();
                    $emailsQuery->extendSelect(['id', 'email']);
                    $emailsQuery->where(['[[emails.email]]'=>$rawEmailsModel->email]);
                    $emailsModel = $emailsQuery->one();
                    if (!$emailsModel instanceof EmailsModel) {
                        if (YII_ENV_DEV) {
                            throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'EmailsModel / email: ' . $rawEmailsModel->email]));
                        } else {
                            $this->writeMessageInLogs(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'EmailsModel / email: ' . $rawEmailsModel->email]), __METHOD__);
                            $rawEmailsModel->addError('email', \Yii::t('base', 'Account with this email does not exist!'));
                        }
                    } else {
                        $sent = MailHelper::send([
                            [
                                'template'=>'@theme/mail/forgot-mail.twig', 
                                'setFrom'=>['admin@shop.com'=>'Shop'], 
                                'setTo'=>['timofey@localhost'=>'Timofey'], 
                                'setSubject'=>\Yii::t('base', 'Password restore to shop.com'), 
                                'dataForTemplate'=>[
                                    'email'=>$emailsModel->email,
                                    'key'=>HashHelper::createHashRestore($emailsModel),
                                ],
                            ]
                        ]);
                        if ($sent < 1) {
                            if (YII_ENV_DEV) {
                                throw new ErrorException(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'MailHelper::send']));
                            } else {
                                $this->writeMessageInLogs(\Yii::t('base/errors', 'Method error {placeholder}!', ['placeholder'=>'MailHelper::send']), __METHOD__);
                                $renderArray['errorMessage'] = \Yii::t('base/errors', 'Error in password recovery!');
                            }
                        } else {
                            $renderArray['sent'] = true;
                        }
                    }
                }
            }
            
            $renderArray['emailsModel'] = $rawEmailsModel;
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/user/restore'], 'label'=>\Yii::t('base', 'Password restore')];
            
            return $this->render('forgot.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
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
            
            $renderArray = InstancesHelper::getInstances();
            if (!is_array($renderArray) || empty($renderArray)) {
                throw new ErrorException(\Yii::t('base/errors', 'Received invalid data type instead {placeholder}!', ['placeholder'=>'array $renderArray']));
            }
            
            if (\Yii::$app->request->isGet && \Yii::$app->request->get('email') && \Yii::$app->request->get('key')) {
                $emailsQuery = EmailsModel::find();
                $emailsQuery->extendSelect(['id', 'email']);
                $emailsQuery->where(['[[emails.email]]'=>\Yii::$app->request->get('email')]);
                $emailsModel = $emailsQuery->one();
                if ($emailsModel instanceof EmailsModel) {
                    $salt = SessionHelper::readFlash('restore.' . \Yii::$app->request->get('email'));
                    $expectedHash = HashHelper::createHash([$emailsModel->email, $emailsModel->id, $emailsModel->users->id, $salt]);
                    $renderArray['allow'] = ($expectedHash == \Yii::$app->request->get('key')) ? true : false;
                    $renderArray['key'] = HashHelper::createHashRestore($emailsModel);
                    $renderArray['email'] = $emailsModel->email;
                }
            }
            
            if (\Yii::$app->request->isPost && Model::loadMultiple([$rawUsersModel, $rawUsersModelConfirm], \Yii::$app->request->post())) {
                if (Model::validateMultiple([$rawUsersModel, $rawUsersModelConfirm])) {
                    $emailsQuery = EmailsModel::find();
                    $emailsQuery->extendSelect(['id', 'email']);
                    $emailsQuery->where(['[[emails.email]]'=>\Yii::$app->request->post('email')]);
                    $emailsModel = $emailsQuery->one();
                    if ($emailsModel instanceof EmailsModel) {
                        $salt = SessionHelper::readFlash('restore.' . \Yii::$app->request->post('email'));
                        $expectedHash = HashHelper::createHash([$emailsModel->email, $emailsModel->id, $emailsModel->users->id, $salt]);
                        if ($expectedHash == \Yii::$app->request->post('key')) {
                            if ($rawUsersModel->password != $rawUsersModelConfirm->password) {
                                $rawUsersModelConfirm->addError('password', \Yii::t('base', 'Passwords do not match!'));
                                $renderArray['allow'] = true;
                                $renderArray['key'] = HashHelper::createHashRestore($emailsModel);
                                $renderArray['email'] = $emailsModel->email;
                            } else {
                                $transaction = \Yii::$app->db->beginTransaction(Transaction::REPEATABLE_READ);
                                try {
                                    $usersModel = $emailsModel->users;
                                    $usersModel->scenario = UsersModel::GET_FROM_AUTHENTICATION;
                                    $usersModel->password = password_hash($rawUsersModel->password, PASSWORD_DEFAULT);
                                    $usersModel->save();
                                    $transaction->commit();
                                    $renderArray['complete'] = true;
                                } catch (\Throwable $t) {
                                    $transaction->rollBack();
                                    if (YII_ENV_DEV) {
                                        throw $t;
                                    } else {
                                        $this->writeMessageInLogs(\Yii::t('base/errors', 'Error in password recovery!'), __METHOD__);
                                        $renderArray['errorMessage'] = \Yii::t('base/errors', 'Error in password recovery!');
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            $renderArray['usersModel'] = [$rawUsersModel, $rawUsersModelConfirm];
            
            \Yii::$app->params['breadcrumbs'] = ['url'=>['/user/restore'], 'label'=>\Yii::t('base', 'Password restore')];
            
            return $this->render('restore.twig', $renderArray);
        } catch (\Throwable $t) {
            $this->writeErrorInLogs($t, __METHOD__);
            $this->throwException($t, __METHOD__);
        }
    }
}
