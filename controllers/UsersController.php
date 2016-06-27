<?php

namespace app\controllers;

use app\controllers\AbstractBaseController;
use yii\base\ErrorException;
use app\models\UsersModel;
use app\mappers\UsersInsertMapper;
use app\mappers\UsersRulesInsertMapper;
use app\factories\UsersRulesAutonomicFactory;

/**
 * Управляет работой с пользователями
 */
class UsersController extends AbstractBaseController
{
    /**
     * Управляет процессом создания учетной записи
     */
    public function actionAddUser()
    {
        try {
            $model = new UsersModel(['scenario'=>UsersModel::GET_FROM_FORM]);
            
            if (\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post())) {
                if ($model->validate()) {
                    $usersInsertMapper = new UsersInsertMapper([
                        'tableName'=>'users',
                        'fields'=>['login', 'password', 'name', 'surname'],
                        'objectsArray'=>[$model],
                    ]);
                    if (!$result = $usersInsertMapper->setGroup()) {
                        throw new ErrorException('Не удалось добавить данные в БД!');
                    }
                    
                    $usersRulesInsertMapper = new UsersRulesInsertMapper([
                        'tableName'=>'users_rules',
                        'fields'=>['id_users', 'id_rules'],
                        'model'=>$model
                    ]);
                    if (!$result = $usersRulesInsertMapper->setGroup()) {
                        throw new ErrorException('Не удалось добавить данные в БД!');
                    }
                }
            }
            
            if (!is_array($dataForRender = $this->getDataForRender())) {
                throw new ErrorException('Ошибка при формировании массива данных!');
            }
            $resultArray = array_merge(['model'=>$model], $dataForRender);
            return $this->render('add-user.twig', $resultArray);
        } catch (\Exception $e) {
            $this->writeErrorInLogs($e, __METHOD__);
            $this->throwException($e, __METHOD__);
        }
    }
}
