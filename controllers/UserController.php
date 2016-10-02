<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\db\Transaction;
use app\controllers\AbstractBaseController;
use app\helpers\InstancesHelper;
use app\models\{EmailsModel,
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
                    $usersQuery = UsersModel::find();
                    $usersQuery->extendSelect(['id', 'id_email', 'password', 'name', 'surname', 'id_phone', 'id_address']);
                    $usersQuery->innerJoin('emails', '[[users.id_email]]=[[emails.id]]');
                    $usersQuery->where(['emails.email'=>$rawEmailsModel->email]);
                    $usersModel = $usersQuery->one();
                    if (!is_object($usersModel) || !$usersModel instanceof UsersModel) {
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
                            $rawEmailsModel->save();
                        }
                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
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
}
