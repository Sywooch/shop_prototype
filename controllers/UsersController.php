<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use app\helpers\UserAuthenticationHelper;
use app\controllers\AbstractBaseController;
use app\models\UsersModel;
use app\models\EmailsModel;
use app\mappers\UsersInsertMapper;
use app\mappers\UsersRulesInsertMapper;
use app\mappers\UsersByLoginMapper;
use app\factories\UsersRulesAutonomicFactory;

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
                    if (!$emailsModel = $this->getEmailsModel($emailsModel)) {
                        throw new ErrorException('Ошибка при сохранении E-mail!');
                    }
                    $usersModel->id_emails = $emailsModel->id;
                    if (!$this->setUsersModel($usersModel)) {
                        throw new ErrorException('Ошибка при сохранении данных пользователя!');
                    }
                    
                    $usersRulesInsertMapper = new UsersRulesInsertMapper([
                        'tableName'=>'users_rules',
                        'fields'=>['id_users', 'id_rules'],
                        'model'=>$usersModel
                    ]);
                    if (!$result = $usersRulesInsertMapper->setGroup()) {
                        throw new ErrorException('Не удалось добавить данные в БД!');
                    }
                    return $this->redirect(Url::to(['users/login-user', 'added'=>1]));
                }
            }
            
            if (!is_array($dataForRender = $this->getDataForRender())) {
                throw new ErrorException('Ошибка при формировании массива данных!');
            }
            $resultArray = array_merge(['usersModel'=>$usersModel, 'emailsModel'=>$emailsModel], $dataForRender);
            return $this->render('add-user.twig', $resultArray);
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
                    if (!UserAuthenticationHelper::fill($usersModel)) {
                        return $this->redirect(Url::to(['users/add-user', 'notexists'=>$usersModel->login]));
                    }
                    return $this->redirect(Url::to(['products-list/index']));
                }
            }
            
            if (!is_array($dataForRender = $this->getDataForRender())) {
                throw new ErrorException('Ошибка при формировании массива данных!');
            }
            $resultArray = array_merge(['usersModel'=>$usersModel], $dataForRender);
            return $this->render('login-user.twig', $resultArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
